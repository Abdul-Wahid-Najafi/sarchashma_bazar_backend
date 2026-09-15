<?php

namespace App\Http\Controllers\Api\Product;

use App\Actions\Product\CreateProductAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Product\StoreProductRequest;
use App\Http\Resources\Api\Product\ProductResource;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\Category; 
use Illuminate\Support\Facades\Storage; 

use App\Actions\Product\UpdateProductAction;
use App\Http\Requests\Product\UpdateProductRequest;

class ProductController extends Controller
{
    public function store(StoreProductRequest $request, CreateProductAction $action)
    {
        $shop = $request->user()->shop;

        if (!$shop) {
            return response()->json([
                'message' => 'You need to create a shop first.',
            ], 403);
        }

        $product = $action->execute($shop, $request->validated());

        return (new ProductResource($product))
            ->additional(['message' => 'Uploaded successfully. Your product is now pending approval.']);
    }

    /**
     * 🆕 فید عمومی همه محصولات (صفحه‌بندی‌شده، فقط published)
     */
    public function index(Request $request): JsonResponse
    {
        $products = Product::query()
            ->where('status', 'published')
            ->with(['category', 'images', 'shop'])
            ->withExists([
                'favoritedBy as is_favorited_by_me' => fn ($q) =>
                    $q->where('user_id', $request->user('sanctum')?->id ?? 0),
            ])
            ->latest()
            ->paginate(15);

        return response()->json([
            'success' => true,
            'data'    => ProductResource::collection($products),
            'meta'    => [
                'current_page' => $products->currentPage(),
                'last_page'    => $products->lastPage(),
                'total'        => $products->total(),
            ],
        ], 200);
    }


    public function show(string $slug): JsonResponse
    {
        $product = Product::where('slug', $slug)
            ->where('status', 'published')
            ->with(['category', 'images', 'attributes', 'shop'])
            ->withExists([
                'favoritedBy as is_favorited_by_me' => fn ($q) =>
                    $q->where('user_id', request()->user('sanctum')?->id ?? 0),
            ])
            ->first();

        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => new ProductResource($product),
        ], 200);
    }
        
     //similar products 

    // public function similar(string $slug): JsonResponse
    // {
    //     $product = Product::where('slug', $slug)
    //         ->where('status', 'published')
    //         ->first();

    //     if (!$product) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Product not found.',
    //         ], 404);
    //     }

    //     $similar = Product::where('status', 'published')
    //         ->where('category_id', $product->category_id)
    //         ->where('id', '!=', $product->id)
    //         ->with(['category', 'images', 'shop'])
    //         ->latest()
    //         ->limit(10)
    //         ->get();
        
    //     info('Product: ' . $similar);

    //     return response()->json([
    //         'success' => true,
    //         'data' => ProductResource::collection($similar),
    //     ], 200);
    // }


    public function similar(string $slug): JsonResponse
    {
        $product = Product::where('slug', $slug)
            ->where('status', 'published')
            ->with('category')
            ->first();

        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found.',
            ], 404);
        }

        $maxResults = 12;
        $ids = collect();
        $excludedIds = [$product->id];

        $addIds = function (callable $queryBuilder) use (&$ids, &$excludedIds, $maxResults) {
            $remaining = $maxResults - $ids->count();

            if ($remaining <= 0) {
                return;
            }

            $found = $queryBuilder()
                ->whereNotIn('id', $excludedIds)
                ->latest()
                ->limit($remaining)
                ->pluck('id');

            foreach ($found as $id) {
                $ids->push($id);
                $excludedIds[] = $id;
            }
        };

        // اولویت ۱: همان زیرکتگوری دقیق (leaf)
        $addIds(fn () => Product::where('status', 'published')
            ->where('category_id', $product->category_id));

        // اولویت ۲: کتگوری‌های خواهر زیر همان والد
        if ($ids->count() < $maxResults && $product->category?->parent_id) {
            $siblingCategoryIds = Category::where('parent_id', $product->category->parent_id)
                ->where('id', '!=', $product->category_id)
                ->pluck('id');

            if ($siblingCategoryIds->isNotEmpty()) {
                $addIds(fn () => Product::where('status', 'published')
                    ->whereIn('category_id', $siblingCategoryIds));
            }
        }

        // اولویت ۳: شباهت در نام محصول
        if ($ids->count() < $maxResults) {
            $words = collect(preg_split('/\s+/', $product->name))
                ->filter(fn ($word) => mb_strlen($word) >= 3)
                ->take(3);

            if ($words->isNotEmpty()) {
                $addIds(function () use ($words) {
                    return Product::where('status', 'published')
                        ->where(function ($query) use ($words) {
                            foreach ($words as $word) {
                                $query->orWhere('name', 'like', "%{$word}%");
                            }
                        });
                });
            }
        }

        // اولویت ۴: همان فروشنده
        if ($ids->count() < $maxResults) {
            $addIds(fn () => Product::where('status', 'published')
                ->where('shop_id', $product->shop_id));
        }

        if ($ids->isEmpty()) {
            return response()->json(['success' => true, 'data' => []], 200);
        }

        // یک کوئری نهایی، با حفظ ترتیب اولویت
        $products = Product::whereIn('id', $ids)
        ->with(['category', 'images', 'shop'])
        ->withExists([
            'favoritedBy as is_favorited_by_me' => fn ($q) =>
                $q->where('user_id', request()->user('sanctum')?->id ?? 0),
        ])
        ->get()
        ->sortBy(fn ($item) => $ids->search($item->id))
        ->values();

        return response()->json([
            'success' => true,
            'data' => ProductResource::collection($products),
        ], 200);
    }

    public function destroy(Product $product): JsonResponse
    {
        if ($product->shop_id !== auth()->user()->shop?->id) {
            return response()->json([
                'success' => false,
                'message' => 'You are not authorized to delete this product.',
            ], 403);
        }

        foreach ($product->images as $image) {
            Storage::disk('public')->delete($image->image);
        }

        $product->delete();

        return response()->json([
            'success' => true,
            'message' => 'Product deleted successfully.',
        ], 200);
    }

    public function update(UpdateProductRequest $request, Product $product, UpdateProductAction $action)
    {
        $updated = $action->execute($product, $request->validated());

        return (new ProductResource($updated))
            ->additional(['message' => 'محصول به‌روزرسانی شد و در انتظار تأیید مجدد است.']);
    }
}