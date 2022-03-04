<?php

namespace App\Http\Controllers\pilotCertificates;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PilotCertificate as PCert;
use App\Http\Controllers\biodatas\Biodata as Bio;
use DB;

class PilotCertificate extends Controller
{
    protected function getCertByPeopleId($peopleId){
        return PCert::select('pilot_certificates.id',
                             'pilot_certificates.biodata_id',
                             'certificates.id as cert_id',
                             'certificates.name as cert_name',
                             'pilot_certificates.valid_start',
                             'pilot_certificates.valid_end',
                             DB::raw("DATEDIFF(pilot_certificates.valid_end, NOW()) as day_left"))
                    ->join('certificates', 'certificates.id', '=', 'pilot_certificates.certificate_id')
                    ->where('biodata_id', $peopleId)
                    ->orderBy('day_left', 'DESC')
                    ->get()->toArray();
        // return PCert::join('certificates', 'certificates.id', '=', 'pilot_certificates.certificate_id')
        //             ->where('biodata_id', $peopleId)
        //             ->select(
        //                 'pilot_certificates.id',
        //                 'pilot_certificates.biodata_id',
        //                 'certificates.id as cert_id',
        //                 'certificates.name as cert_name',
        //                 'pilot_certificates.valid_start',
        //                 'pilot_certificates.valid_end',
        //             );
    }

    protected function getCertByCertIdWithCertName($certId, $peopleId){
        return PCert::join('certificates', 'certificates.id', '=', 'pilot_certificates.certificate_id')
                    ->where('biodata_id', $peopleId)
                    ->where('pilot_certificates.id', $certId)
                    ->get([
                        'pilot_certificates.id',
                        'pilot_certificates.biodata_id',
                        'certificates.id as cert_id',
                        'certificates.name as cert_name',
                        'pilot_certificates.valid_start',
                        'pilot_certificates.valid_end',
                    ]);
    }

    public function store(Request $req){
        $data = [
            'biodata_id' => $req->biodata_id,
            'certificate_id' => $req->cert_id,
            'valid_start' => $req->valid_start,
            'valid_end' => $req->valid_end,
        ];

        $pcert = PCert::create($data);
        $pcert = $this->getCertByCertIdWithCertName($pcert->id, $pcert->biodata_id)[0];
        return response()->json(compact('pcert'));
    }

    public function getPeopleWithCertById($peopleId){
        $bio = new Bio();
        $bio = $bio->getPeopleByIdWithFlightNum($peopleId);
        $pcert = $this->getCertByPeopleId($peopleId);
        $people = [
            'id' => $bio->id,
            'name' => $bio->name,
            'licence_number' => $bio->licence_number,
            'certificates' => $pcert
        ];

        return response()->json(compact('people'));
    }
}
