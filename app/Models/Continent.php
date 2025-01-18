<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Continent extends Model
{
    use HasFactory;
    protected $table='continent';
    protected $primaryKey='continent_id';
    protected $fillable=[
        'continent_name'
    ];

}
