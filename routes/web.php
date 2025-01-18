<?php

use App\Http\Controllers\Api\FoodsController;

use Illuminate\Support\Facades\Route;

Route::get('/',[FoodsController::class,'index']);


Route::get('/', function () {
    return view('test');
});

Route::get('/show', function () {
    return view('show');
});

