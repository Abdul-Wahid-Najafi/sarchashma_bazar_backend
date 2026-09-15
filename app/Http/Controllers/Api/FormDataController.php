<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\SocialIcon;
use Illuminate\Http\JsonResponse;

class FormDataController extends Controller
{
    public function __invoke(): JsonResponse
    {
        $countries = Country::with(['provinces'])->get();

        // 🛑 واکشی مستقیم آیکون‌های داینامیک از جدول جدید
        $socialIcons = SocialIcon::select('id', 'name', 'icon_url')->get();

        return response()->json([
            'success' => true,
            'data' => [
                'countries' => $countries,
                'allowed_socials' => $socialIcons // فرستادن اطلاعات کامل شامل لینک عکس آیکون
            ]
        ], 200);
    }
}
