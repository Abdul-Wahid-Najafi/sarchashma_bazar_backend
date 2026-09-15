<?php

namespace App\Http\Controllers\Api\Shop;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Shop\UpdateShopSocialsRequest;
use App\Actions\Shop\UpdateShopSocialsAction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

use App\Models\Shop;
use App\Http\Resources\Api\Shop\ShopResource;

use App\Http\Requests\Api\Shop\StoreShopRequest;
use App\Actions\Shop\StoreShopAction;

    use App\Http\Requests\Api\Shop\UpdateShopRequest;
use App\Actions\Shop\UpdateShopAction;
// use App\Http\Resources\Api\Shop\ShopResource;

class ShopSocialController extends Controller
{
    public function update(UpdateShopSocialsRequest $request, UpdateShopSocialsAction $action): JsonResponse
    {
        
        $action->execute($request->user(), $request->validated('socials'));

        $updatedSocials = $request->user()->shop->socialAccounts;

        return response()->json([
            'success' => true,
            'message' => 'Shop social accounts updated successfully.',
            'data' => $updatedSocials
        ], 200);
    }


    public function delete(){
        
        $user = auth()->user();
        $shop = $user->shop;

        if (!$shop) {
            return response()->json([
                'success' => false,
                'message' => 'Shop not found for this seller.'
            ], 404);
        }

        $shop->socialAccounts()->delete();

        return response()->json([
            'success' => true,
            'message' => 'All social accounts deleted successfully.'
        ], 200);
    }


    /**
 * دریافت اطلاعات کامل یک دکان بر اساس Slug
 */
    public function show($slug): JsonResponse
    {
        // واکشی دکان به همراه لود کردن همزمان روابط (Eager Loading) برای سرعت فوق‌العاده بالاتر دیتابیس
        $shop = Shop::with(['user', 'province', 'socialAccounts'])
            ->where('slug', $slug)
            ->first();

        if (!$shop) {
            return response()->json([
                'success' => false,
                'message' => 'Shop not found.'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => new ShopResource($shop) // تبدیل دیتا توسط ریسورس ساخته شده
        ], 200);
    }


    public function myShop(Request $request): JsonResponse
    {
        // ۱. چک کردن اینکه آیا کاربر اصلاً فروشنده است یا خیر
        if (!$request->user()->is_seller) {
            return response()->json([
                'success' => false,
                'message' => 'You do not have a shop. Please register as a seller.'
            ], 403);
        }

        // ۲. پیدا کردن شاپ کاربر به همراه روابط آن (Eager Loading)
        $shop = $request->user()->shop()->with(['user', 'province', 'socialAccounts'])->first();

        if (!$shop) {
            return response()->json([
                'success' => false,
                'message' => 'Shop not found for this user.'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => new ShopResource($shop) // استفاده مجدد از ریسورس هوشمند قبلی
        ], 200);
    }


    public function store(StoreShopRequest $request,StoreShopAction $action): JsonResponse {
        info("create new Shop", $request->all());
        
        $shop = $action->execute(
            $request->user(),
            $request->validated()
        );

        $shop->load([
            'user',
            'province.country',
            'socialAccounts',
            'services',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Your shop has been created successfully.',
            'data' => new ShopResource($shop),
        ], 201);
    }




/**
 * ویرایش اطلاعات دکان اختصاصی فروشنده لاگین شده
 */
    public function updateMyShop(UpdateShopRequest $request, UpdateShopAction $action): JsonResponse
    {   
            info('All Request Data:', $request->all());

    // 🌟 فقط دیتایی که تایید هویت (Validate) شده را در لاگ ذخیره کن
    info('Validated Data:', $request->validated());
        $shop = $request->user()->shop;

       

        if (!$shop) {
            return response()->json([
                'success' => false,
                'message' => 'Shop not found.'
            ], 404);
        }

        // اجرای اکشن ویرایش دکان
        $action->execute($shop, $request->validated());

        // لود کردن مجدد تمام روابط تودرتو برای بازگرداندن دیتای جدید به فلاتر
        $shop->load(['user', 'province.country', 'socialAccounts', 'services']);

        return response()->json([
            'success' => true,
            'message' => 'Your shop has been updated successfully.',
            'data'    => new ShopResource($shop)
        ], 200);
    }


    /**
 * 🆕 آمار لحظه‌ای شاپ خودم (بدون کش، همیشه تازه)
 */
public function myShopStats(Request $request): JsonResponse
{
    $shop = $request->user()->shop;

    if (!$shop) {
        return response()->json([
            'success' => false,
            'message' => 'Shop not found.',
        ], 404);
    }

    return response()->json([
        'success' => true,
        'data' => [
            'followers_count'   => $shop->followers()->count(),
            'products_count'    => $shop->products()->count(),
            'is_followed_by_me' => false, // شاپ خودشه، فالو کردن معنی نداره
        ],
    ], 200);
}

    public function shopStats(string $slug, Request $request): JsonResponse
    {
        $shop = Shop::where('slug', $slug)->first();

        if (!$shop) {
            return response()->json([
                'success' => false,
                'message' => 'Shop not found.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'followers_count'   => $shop->followers()->count(),
                'products_count'    => $shop->products()->count(),
                'is_followed_by_me' => $request->user()
                    ? $shop->followers()->where('user_id', $request->user()->id)->exists()
                    : false,
            ],
        ], 200);
    }

    /**
 * فالو کردن یک شاپ
 */
    public function follow(string $slug, Request $request): JsonResponse
    {
        $shop = Shop::where('slug', $slug)->first();

        if (!$shop) {
            return response()->json([
                'success' => false,
                'message' => 'Shop not found.',
            ], 404);
        }

        if ($shop->user_id === $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'You cannot follow your own shop.',
            ], 422);
        }

        $shop->followers()->syncWithoutDetaching([$request->user()->id]);

        return response()->json([
            'success' => true,
            'message' => 'Followed successfully.',
        ], 200);
    }

    /**
     * آنفالو کردن یک شاپ
     */
    public function unfollow(string $slug, Request $request): JsonResponse
    {
        $shop = Shop::where('slug', $slug)->first();

        if (!$shop) {
            return response()->json([
                'success' => false,
                'message' => 'Shop not found.',
            ], 404);
        }

        $shop->followers()->detach($request->user()->id);

        return response()->json([
            'success' => true,
            'message' => 'Unfollowed successfully.',
        ], 200);
    }

}
