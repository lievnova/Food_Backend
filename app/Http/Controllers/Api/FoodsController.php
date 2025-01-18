<?php

namespace App\Http\Controllers\Api;


use App\Models\Foods;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\CombinedIngredient;

class FoodsController extends Controller
{
    public function index()
    {
        // Eager load relationships: combined_ingredients (with ingredient), country, and typefood
        $foods = Foods::with(['combined_ingredients.ingredient', 'country', 'typefood'])->get();

        // Map response data
        $response = $foods->map(function ($food) {
            return [
                'food_id' => $food->food_id,
                'name_food' => $food->name_food,
                'ingredients' => $food->combined_ingredients->map(function ($combinedIngredient) {
                    // Ensure ingredient relationship is loaded to get ingredient_name
                    return [
                        'ingredient_id' => $combinedIngredient->ingredient->ingredient_id,
                        'ingredient_name' => $combinedIngredient->ingredient->ingredient_name,
                    ];
                }),

                'country' => $food->country ? [
                    'country_id' => $food->country->country_id,
                    'country_name' => $food->country->country_name,
                    'region' => $food->country->region ? [
                        'region_id' => $food->country->region_id,
                        'region_name' => $food->country->region->region_name,
                        'continent' => $food->country->region->continent ? [
                            'continent_id' => $food->country->region->continent->continent_id,
                            'continent_name' => $food->country->region->continent->continent_name,
                        ] : null,
                    ] : null,
                    'duplicate_dependency_id' => $food->country->duplicate_dependency_id,
                ] : null,


                'type_foods' => $food->typefood ? [
                    'type_id' => $food->typefood->type_id,
                    'type_name' => $food->typefood->type_name,
                ] : null,

                'description' => $food->description,
                'how' => $food->how_make,
                'note' => $food->note,
                'food_image'=> $food->image,
            ];
        });

        return response()->json($response);
    }
    public function input($id)
    {
        try {
            // Find the food item by ID
            $food = Foods::find($id);

            // Check if the food item exists
            if (!$food) {
                return response()->json(['message' => 'Food not found'], 404);
            }

            // Transform the food data
            $response = [
                'food_id' => $food->food_id,
                'name_food' => $food->name_food,
                'ingredients' => $food->combined_ingredients->map(function ($combinedIngredient) {
                    return [
                        'ingredient_id' => $combinedIngredient->ingredient->ingredient_id,
                        'ingredient_name' => $combinedIngredient->ingredient->ingredient_name,
                    ];
                }),
                'country' => $food->country ? [
                    'country_id' => $food->country->country_id,
                    'country_name' => $food->country->country_name,
                    'region_id' => $food->country->region_id,
                    'duplicate_dependency_id' => $food->country->duplicate_dependency_id,
                ] : null,
                'type_foods' => $food->typefood ? [
                    'type_id' => $food->typefood->type_id,
                    'type_name' => $food->typefood->type_name,
                ] : null,
                'description' => $food->description,
                'how' => $food->how_make,
                'note' => $food->note,
                'food_image' => $food->image,
            ];

            // Return the transformed data
            return response()->json($response, 200);
        } catch (\Exception $e) {
            // Handle errors
            return response()->json([
                'message' => 'Error retrieving food data',
                'error' => $e->getMessage(),
            ], 500);
        }
    }


    public function show(Request $request)
    {
        // Retrieve search parameters from the request
        $foodId = $request->input('food_id');
        $nameFood = $request->input('name_food');
        $countryName = $request->input('country');
        $countryId= $request->input('country_id');
        $typeFoodId = $request->input('type_id');
        $ingredientName = $request->input('ingredient');
        $regionID=$request->input('region_id');
        $continentID=$request->input('continent_id');

        // Build the query with conditional filters
        $query = Foods::with(['combined_ingredients.ingredient', 'country.region.continent', 'typefood'])
                    ->when($foodId, function ($query) use ($foodId) {
                        return $query->where('food_id', $foodId);
                    })
                    ->when($nameFood, function ($query) use ($nameFood) {
                        return $query->where('name_food', 'like', '%' . $nameFood . '%');
                    })
                    
                    ->when($typeFoodId, function ($query) use ($typeFoodId) {
                        return $query->where('type_id', $typeFoodId);
                        
                    })
                    ->when($regionID, function ($query) use ($regionID) {
                        return $query->whereHas('country.region', function ($query) use ($regionID) {
                            $query->where('region_id', $regionID);
                        });
                    })
                    ->when($continentID, function ($query) use ($continentID) {
                        return $query->whereHas('country.region.continent', function ($query) use ($continentID) {
                            $query->where('continent_id', $continentID);
                        });
                    })
                    ->when($countryId, function ($query) use ($countryId){
                        return $query->where('country_id', $countryId );
                    })
                    ->when($ingredientName, function ($query) use ($ingredientName) {
                        return $query->whereHas('combined_ingredients.ingredient', function ($query) use ($ingredientName) {
                            $query->where('ingredient_name', 'like', '%' . $ingredientName . '%');
                        });
                    });

        // Fetch the results (if any)
        $foods = $query->get();

        // If no foods are found, return a 404 error
        if ($foods->isEmpty()) {
            return response()->json(['message' => 'No foods found matching the given criteria.'], 404);
        }

        // Map response data
        $response = $foods->map(function ($food) {
            return [
                'food_id' => $food->food_id,
                'name_food' => $food->name_food,
                'ingredients' => $food->combined_ingredients->map(function ($combinedIngredient) {
                    return [
                        'ingredient_id' => $combinedIngredient->ingredient->ingredient_id,
                        'ingredient_name' => $combinedIngredient->ingredient->ingredient_name,
                    ];
                }),
                'country' => $food->country ? [
                    'country_id' => $food->country->country_id,
                    'country_name' => $food->country->country_name,
                    'region' => $food->country->region ? [
                        'region_id' => $food->country->region_id,
                        'region_name' => $food->country->region->region_name,
                        'continent' => $food->country->region->continent ? [
                            'continent_id' => $food->country->region->continent->continent_id,
                            'continent_name' => $food->country->region->continent->continent_name,
                        ] : null,
                    ] : null,
                    'duplicate_dependency_id' => $food->country->duplicate_dependency_id,
                ] : null,
                'type_foods' => $food->typefood ? [
                    'type_id' => $food->typefood->type_id,
                    'type_name' => $food->typefood->type_name,
                ] : null,
                'description' => $food->description,
                'how' => $food->how_make,
                'note' => $food->note,
                'food_image' => $food->image,
            ];
        });

        return response()->json($response);
    }
    public function store(Request $request)
    {
        // Validate the input data
        $data = $request->validate([
            'name_food' => 'required|string|max:255',
            'type_id' => 'required|exists:food_type,type_id', // Validates type_id exists in the food_type table
            'country_id' => 'required|exists:country,country_id', // Validates country_id exists in the country table
            'description' => 'nullable|string',
            'how_make' => 'nullable|string',
            'note' => 'nullable|string',
            'image' => 'nullable|string|max:1000',
        ]);

        // Handle image upload if provided
        $imagePath = $request->hasFile('image') 
            ? $request->file('image')->store('images/foods', 'public') 
            : null;

        // Insert the food item into the `foods` table
        $food = Foods::create([
            'name_food' => $data['name_food'],
            'type_id' => $data['type_id'],
            'country_id' => $data['country_id'],
            'description' => $data['description'] ?? null,
            'how_make' => $data['how_make'] ?? null,
            'note' => $data['note'] ?? null,
            'image' => $imagePath,
        ]);
        

        // Retrieve the newly created food item
        $food = DB::table('foods')->where('id', $food)->first();

        return response()->json([
            'success' => true,
            'message' => 'Food created successfully.',
            'food' => $food,
        ], 201);
    }
    public function destroy(Request $request)
    {
        $id=$request->input('food_id');
        $name=$request->input('name_food');

        if($id){
            $food = Foods::find($id);
            if($food){
                $food->delete();
                return response()->json(['message' => 'Food deleted successfully by ID']);
            }
            else{
                return response()->json(['message'=> 'Food not found by ID']);
            }
        }

        if($name){
            $food = Foods::where('name_food', $name)->first();
            if($food){
                $food->delete();
                return response()->json(['message'=> 'Food deleted successfully by name']);
            }
            else{
                return response()->json(['message'=> 'Food not found by name']);
            }
        }
        return response()->json(['message'=> 'Invalid input. Provide either ID or Name.'],400);
    }
    public function update(Request $request, $id) // Accept the ID of the food to update
    {
        // Validate the request data
        $validated = $request->validate([
            'name_food' => 'sometimes|required|string|max:255',
            'type_id' => 'sometimes|required|exists:food_type,type_id',
            'country_id' => 'sometimes|required|exists:country,country_id',
            'description' => 'nullable|string',
            'how_make' => 'nullable|string',
            'note' => 'nullable|string',
            'image' => 'nullable|string|max:1000',
        ]);
        

        // Find the food record by ID
        $food = Foods::find($id);

        // Check if the record exists
        if (!$food) {
            return response()->json([
                'success' => false,
                'message' => 'Food not found.',
            ], 404);
        }

        // Update the food record
        $food->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Food updated successfully.',
            'data' => $food,
        ], 200);
    }
}
