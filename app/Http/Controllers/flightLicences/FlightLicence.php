<?php

namespace App\Http\Controllers\flightLicences;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FlightLicenceNumber as FN;

class FlightLicence extends Controller
{
    public function createData($data){
        return FN::create($data);
    }
}
