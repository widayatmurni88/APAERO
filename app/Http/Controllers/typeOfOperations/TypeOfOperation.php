<?php

namespace App\Http\Controllers\typeOfOperations;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TypeOfOperation as TOP;

class TypeOfOperation extends Controller
{
    public function storeData(Request $req){
        $data = [
            'name' => $req->name,
            'desc' => $req->desc,
        ];
        $top = TOP::create($data);
        return response()->json(compact('top'));
    }

    public function getData(){
        $tops = TOP::get();
        return response()->json(compact('tops'));
    }

    public function getById($id){
        $top = TOP::where('id', $id)->first();
        return response()->json(compact('top'));
    }

    public function updateData(Request $req){
        $data = [
            'name' => $req->name,
            'desc' => $req->desc,
        ];
        $top = TOP::where('id', $req->id)->update($data);
        return response()->json(compact('top'));
    }

    public function deleteById($id){
        $top = TOP::where('id', $id)->delete();
        return response()->json(compact('top'));
    }
}
