<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Countrys extends Model
{
    use HasFactory;

    protected $table='country';
    protected $primaryKey= 'country_id';

    protected $fillable=[
        'country_name',
        'region_id',
        'duplicate_dependency_id',
    ];

    public function region(){
        return $this->belongsTo(Region::class,'region_id','region_id');
    }

    public function duplicatedependency(){
        return $this->belongsTo(DuplicateDependency::class,'duplicate_dependency_id','duplicate_dependency_id');
    }
    
}
