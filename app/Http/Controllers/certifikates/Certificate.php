<?php

namespace App\Http\Controllers\certifikates;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Certificate as Cert;

class Certificate extends Controller
{
    protected function saveData($data){
        return Cert::create($data);
    }

    protected function getData($id){
        return Cert::where('id', $id)
                    ->get(['id', 'name', 'valid_period', 'period_type', 'desc']);
    }

    protected function getAll(){
        return Cert::get(['id', 'name', 'valid_period', 'period_type']);
    }

    public function storeData(Request $req){
        $data = [
            'name' => $req->name,
            'valid_period' => $req->valid_period,
            'period_type' => $req->period_type,
            'desc' => $req->desc,
        ];
        $cert = $this->saveData($data);
        return response()->json(compact('cert'));
    }

    public function getAllData(){
        $certs = $this->getAll();
        return response()->json(compact('certs'));
    }

    public function deleteById($id){
        $cert = Cert::where('id', $id)->delete();
        return response()->json(compact('cert'));
    }

    public function update(Request $req){
        $data = [
            'name' => $req->name,
            'valid_period' => $req->valid_period,
            'period_type' => $req->period_type,
            'desc' => $req->desc,
        ];

        $cert = Cert::where('id', $req->id)->update($data);
        return response()->json(compact('data'));
    }

    public function getDataById($id){
        $cert = $this->getData($id)[0];
        return response()->json(compact('cert'));
    }

    public function getIdName(){
        $cert = Cert::get(['id', 'name', 'period_type', 'valid_period']);
        return response()->json(compact('cert'));
    }

    public function searchByName($name=null){
        if (trim($name) != null) {
            $certs = Cert::where('name', 'like', "%{$name}%")
                        ->get(['id', 'name', 'valid_period', 'period_type']);
            return response()->json(compact('certs'));
        } else {
            $certs = $this->getAll();
            return response()->json(compact('certs'));
        }
    }
}
