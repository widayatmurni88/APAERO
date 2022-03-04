<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Licence extends Model
{
    use HasFactory;
    protected $table = 'licence_types';
    protected $fillable = ['name', 'num_period', 'type_period', 'desc'];
    protected $hidden = ['created_at', 'updated_at'];
}
