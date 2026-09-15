<?php

namespace App\Http\Controllers\Api\Shop;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\Product\ProductResource;
use App\Models\Shop;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ShopProductController extends Controller
{
    public function myProducts(Request $request): JsonResponse
    {
        $shop = $request->user()->shop;

        if (!$shop) {
            return response()->json([
                'success' => false,
                'message' => 'Shop not found for this user.',
            ], 404);
        }

        $products = $shop->products()
            ->with(['category', 'images', 'attributes', 'shop'])
            ->withExists([
                'favoritedBy as is_favorited_by_me' => fn ($q) =>
                    $q->where('user_id', $request->user()->id),
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

   public function productsByShop(string $slug, Request $request): JsonResponse
    {
        $shop = Shop::where('slug', $slug)->first();

        if (!$shop) {
            return response()->json([
                'success' => false,
                'message' => 'Shop not found.',
            ], 404);
        }

        $products = $shop->products()
            ->where('status', 'published')
            ->with(['category', 'images', 'attributes', 'shop'])
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
}