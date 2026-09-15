<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\Product\ProductResource;
use App\Http\Resources\Api\Shop\FollowedShopResource;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\Shop;
use App\Http\Resources\Api\Shop\DiscoverShopResource;

class FollowingController extends Controller
{
    /**
     * محصولات همه‌ی شاپ‌هایی که کاربر فعلی فالو کرده
     */
    public function products(Request $request): JsonResponse
    {
        $shopIds = $request->user()->followedShops()->pluck('shops.id');

        $products = Product::whereIn('shop_id', $shopIds)
            ->where('status', 'published')
            ->with(['category', 'images', 'shop'])
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

    /**
     * لیست شاپ‌هایی که کاربر فعلی فالو کرده
     */
    public function shops(Request $request): JsonResponse
    {
        $shops = $request->user()->followedShops()
            ->withCount(['followers', 'products'])
            ->latest('shop_followers.created_at')
            ->paginate(15);

        return response()->json([
            'success' => true,
            'data'    => FollowedShopResource::collection($shops),
            'meta'    => [
                'current_page' => $shops->currentPage(),
                'last_page'    => $shops->lastPage(),
                'total'        => $shops->total(),
            ],
        ], 200);
    }


    public function discover(Request $request): JsonResponse
{
    $ownShopId = $request->user()->shop?->id;
    $followedShopIds = $request->user()->followedShops()->pluck('shops.id');

    $shops = Shop::query()
        ->when($ownShopId, fn ($query) => $query->where('id', '!=', $ownShopId))
        ->whereNotIn('id', $followedShopIds)
        ->withCount(['followers', 'products'])
        ->latest()
        ->paginate(15);

    return response()->json([
        'success' => true,
        'data'    => DiscoverShopResource::collection($shops),
        'meta'    => [
            'current_page' => $shops->currentPage(),
            'last_page'    => $shops->lastPage(),
            'total'        => $shops->total(),
        ],
    ], 200);
}
}