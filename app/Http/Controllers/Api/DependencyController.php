<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Dependency;
use Illuminate\Http\Request;

class DependencyController extends Controller
{
    //
    public function index(Request $request){
        $dependencys = Dependency::all();
        $response=$dependencys->map(function($dependency){
            return[
                "dependency_id"=> $dependency->dependency_id,
                "dependency_name"=> $dependency->dependency_name,
            ];
        });
        return response()->json($response);
    }
}
