<?php

namespace Database\Seeders;

use App\Models\Province;
use Illuminate\Database\Seeder;

class ProvinceSeeder extends Seeder
{
    public function run(): void
    {
        $afGprovinces = [
            'Badakhshan',
            'Badghis',
            'Baghlan',
            'Balkh',
            'Bamyan',
            'Daykundi',
            'Farah',
            'Faryab',
            'Ghazni',
            'Ghor',
            'Helmand',
            'Herat',
            'Jowzjan',
            'Kabul',
            'Kandahar',
            'Kapisa',
            'Khost',
            'Kunar',
            'Kunduz',
            'Laghman',
            'Logar',
            'Nangarhar',
            'Nimruz',
            'Nuristan',
            'Paktia',
            'Paktika',
            'Panjshir',
            'Parwan',
            'Samangan',
            'Sar-e Pol',
            'Takhar',
            'Urozgan',
            'Wardak',
            'Zabul'
        ];

        foreach ($afGprovinces as $province) {
            Province::create([
                'country_id' => 1, 
                'name' => $province,
            ]);
        }
    }
}
