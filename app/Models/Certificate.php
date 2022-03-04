<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Certificate extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'valid_period', 'period_type', 'desc'];
    protected $hidden = ['created_at', 'updated_at'];
}
