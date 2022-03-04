<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AirCrafType extends Model
{
    use HasFactory;
    protected $fillable = ['type', 'desc', 'engine_type_id'];
    protected $hidden = ['created_at', 'updated_at'];
}
