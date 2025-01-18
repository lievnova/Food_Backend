<?php

namespace App\Http\Controllers\Api;

use App\Models\Ingredients;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class IngredientController extends Controller
{
    //
    public function index(){
        $ingredients = Ingredients::all();
        $response= $ingredients->map(function($ingredient){
            return[
                "ingredient_id"=> $ingredient->ingredient_id,
                "ingredient_name"=> $ingredient->ingredient_name,
            ];
        });
        return response()->json($response);
    }
    public function store(Request $request)
    {
        // Validate the input
        $validated = $request->validate([
            'ingredient_name' => 'required|string|max:255',
        ]);

        // Insert and retrieve the correct primary key
        $ingredientId = DB::table('ingredient')->insertGetId([
            'ingredient_name' => $validated['ingredient_name'],
        ], 'ingredient_id'); // Specify the primary key column

        // Return a success response
        return response()->json([
            'success' => true,
            'message' => 'Ingredient added successfully!',
            'ingredient' => [
                'ingredient_id' => $ingredientId,
                'ingredient_name' => $validated['ingredient_name'],
            ],
        ], 201);
    }
    public function show($input)
    {
        // Check if the input is numeric (for ID lookup) or a string (for name lookup)
        if (is_numeric($input)) {
            $ingredient = Ingredients::find($input); // Lookup by ID
        } else {
            $ingredient = Ingredients::where('ingredient_name', $input)->first(); // Lookup by name
        }

        // Check if the ingredient exists
        if (!$ingredient) {
            return response()->json([
                'success' => false,
                'message' => 'Ingredient not found'
            ], 404);
        }

        // Format the response
        $response = [
            "ingredient_id" => $ingredient->ingredient_id,
            "ingredient_name" => $ingredient->ingredient_name,
        ];
        return response()->json($response);
    }

    public function update(Request $request, $id){
        $validated = $request->validate([
            'ingredient_name' =>'required|string|max:255',
        ]);
        $ingredient = Ingredients::find($id);
        if(!$ingredient){
            return response()->json([
                'success'=> false,
                'message'=> 'Ingredient not found'
            ],404);
        }
        $ingredient->ingredient_name = $validated['ingredient_name'];
        $ingredient->save();

        return response()->json([
            'success'=> true,
            'message'=> 'Ingredient updated successfully!',
            'ingredient'=>[
                'ingredient_id'=> $ingredient->ingredient_id,
                'ingredient_name'=>$ingredient->ingredient_name,
            ],
        ],200);
    }
    public function destroy(Request $request)
    {
        $id = $request->input('ingredient_id');
        $name = $request->input('ingredient_name');

        // Handle deletion by ID
        if ($id) {
            $ingredient = Ingredients::find($id);
            if ($ingredient) {
                $ingredient->delete();
                return response()->json(['message' => 'Ingredient deleted successfully by ID']);
            } else {
                return response()->json(['error' => 'Ingredient not found by ID'], 404);
            }
        }

        // Handle deletion by Name
        if ($name) {
            $ingredient = Ingredients::where('ingredient_name', $name)->first();
            if ($ingredient) {
                $ingredient->delete();
                return response()->json(['message' => 'Ingredient deleted successfully by Name']);
            } else {
                return response()->json(['error' => 'Ingredient not found by Name'], 404);
            }
        }

        // If neither ID nor Name is provided
        return response()->json(['error' => 'Invalid input. Provide either ID or Name.'], 400);
    }

}
