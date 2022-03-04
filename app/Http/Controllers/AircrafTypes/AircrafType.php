<?php

namespace App\Http\Controllers\AircrafTypes;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AirCrafType as ACType;

class AircrafType extends Controller
{
    protected function store($data){
        return ACType::create($data);
    }

    protected function getType($id){
        $type = ACType::join('air_craf_engine_types', 'air_craf_engine_types.id', '=', 'air_craf_types.engine_type_id')
                        ->where('air_craf_types.id', $id)
                        ->get(['air_craf_types.id',
                               'air_craf_types.type as name',
                               'air_craf_engine_types.name as type']);
        return $type;
    }
    
    protected function getAll(){
        $type = ACType::join('air_craf_engine_types', 'air_craf_engine_types.id', '=', 'air_craf_types.engine_type_id')
                        ->get(['air_craf_types.id',
                               'air_craf_types.type as name',
                               'air_craf_engine_types.name as type']);
        return $type;
    }

    public function storeData(Request $req){
        $data = [
            'type' => $req->name,
            'desc' => $req->desc,
            'engine_type_id' => $req->eng_type_id
        ];
        $id = $this->store($data);
        $type = $this->getType($id['id'])[0];

        return response()->json(compact('type'), 200);
    }

    public function getDataById($id){
        $type = ACType::join('air_craf_engine_types', 'air_craf_engine_types.id', '=', 'air_craf_types.engine_type_id')
                        ->where('air_craf_types.id', $id)
                        ->get(['air_craf_types.id',
                            'air_craf_types.type as name',
                            'air_craf_types.desc',
                            'air_craf_engine_types.id as eng_type_id'])->first();
        return response()->json(compact('type'));
    }

    public function showAll(){
        $types = $this->getAll();
        return response()->json(compact('types'), 200);
    }

    public function deleteData($id){
        $type = ACType::where('air_craf_types.id', $id)
                        ->delete();
        return response()->json(compact('type'));
    }

    public function update(Request $req){
        $data = [
            'type' => $req->name,
            'engine_type_id' => $req->eng_type_id,
            'desc' => $req->desc
        ];
        $type = ACType::where('id', $req->id)
                 ->update($data);
        if ($type) {
            $type = $this->getType($req->id)[0];
        }
        
        return response()->json(compact('type'));
    }
}
