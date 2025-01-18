<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ingredients extends Model
{
    use HasFactory;

    protected $table = 'ingredient';
    protected $primaryKey = 'ingredient_id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'ingredient_name',
    ];

    public function foods()
    {
        return $this->belongsToMany(Foods::class, 'combined_ingredients', 'ingredient_id', 'food_id');
    }
}
