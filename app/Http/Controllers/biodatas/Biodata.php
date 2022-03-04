<?php

namespace App\Http\Controllers\biodatas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use App\Http\Controllers\User;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use App\Models\User as muser;
use App\Models\Biodata as mbio;
use App\Models\PilotPassport as mpilotpass;
use App\Models\FlightLicenceNumber as mflightlicencenumber;
use App\Models\ProjectExperien as projex;
use App\Models\EmployHistory as emhis;

class Biodata extends Controller
{
    protected function responseFormat($status = false, $code = 200, $res){
        return response()->json([
            'status' => $status,
            'content' => $res,
        ], $code);
    }
    
    protected function createAccount($data){
        return muser::create([
            'name' => '',
            'email' => $data->email,
            'password' => bcrypt($data->password)
        ]);
    }

    protected function createBio($data){
        return mbio::create($data);
    }

    protected function createPilotPassport($data){
        return mpilotpass::create($data);
    }
    
    protected function createFlightLicenceNumber($data){
        return mflightlicencenumber::create($data);
    }

    protected function createProjectExperien($data){
        return projex::create($data);
    }

    protected function createEmployHistory($data){
        return emhis::create($data);
    }

    public function getPeoplesWithFlightNum(){
        return mbio::join('flight_licence_numbers', 'flight_licence_numbers.biodata_id', '=', 'biodatas.id')
                        ->where('flight_licence_numbers.active', true)
                        ->where('biodatas.is_deleted', false)
                        ->get([
                            'biodatas.id',
                            'biodatas.name',
                            'flight_licence_numbers.number as licence_number'
                        ]);
    }
    
    public function create(Request $req){
        $usr = new User();
        $data = json_decode($req->getContent());
        $dataAkun = $data->account;
        $dataBio = $data->bio;

        if (!$usr->cekExistingEmail($dataAkun->email)) {
            $akun = $this->createAccount($dataAkun);
            if ($akun) {
                // CREATE BIODATA
                $bio = [
                    'user_id'       => $akun->id,
                    'name'          => $dataBio->name,
                    'place_birth'   => $dataBio->place_birth,
                    'date_birth'    => $dataBio->date_birth,
                    'marrid'        => $dataBio->marrid,
                ];
                $bio = $this->createBio($bio);
                
                // CREATE PASSPORT
                $pass = [
                    'biodata_id'    => $bio->id,
                    'number'        => $dataBio->passport->pass_number,
                    'valid_start'   => $dataBio->passport->date_start,
                    'valid_end'     => $dataBio->passport->date_end,
                ];
                $pass = $this->createPilotPassport($pass);
                
                // CREATE FLIGHT NUMBER
                $licen = [
                    'biodata_id'    => $bio->id,
                    'number'        => $dataBio->licence->number,
                    'valid_start'   => $dataBio->licence->date_start,
                    'valid_end'     => $dataBio->licence->date_end,
                ];
                $licen = $this->createFlightLicenceNumber($licen);
                
                // CREATE PROJECT EXPERIEN
                $projects = [];
                foreach ($dataBio->project as $item) {
                    $data=[
                        'name' => $item->project_name,
                        'date_start' => $item->date_start,
                        'date_end' => $item->date_end,
                        'biodata_id' => $bio->id,
                    ];
                    $tmpCreate = $this->createProjectExperien($data);
                    $projects = Arr::prepend($projects, $tmpCreate);
                }

                // CREATE EMPLOY HISTORY
                $employ = [];
                foreach ($dataBio->employ as $item) {
                    $data=[
                        'name' => $item->employ_name,
                        'date_start' => $item->date_start,
                        'date_end' => $item->date_end,
                        'biodata_id' => $bio->id,
                    ];
                    $tmpCreate = $this->createEmployHistory($data);
                    $employ = Arr::prepend($employ, $tmpCreate);
                }       

                $biodatas = [
                    'id' => $bio->id,
                    'email' => $akun->email,
                    'name' => $bio->name,
                    'place_birth' => $bio->place_birth,
                    'date_birth' => $bio->date_birth,
                    'marrid' => $bio->marrid,
                    'passport' => $pass,
                    'licence' => $licen,
                    'project' => $projects,
                    'employ' => $employ,
                ];
                
                return $this->responseFormat(true, 200, compact('biodatas'));
            }
        } else {
            return $this->responseFormat(false, 200, ['email' => 'email sudah ada!']);
        }
    }

    public function getAllSummari(){
        $biodatas = mbio::join('users', 'users.id', 'biodatas.user_id')
                        ->join('pilot_passports', 'pilot_passports.biodata_id', '=', 'biodatas.id')
                        ->join('flight_licence_numbers', 'flight_licence_numbers.biodata_id', '=', 'biodatas.id')
                        ->where('flight_licence_numbers.active', true)
                        ->where('pilot_passports.active', true)
                        ->where('biodatas.is_deleted', false)
                        // ->where('biodatas.is_deleted', false)
                        ->get([
                            'users.email',
                            'biodatas.id',
                            'biodatas.name',
                            'biodatas.marrid',
                            'pilot_passports.number as passport_number',
                            'flight_licence_numbers.number as licence_number'
                        ]);
        return response()->json(compact('biodatas'));
    }

    public function getAllPeopleWithFlightNum(){
        $biodatas = $this->getPeoplesWithFlightNum();
        return response()->json(compact('biodatas'));
    }

    public function getPeopleByIdWithFlightNum($id){
        return mbio::join('flight_licence_numbers', 'flight_licence_numbers.biodata_id', '=', 'biodatas.id')
                        ->where('flight_licence_numbers.active', true)
                        ->where('biodatas.is_deleted', false)
                        ->where('biodatas.id', $id)
                        ->get([
                            'biodatas.id',
                            'biodatas.name',
                            'flight_licence_numbers.number as licence_number'
                        ])->first();
    }

    public function getPeopleByIdWithLicenceNum($id){
        return mbio::join('flight_licence_numbers', 'flight_licence_numbers.biodata_id', '=', 'biodatas.id')
                        ->where('flight_licence_numbers.active', true)
                        ->where('biodatas.is_deleted', false)
                        ->where('biodatas.id', $id)
                        ->get([
                            'biodatas.id',
                            'biodatas.name',
                            'flight_licence_numbers.number as licence_number'
                        ])->first();
    }

    public function deleteById($id){
        $userid = mbio::where('id', $id)->get(['user_id'])[0];
        mbio::where('id', $id)->update(['is_deleted' => true]);
        muser::where('id', $userid->user_id)->update(['is_deleted' => true]);
        return response()->json('success');
    }
}
