<?php

namespace App\Http\Controllers\Api;

use App\Models\Region;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RegionController extends Controller
{
    //
    public function index(){
        $regions = Region::with(['continent'])->get();
        $response = $regions->map(function($region){
            return [
                "region_id" => $region->region_id,
                "region_name" => $region->region_name,
                "continent" => [
                    "continent_id" => $region->continent->continent_id,
                    "continent_name" => $region->continent->continent_name,
                ],
            ];
        });        
        return response()->json($response);
    }
}
