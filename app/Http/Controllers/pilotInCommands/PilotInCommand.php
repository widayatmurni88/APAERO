<?php

namespace App\Http\Controllers\pilotInCommands;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use App\Models\PilotInCommand as PIC;

class PilotInCommand extends Controller
{
    protected function getPicGroupByEngType($peopleId){
        return PIC::join('air_craf_types', 'air_craf_types.id', '=', 'pilot_in_commands.air_craft_id')
                ->join('air_craf_engine_types', 'air_craf_engine_types.id', '=', 'air_craf_types.engine_type_id')
                ->where('pilot_in_commands.biodata_id', $peopleId)
                ->selectRaw('air_craf_types.engine_type_id, air_craf_engine_types.name, SUM(pilot_in_commands.hours_flight) as hours_flight')
                ->groupBy(['air_craf_types.engine_type_id',
                'air_craf_engine_types.name'])
                ->get();
    }

    protected function getPicGroupByAircraf($peopleId, $engTypeId){
        return PIC::join('air_craf_types', 'air_craf_types.id', '=', 'pilot_in_commands.air_craft_id')
                ->join('air_craf_engine_types', 'air_craf_engine_types.id', '=', 'air_craf_types.engine_type_id')
                ->where('pilot_in_commands.biodata_id', $peopleId)
                ->where('air_craf_types.engine_type_id', $engTypeId)
                ->selectRaw('air_craf_types.id, air_craf_types.type, SUM(pilot_in_commands.hours_flight) as hours_flight')
                ->groupBy(['air_craf_types.id',
                        'air_craf_types.type'])
                ->get();
    }

    public function getPicData($peopleId){
        $picdt = [];
        $picByEngType = $this->getPicGroupByEngType($peopleId);
        foreach ($picByEngType as $pic) {
            $picByAc = $this->getPicGroupByAircraf($peopleId, $pic->engine_type_id);
            $tmp = [
                'id' => $pic->engine_type_id,
                'name' => $pic->name,
                'total_hours_flight' => $pic->hours_flight,
                'aircraf' => $picByAc
            ];
            $picdt = Arr::prepend($picdt, $tmp);
        }
        return $picdt;
    }

    public function storeData(Request $req){
        $data = [
            'biodata_id' => $req->bio_id,
            'air_craft_id' => $req->aircraf_id,
            'hours_flight' => $req->hours,
        ];

        $pic = PIC::create($data);
        return response()->json(compact('pic'));
    }
}
