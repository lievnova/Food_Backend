<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;

class Dependency extends Model
{
    use HasFactory;
    protected $table='dependency';
    protected $primaryKey= 'dependency_id';
    protected $fillable=[
        'dependency_name',
    ];
    
}
