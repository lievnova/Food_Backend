<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DuplicateDependency extends Model
{
    //
    use HasFactory;
    protected $table='duplicate_dependency';
    protected $primaryKey='duplicate_dependency_id';
    protected $fillable=[
        'country_id',
    ];
    public function country(){
        return $this->belongsTo(Countrys::class,'country_id','country_id');
        
    }
}
