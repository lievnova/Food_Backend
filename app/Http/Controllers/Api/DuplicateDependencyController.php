<?php

namespace App\Http\Controllers\Api;

use App\Models\DuplicateDependency;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DuplicateDependencyController extends Controller
{
    public function index(Request $request){
        $duplicatedependencys=DuplicateDependency::all();
        $response=$duplicatedependencys->map(function($duplicatedependency){
            return[
                "duplicate_dependencys_id"=> $duplicatedependency->duplicate_dependency_id,
                "country_id"=> $duplicatedependency->country_id,
            ];
        });
        return response()->json($response);

    }
}
