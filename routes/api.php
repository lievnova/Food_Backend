<?php

use App\Http\Controllers\Api\FoodsController;
use App\Http\Controllers\Api\TypeFoodController;
use App\Http\Controllers\Api\IngredientController;
use App\Http\Controllers\Api\RegionController;
use App\Http\Controllers\Api\CountryController;
use App\Http\Controllers\Api\DuplicateDependencyController;
use App\Http\Controllers\Api\DependencyController;
use App\Http\Controllers\Api\CombinedIngredientController;
use App\Models\Continent;
use App\Http\Controllers\Api\ContinentController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpKernel\DataCollector\RouterDataCollector;

Route::prefix('/foods')->group(function () {
    Route::get("/typefoods", [TypeFoodController::class,"index"])->name("typefoods.index");
    Route::get("/", [FoodsController::class,"index"])->name("foods.index");
    Route::get('/{id}', [FoodsController::class,"input"])->name("foods.input");
    Route::get("/", [FoodsController::class,"show"])->name("foods.show");
    Route::post("/", [FoodsController::class,"store"])->name("foods.store");
    Route::delete("/", [FoodsController::class,"destroy"])->name("foods.destroy");
    Route::put("/{id}", [FoodsController::class,"update"])->name("foods.update");
});


Route::prefix("/ingredients")->group(function () {
    Route::get("/", [IngredientController::class,"index"])->name("name");
    Route::post("/", [IngredientController::class,"store"])->name("store");
    Route::get("/{input}", [IngredientController::class,"show"])->name("show");
    Route::put("/{id}", [IngredientController::class,"update"])->name("update");
    Route::delete("/", [IngredientController::class,"destroy"])->name("destroy");
    
});
Route::prefix("combination_ingredients")->group(function () {
    Route::get("/", [CombinedIngredientController::class,"index"])->name("name");
    Route::post("/", [CombinedIngredientController::class,"store"])->name("store");
    Route::put("/{id}", [CombinedIngredientController::class,"update"])->name("update");
    Route::delete("/", [CombinedIngredientController::class,"destroy"])->name("destroy");
});

Route::prefix("/countrys")->group(function () {
    Route::get("/", [CountryController::class,"index"])->name("index");
    Route::get("/regions", [RegionController::class,"index"])->name("index");
    Route::get("/dependencys",[DependencyController::class,"index"])->name("index");
    Route::get("/duplicate_dependencys", [DuplicateDependencyController::class,"index"])->name("index");
    Route::get('/continent', [ContinentController::class,"index"])->name("index");

});

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
