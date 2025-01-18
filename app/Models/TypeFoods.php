<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;

class TypeFoods extends Model
{
    //
    use HasFactory;
    protected $table='food_type';
    protected $primaryKey='type_id';
    protected $fillable=[
        'type_name',
    ];

}
