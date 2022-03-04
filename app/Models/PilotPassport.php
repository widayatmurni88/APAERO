<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PilotPassport extends Model
{
    // use HasFactory;
    protected $table = 'pilot_passports';
    protected $fillable = ['number', 'valid_start', 'valid_end', 'active', 'biodata_id'];
    protected $hidden = ['created_at', 'updated_at'];
}
