<?php

namespace App\Http\Controllers\Api;

use App\Models\TypeFoods;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TypeFoodController extends Controller
{
    public function index(){
        $typefoods = TypeFoods::all();
        $response=$typefoods->map(function($typefood){
            return [
                "type_id"=> $typefood->type_id,
                "type_name"=> $typefood->type_name,
            ];
        });
        return response()->json($response);
    }
}
