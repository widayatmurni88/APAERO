<?php

namespace App\Http\Controllers\pilotPassports;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PilotPassport as mpassport;

class PilotPassport extends Controller{
    
    public function createData($data){
        return mpassport::create($data);
    }
}
