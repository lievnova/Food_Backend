<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Continent;
use Illuminate\Http\Request;

class ContinentController extends Controller
{
    public function index(){
        $continents =Continent::all();
        $response=$continents->map(function($continent){
            return[
                'continent_id'=>$continent->continent_id,
                'continent_name'=>$continent->continent_name,
            ];
        });
        return response()->json($response);
    }
}
