<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FlightLicenceNumber extends Model
{
    protected $table = 'flight_licence_numbers';
    protected $fillable = ['number', 'valid_start', 'valid_end', 'active', 'biodata_id'];
    protected $hidden = ['created_at', 'update_at'];
}
