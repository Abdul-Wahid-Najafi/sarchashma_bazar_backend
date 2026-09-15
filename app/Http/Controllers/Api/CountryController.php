<?php

namespace App\Http\Controllers\Api;
use App\Models\Country;


 class CountryController
{
    public function countries()
    {
        $countries = Country::all();
        return response()->json([
            'success' => true,
            'data' => $countries,
            'provinces'=> $countries->map(function ($country) {
                return [
                    'country_id' => $country->id,
                    'country_name' => $country->name,
                    'provinces' => $country->provinces->map(function ($province) {
                        return [
                            'province_id' => $province->id,
                            'province_name' => $province->name,
                        ];
                    }),
                ];
            }),
        ]);
    }
}
