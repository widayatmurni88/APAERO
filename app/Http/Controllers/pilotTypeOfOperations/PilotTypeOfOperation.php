<?php

namespace App\Http\Controllers\pilotTypeOfOperations;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PilotTypeOfOperation as ptop;

class PilotTypeOfOperation extends Controller
{
    public function storeData(Request $req){
        $data = [
            'hours_operation' => $req->hours,
            'biodata_id' => $req->bio_id,
            'type_of_operation_id' => $req->top_id
        ];
        $top = ptop::create($data);
        return response()->json(compact('top'));
    }

    public function getTopByPeopleId($peopleId){
        return ptop::join('type_of_operations', 'type_of_operations.id', '=', 'pilot_of_operations.type_of_operation_id')
                    ->where('biodata_id', $peopleId)
                    ->selectRaw('type_of_operations.id, type_of_operations.name, SUM(pilot_of_operations.hours_operation) as hours_operation')
                    ->groupBy(['type_of_operations.id', 'type_of_operations.name'])
                    ->get();
    }

    public function getTopById($peopleId){
        return ptop::selectRaw('SUM(hours_operation) as hours_operation')
                ->where('biodata_id', $peopleId)
                ->get()->first();
    }

    public function getTopData($peopleId){
        $topdt = $this->getTopByPeopleId($peopleId);
        return $topdt;
    }

    public function getTops($bio_id){
        $tops = $this->getTopByPeopleId($bio_id);
        return response()->json(compact('tops'));
    }

    public function getTotalHoursByPeopleId($bio_id){
        $total = $this->getTopById($bio_id);
        return $total;
    }
}
