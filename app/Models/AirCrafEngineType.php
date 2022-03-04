<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AirCrafEngineType extends Model
{
    protected $fillable = ['name', 'desc'];
    protected $hidden=['created_at', 'updated_at'];
}
