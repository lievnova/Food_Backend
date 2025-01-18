<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Region extends Model
{
    use HasFactory;
    protected $table='region';
    protected $primaryKey= 'region_id';
    protected $fillable = [
        'region_name',
        'continent_id',
    ];

    public function continent(){
        return $this->belongsTo(Continent::class, 'continent_id', 'continent_id');
    }
}
