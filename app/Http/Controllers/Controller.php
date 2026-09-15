<?php

namespace App\Http\Controllers;
use App\Models\Country;

abstract class Controller
{
    public static function countries()
    {
        $countries = Country::all();
        return response()->json([
            'success' => true,
            'data' => $countries
        ]);
    }
}
