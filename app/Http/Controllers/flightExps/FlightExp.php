<?php

namespace App\Http\Controllers\flightExps;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use App\Http\Controllers\biodatas\Biodata as Bio;
use App\Models\FlightExp as FE;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\pilotInCommands\PilotInCommand as PIC;
use App\Http\Controllers\pilotTypeOfOperations\PilotTypeOfOperation as PTOP;

class FlightExp extends Controller{
    
    protected function getPeoples(){
        $people = new Bio();
        return $people->getPeoplesWithFlightNum();
    }

    protected function getTotalFlightExpById($id){
        return FE::where('biodata_id', $id)
                ->sum('hours_flight');
    }

    protected function getPeopleWithFlightExpById($id){
        $people = new Bio();
        return $people->getPeopleByIdWithFlightNum($id);
    }

    protected function getGroupEngFromAircrafByPeopleId($id){
        return FE::join('air_craf_types', 'air_craf_types.id', '=', 'flight_experiences.air_craf_id')
                ->join('air_craf_engine_types', 'air_craf_engine_types.id', '=', 'air_craf_types.engine_type_id')
                ->where('flight_experiences.biodata_id', $id)
                ->selectRaw('air_craf_types.engine_type_id, air_craf_engine_types.name, SUM(flight_experiences.hours_flight) as hours_flight')
                ->groupBy(['air_craf_types.engine_type_id',
                'air_craf_engine_types.name'])
                ->get();
    }

    protected function getFexpGroupByAircraf($peopleId, $engTypeId){
        $fex = FE::join('air_craf_types', 'air_craf_types.id', '=', 'flight_experiences.air_craf_id')
                ->join('air_craf_engine_types', 'air_craf_engine_types.id', '=', 'air_craf_types.engine_type_id')
                ->where('flight_experiences.biodata_id', $peopleId)
                ->where('air_craf_types.engine_type_id', $engTypeId)
                ->selectRaw('air_craf_types.id, air_craf_types.type, SUM(flight_experiences.hours_flight) as hours_flight')
                ->groupBy(['air_craf_types.id',
                           'air_craf_types.type'])
                ->get();
        
        return $fex;
    }

    public function getAllPeopleExps(){
        $peoples=[];
        $peopls = $this->getPeoples();
        foreach ($peopls as $p) {
            $tmp = [
                'id' => $p->id,
                'name' => $p->name,
                'licence_number' => $p->licence_number,
                'flight_exp' => $this->getTotalFlightExpById($p->id),
            ];
            $peoples = Arr::prepend($peoples, $tmp);
        }
        return response()->json(compact('peoples'));
    }

    public function getPeopleWithFExById($id){
        $people = $this->getPeopleWithFlightExpById($id);
        return response()->json(compact('people'));
    }

    public function storeData(Request $req){
        $data = [
            'biodata_id' => $req->bio_id,
            'air_craf_id' => $req->aircraf_id,
            'hours_flight' => $req->hours,
        ];
        $fexp = FE::create($data);
        return response()->json(compact('fexp'));
    }

    public function getFexGroupByTypeEng($id){
        $people = $this->getPeopleWithFlightExpById($id);
        $fex = [];
        // FLIGHT EXPERIENCES
        $groupEngType = $this->getGroupEngFromAircrafByPeopleId($id);
        foreach ($groupEngType as $get) {
            $ac = $this->getFexpGroupByAircraf($id, $get->engine_type_id);
            $tmp = [
                'id' => $get->engine_type_id,
                'name' => $get->name,
                'total_hours_flight' =>$get->hours_flight,
                'aircraf' => $ac,
            ];

            $fex = Arr::prepend($fex, $tmp);
        }
        
        // PILOT IN COMMAND
        $pic = new PIC();
        $picdt = $pic->getPicData($id);

        // PILOT TYPE OF OPERA
        $top = new PTOP();
        $tops = $top->getTopData($id);

        $people_fexp = [
            'id' => $people->id,
            'name' => $people->name,
            'licence_number' => $people->licence_number,
            'fexps' =>  $fex,
            'pics' => $picdt,
            'tops' => $tops,
        ];
        return response()->json(compact('people_fexp'));
    }
}
