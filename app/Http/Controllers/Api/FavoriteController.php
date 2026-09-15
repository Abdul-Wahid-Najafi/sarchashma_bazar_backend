<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\Product\ProductResource;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    public function store(string $slug, Request $request): JsonResponse
    {
        $product = Product::where('slug', $slug)
            ->where('status', 'published')
            ->first();

        if (!$product) {
            return response()->json(['success' => false, 'message' => 'Product not found.'], 404);
        }

        $product->favoritedBy()->syncWithoutDetaching([$request->user()->id]);

        return response()->json(['success' => true, 'message' => 'Added to favorites.'], 200);
    }

    public function destroy(string $slug, Request $request): JsonResponse
    {
        $product = Product::where('slug', $slug)->first();

        if (!$product) {
            return response()->json(['success' => false, 'message' => 'Product not found.'], 404);
        }

        $product->favoritedBy()->detach($request->user()->id);

        return response()->json(['success' => true, 'message' => 'Removed from favorites.'], 200);
    }

    public function index(Request $request): JsonResponse
    {
        $products = $request->user()->favoriteProducts()
            ->where('status', 'published')
            ->with(['category', 'images', 'shop'])
            ->withExists([
                'favoritedBy as is_favorited_by_me' => fn ($q) =>
                    $q->where('user_id', $request->user()->id),
            ])
            ->latest('product_favorites.created_at')
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
}