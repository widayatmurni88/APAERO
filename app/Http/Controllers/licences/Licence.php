<?php

namespace App\Http\Controllers\licences;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Licence as Lc;

class Licence extends Controller{

    protected function saveData($data){
        return Lc::create($data);
    }

    protected function getAll(){
        return Lc::get(['id', 'name', 'num_period', 'type_period']);
    }

    protected function getById($id){
        return Lc::where('id', $id)->get(['id', 'name', 'num_period', 'type_period', 'desc'])->first();
    }

    public function storeData(Request $req){
        $data = [
            'name' => $req->name,
            'num_period' => $req->num_period,
            'type_period' => $req->type_period,
            'desc' => $req->desc,
        ];

        $licence = $this->saveData($data);

        return response()->json(compact('licence'), 200);
    }

    public function getAllData(){
        $licences=$this->getAll();
        return response()->json(compact('licences'));
    }

    public function deleteById($id){
        $licence = Lc::where('id', $id)->delete();
        return response()->json(compact('licence'));
    }

    public function getDataById($id){
        $licence = $this->getById($id);
        return response()->json(compact('licence'));
    }

    public function updateData(Request $req){
        $data = [
            'name' => $req->name,
            'num_period' => $req->num_period,
            'type_period' => $req->type_period,
            'desc' => $req->desc,
        ];

        $licence = Lc::where('id', $req->id)->update($data);
        return response()->json(compact('licence'));
    }

    public function searchByName($name=null){
        if (trim($name) != null) {
            $licences = Lc::where('name', 'like', "%{$name}%")
                        ->get(['id', 'name', 'num_period', 'type_period']);
            return response()->json(compact('licences'));
        } else {
            $licences=$this->getAll();
            return response()->json(compact('licences'));
        }
    }
}
