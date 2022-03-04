<?php

namespace App\Http\Controllers\EngineTypes;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AirCrafEngineType as EngType;

class EngineType extends Controller
{
    protected function getAll(){
        return EngType::get(['id', 'name', 'desc']);
    }

    public function showAll(){
        $types = $this->getAll();
        return response()->json(compact('types'));
    }

    public function storeData(Request $req){
        $etype = [
            'name' => $req->name,
            'desc' => $req->desc
        ];
        $etype = EngType::create($etype);
        return response()->json(compact('etype'));
    }
}
