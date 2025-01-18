<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Foods extends Model
{
    use HasFactory;

    protected $table = 'foods';
    protected $primaryKey = 'food_id';
    public $timestamps = false;
    protected $fillable = [
        'name_food',
        'type_id',
        'country_id',
        'description',
        'how_make',
        'note',
        'image',
    ];

    
    public function typefood()
    {
        return $this->belongsTo(TypeFoods::class, 'type_id', 'type_id');
    }

    public function combined_ingredients()
    {
        return $this->hasMany(CombinedIngredient::class, 'food_id');
    }

    public function country()
    {
        return $this->belongsTo(Countrys::class, 'country_id', 'country_id');
    }
    public function continent(){
        return $this->belongsTo(Continent::class, 'continent_id', 'continent_id');
    }

}
