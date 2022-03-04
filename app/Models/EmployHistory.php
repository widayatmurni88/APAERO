<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployHistory extends Model
{
    // use HasFactory;
    protected $table = 'pilot_employ_histories';
    protected $fillable = ['name', 'date_start', 'date_end', 'biodata_id'];
    protected $hidden = ['created_at', 'updated_at'];
}
