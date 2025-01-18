<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CombinedIngredient extends Model
{
    use HasFactory;

    protected $table = 'combined_ingredients';
    protected $primaryKey = 'food_id';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = false;

    protected $fillable = ['food_id', 'ingredient_id']; // Allow mass assignment

    public function foods()
    {
        return $this->belongsTo(Foods::class, 'food_id','food_id');
    }

    public function ingredient()
    {
        return $this->belongsTo(Ingredients::class, 'ingredient_id','ingredient_id');
    }
    public function syncIngredient($ingredientId)
    {
        // Check if a record already exists with the given ingredient_id
        $existing = CombinedIngredient::where('food_id', $this->food_id)
            ->where('ingredient_id', $ingredientId)
            ->first();

        if (!$existing) {
            $this->ingredient_id = $ingredientId;
            $this->save();
        }
    }


}
