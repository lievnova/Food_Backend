<?php

namespace App\Http\Controllers\Api;

use App\Models\CombinedIngredient;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CombinedIngredientController extends Controller
{
    public function index(Request $request)
    {
        // Eager load the ingredient relationship
        $ingredients = CombinedIngredient::with(['ingredient'])->get();

        // Map the data to the desired response format
        $response = $ingredients->map(function ($ingredient) {
            return [
                "food_id" => $ingredient->food_id,
                "ingredients" => $ingredient->ingredient ? [
                    "ingredient_id" => $ingredient->ingredient->ingredient_id,
                    "ingredient_name" => $ingredient->ingredient->ingredient_name,
                ] : null,
                "ingredient_id" => $ingredient->ingredient->ingredient_id,
                "ingredient_name" => $ingredient->ingredient->ingredient_name,
            ];
        });

        return response()->json($response);
    }
    public function store(Request $request)
    {
        $data = $request->validate([
            "food_id" => "required|exists:foods,food_id",
            "ingredient_id" => "required|array",
            "ingredient_id.*" => "exists:ingredient,ingredient_id",
        ]);

        // Loop through the ingredient_id array and insert each ingredient with the food_id
        foreach ($data['ingredient_id'] as $ingredient) {
            CombinedIngredient::create([
                "food_id" => $data["food_id"],
                "ingredient_id" => $ingredient,
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Combined ingredients added successfully.',
        ], 201);
    }

    public function destroy(Request $request){
        $food_id=$request->input('food_id');
        $ingredient_id=$request->input('ingredient_id');

        if($food_id){
            $data = CombinedIngredient::find($food_id);
            if($data){
                $data->delete();
                return response()->json(['message' => 'Food deleted successfully by food ID']);
            }
            else{
                return response()->json(['message'=> 'Food not found by food ID']);
            }
        }
        if($ingredient_id){
            $data= CombinedIngredient::find($ingredient_id);
            if($data){
                $data->delete();
                return response()->json(['message'=> 'Food deleted successfully by ingredient ID']);
            }
            else{
                return response()->json(['message'=> 'Food not found by ingredient ID']);
            }

        }
        return response()->json(['message'=> 'Invalid input. Provide either ID or Name.'],400);
    }
    public function update(Request $request, $id)
    {
        // Validate the request data
        $validated = $request->validate([
            'food_id' => 'sometimes|required|exists:foods,food_id',
            'ingredient_id' => 'required|array',
            'ingredient_id.*' => 'required|integer|exists:ingredient,ingredient_id', // Fix validation rule
        ]);

        try {
            // Find the CombinedIngredient record by ID
            $data = CombinedIngredient::find($id);

            if (!$data) {
                return response()->json([
                    'success' => false,
                    'message' => 'CombinedIngredient not found.',
                ], 404);
            }

            // Update food_id if provided
            if (isset($validated['food_id'])) {
                $data->food_id = $validated['food_id'];
                $data->save();
            }

            // Delete existing ingredient_id associations for this food_id
            CombinedIngredient::where('food_id', $data->food_id)->delete();

            // Add new ingredient_id associations
            $newIngredients = [];
            foreach ($validated['ingredient_id'] as $ingredientId) {
                $newIngredients[] = [
                    'food_id' => $data->food_id,
                    'ingredient_id' => $ingredientId,
                ];
            }
            CombinedIngredient::insert($newIngredients);

            // Return success response
            return response()->json([
                'success' => true,
                'message' => 'CombinedIngredient updated successfully.',
                'data' => CombinedIngredient::where('food_id', $data->food_id)->get(),
            ], 200);
        } catch (\Exception $e) {
            // Handle exceptions
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while updating the record.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }


}
