<?php

namespace App\Http\Controllers\pilotLicences;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PilotLicence as PLicen;
use App\Http\Controllers\biodatas\Biodata as Bio;
use DB;

class PilotLicence extends Controller
{
    protected function getPeopleLicences($peopleId){
        return PLicen::select('pilot_licences.id',
                              'licence_types.name',
                              'pilot_licences.valid_start',
                              'pilot_licences.valid_end', // date startnya diganti tanggal sekarang
                              DB::raw("DATEDIFF(pilot_licences.valid_end, NOW()) as day_left"))
                    ->join('licence_types', 'licence_types.id', '=', 'pilot_licences.licence_type_id')
                    ->where('biodata_id', $peopleId)
                    ->orderBy('day_left', 'DESC')
                    ->get()->toArray();
    }

    protected function getLicenById($id){
        return PLicen::select('pilot_licences.id',
                              'licence_types.name',
                              'pilot_licences.valid_start',
                              'pilot_licences.valid_end', // date startnya diganti tanggal sekarang
                              DB::raw("DATEDIFF(pilot_licences.valid_end, NOW()) as day_left"))
                    ->join('licence_types', 'licence_types.id', '=', 'pilot_licences.licence_type_id')
                    ->where('pilot_licences.id', $id)
                    ->orderBy('day_left', 'DESC')
                    ->get()->toArray();
    }

    public function store(Request $req){
        $data = [
            'biodata_id' => $req->bio_id,
            'licence_type_id' => $req->licen_id,
            'valid_start' => $req->valid_start ?? null,
            'valid_end' => $req->valid_end ?? null,
        ];

        $plicen = PLicen::create($data);
        return response()->json(compact('plicen'));
    }

    public function storeData(Request $req){
        $data = [
            'biodata_id' => $req->bio_id,
            'licence_type_id' => $req->licen_id,
            'valid_start' => $req->valid_start ?? null,
            'valid_end' => $req->valid_end ?? null,
        ];
        $licen = PLicen::create($data);
        $licen = $this->getLicenById($licen->id)[0];
        return response()->json(compact('licen'));
    }

    public function getPeopleWithLicenceById($peopleId){
        $bio = new Bio();
        $bio = $bio->getPeopleByIdWithLicenceNum($peopleId);
        $plicens = $this->getPeopleLicences($peopleId);
        $people = [
            'id' => $bio->id,
            'name' => $bio->name,
            'licence_number' => $bio->licence_number,
            'licences' => $plicens
        ];
        return response()->json($people);
    }

    public function getLicensByPeopleId($people_id){
        $licens = $this->getPeopleLicences($people_id);
        return response()->json(compact('licens'));
    }

    public function removeById($item_id){
        $licen = PLicen::where('id', $item_id)->delete();
        return response()->json(compact('licen'));
    }
}
