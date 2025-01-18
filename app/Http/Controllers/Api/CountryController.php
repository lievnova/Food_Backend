<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Countrys;
use Illuminate\Http\Request;

class CountryController extends Controller
{
    public function index()
    {
        $countries = Countrys::all();
        
        $response = $countries->map(function ($country) {
            return [
                "country_id" => $country->country_id,
                "country_name" => $country->country_name,
                "region" => $country->region ? [
                    "region_id" => $country->region->region_id,
                    "region_name" => $country->region->region_name,
                    'continent' => [
                        "continent_id" => $country->region->continent->continent_id,
                        "continent_name" => $country->region->continent->continent_name,
                    
                    ]
                ] : null,
                "duplicate_dependency_id" => $country->duplicate_dependency_id,
            ];
        });

        return response()->json($response);
    }


}
