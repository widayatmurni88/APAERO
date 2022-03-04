<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TypeOfOperation extends Model
{
    // use HasFactory;
    protected $table = 'type_of_operations';
    protected $fillable = ['name', 'desc'];
    protected $hidden = ['created_at', 'upadated_at'];
}
