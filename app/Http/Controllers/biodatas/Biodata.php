<?php

namespace App\Http\Controllers\biodatas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use JWTAuth;
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
                        ->orderBy('biodatas.name', 'DESC')
                        ->get([
                            'biodatas.id',
                            'biodatas.name',
                            'flight_licence_numbers.number as licence_number'
                        ]);
    }

    public function getPeoplesWithFlightNumByLikeName($name){
        return mbio::join('flight_licence_numbers', 'flight_licence_numbers.biodata_id', '=', 'biodatas.id')
                        ->where('flight_licence_numbers.active', true)
                        ->where('biodatas.is_deleted', false)
                        ->where('biodatas.name', 'like', "%{$name}%")
                        ->orderBy('biodatas.name', 'DESC')
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
                $pass = null;
                $licen = null;
                $projects = null;
                $employ = null;

                // CREATE BIODATA
                $bio = [
                    'user_id'       => $akun->id,
                    'name'          => $dataBio->name,
                    'place_birth'   => $dataBio->place_birth,
                    'date_birth'    => $dataBio->date_birth,
                    'marrid'        => $dataBio->marrid ?? '1',
                ];
                $bio = $this->createBio($bio);
                
                // CREATE PASSPORT
                $pass = [
                    'biodata_id'    => $bio->id,
                    'number'        => $dataBio->passport->number ?? NULL,
                    'valid_start'   => $dataBio->passport->date_start ?? NULL,
                    'valid_end'     => $dataBio->passport->date_end ?? NULL,
                ];
                $pass = $this->createPilotPassport($pass);
                
                // CREATE FLIGHT NUMBER
                $licen = [
                    'biodata_id'    => $bio->id,
                    'number'        => $dataBio->licence->number ?? NULL,
                    'valid_start'   => $dataBio->licence->date_start ?? NULL,
                    'valid_end'     => $dataBio->licence->date_end ?? NULL,
                ];
                $licen = $this->createFlightLicenceNumber($licen);
                
                // CREATE PROJECT EXPERIEN
                $projects = [];
                foreach ($dataBio->project as $item) {
                    $data=[
                        'name' => $item->name,
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
                        'name' => $item->name,
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

    public function getPeopleById($peopleId){
        $bio = mbio::join('users', 'users.id', '=', 'biodatas.user_id')
                    ->where('biodatas.id', $peopleId)
                    ->get(['users.email',
                           'biodatas.id',
                           'biodatas.name',
                           'biodatas.place_birth',
                           'biodatas.date_birth',
                           'biodatas.marrid'])
                    ->first();
        $passport = mpilotpass::where('biodata_id', $peopleId)->get();
        $licence = mflightlicencenumber::where('biodata_id', $peopleId)->get();
        $projects = projex::where('biodata_id', $peopleId)->get();
        $emhis = emhis::where('biodata_id', $peopleId)->get();

        $people = [
            'id' => $bio->id,
            'email' => $bio->email,
            'name' => $bio->name,
            'marrid' => $bio->marrid,
            'place_birth' => $bio->place_birth,
            'date_birth' => $bio->date_birth,
            'passport' => $passport[0],
            'licence' => $licence[0],
            'project_exps' => $projects,
            'employ_histories' => $emhis,
        ];

        return response()->json(compact('people'));
    }

    public function updatePeople(Request $req){
        $data = json_decode($req->getContent());
        return $this->responseFormat(true, 200, compact('data'));
    }

    protected function getBioIdFromAuth(){
        $user = JWTAuth::parseToken()->authenticate();
        $bio = mbio::where('user_id', $user->id)->select('id')->first();
        return $bio;
    }

    public function userInfo(){
        $bioId = $this->getBioIdFromAuth();
        if ($bioId) {
            $bio = mbio::join('users', 'users.id', '=', 'biodatas.user_id')
                        ->join('flight_licence_numbers', 'flight_licence_numbers.biodata_id', '=', 'biodatas.id')
                        ->join('pilot_passports', 'pilot_passports.biodata_id', '=', 'biodatas.id')
                        ->where('biodatas.id', $bioId->id)
                        ->where('pilot_passports.active', true)
                        ->where('flight_licence_numbers.active', true)
                        ->get(['users.email', 
                               'biodatas.id',
                               'biodatas.name',
                               'biodatas.img_av',
                               'biodatas.last_position',
                               'flight_licence_numbers.number as licence_number',
                               'pilot_passports.number as pas_number'])->first();
            $bio = [
                'id' => $bio->id,
                'name' => $bio->name,
                'email' => $bio->email,
                'image' => asset('avatars/' . $bio->img_av),
                'last_position' => $bio->last_position,
                'licence_number' => $bio->licence_number,
                'pas_number' => $bio->pas_number,
            ];
    
            return response()->json(['status' => true, 'msg' => compact('bio')]);
        } else {
            return response()->json(['status' => false, 'msg' => 'Unauthorized'], 401);
        }
    }

    public function getUserBio() {
        $bioId = $this->getBioIdFromAuth();
        if ($bioId) {
            $bio = mbio::join('flight_licence_numbers', 'flight_licence_numbers.biodata_id', '=', 'biodatas.id')
                        ->join('pilot_passports', 'pilot_passports.biodata_id', '=', 'biodatas.id')
                        ->where('biodatas.id', $bioId->id)
                        ->where('pilot_passports.active', true)
                        ->where('flight_licence_numbers.active', true)
                        ->get(['biodatas.id',
                               'biodatas.name',
                               'biodatas.place_birth',
                               'biodatas.date_birth',
                               'biodatas.marrid',
                               'biodatas.img_av',
                               'biodatas.last_position',
                               'flight_licence_numbers.number as licence_number',
                               'pilot_passports.number as pas_number'])
                        ->first();
            $bio = [
                'id' => $bio->id,
                'name' => $bio->name,
                'place_birth' => $bio->place_birth,
                'date_birth' => $bio->date_birth,
                'marrid' => $bio->marrid,
                'last_position' => $bio->last_position,
                'img_av' => [
                    'name' => $bio->img_av,
                    'url' =>  asset('avatars/' . $bio->img_av),
                ],
                'flight_licence' => [
                    'number' => $bio->licence_number,
                ],
                'passport' => [
                    'number' => $bio->pas_number
                ],
            ];
    
            return response()->json(['status' => true, 'msg' => compact('bio')]);
        } else {
            return response()->json(['status' => false, 'msg' => 'Unauthorized'], 401);
        }
    }

    public function addNewPassport(Request $req) {
        $data = [
            'biodata_id'    => $req->bio_id,
            'number'        => $req->pass_num ?? NULL,
            'valid_start'   => $req->date_start ?? NULL,
            'valid_end'     => $req->date_end ?? NULL,
        ];
        // SET ALL MENJADI FALSE
        mpilotpass::where('active', true)->update(['active' => false]);
        // INSERT NEW
        $passport = $this->createPilotPassport($data);
        return response()->json(compact('passport'));
    }

    public function addNewFLightLicence(Request $req){
        $data = [
            'biodata_id'    => $req->bio_id,
            'number'        => $req->flicence_number ?? NULL,
            'valid_start'   => $req->date_start ?? NULL,
            'valid_end'     => $req->date_end ?? NULL,
        ];

        // SET ALL ACTIVE FALSE
        mflightlicencenumber::where('active', true)->update(['active' => false]);
        // INSERT NEW
        $flight_licence = $this->createFlightLicenceNumber($data);
        return response()->json(compact('flight_licence'));
    }

    public function updateBiodata(Request $req){
        $data = [
            'name' => $req->name,
            'place_birth' => $req->place_birth,
            'date_birth' => $req->date_birth,
            'marrid' => $req->marrid,
            'last_position' => $req->last_position,
        ];
        $bio = mbio::where('id', $req->bio_id)->update($data);
        return response()->json(compact('bio'));
    }

    public function getRattings($id){
        $ratings=[];
        $fexp = mbio::where('biodatas.id', $id)
                    ->join('flight_experiences', 'flight_experiences.biodata_id', '=', 'biodatas.id')
                    ->join('air_craf_types', 'air_craf_types.id', '=', 'flight_experiences.air_craf_id')
                    ->select('air_craf_types.type');

        $rats = mbio::where('biodatas.id', $id)
                    ->join('pilot_in_commands', 'pilot_in_commands.biodata_id', '=', 'biodatas.id')
                    ->join('air_craf_types', 'air_craf_types.id', '=', 'pilot_in_commands.air_craft_id')
                    ->union($fexp)
                    ->select('air_craf_types.type')
                    ->get();
        foreach ($rats as $item) {
            $ratings = Arr::prepend($ratings, $item->type);
        }
        return response()->json(compact('ratings'));
    }

    public function searchByName($name=null){
        if (trim($name) != null ) {
            $biodatas = mbio::join('users', 'users.id', 'biodatas.user_id')
                        ->join('pilot_passports', 'pilot_passports.biodata_id', '=', 'biodatas.id')
                        ->join('flight_licence_numbers', 'flight_licence_numbers.biodata_id', '=', 'biodatas.id')
                        ->where('flight_licence_numbers.active', true)
                        ->where('pilot_passports.active', true)
                        ->where('biodatas.is_deleted', false)
                        ->where('biodatas.name', 'like', "%{$name}%")
                        ->get([
                            'users.email',
                            'biodatas.id',
                            'biodatas.name',
                            'biodatas.marrid',
                            'pilot_passports.number as passport_number',
                            'flight_licence_numbers.number as licence_number'
                        ]);
            return response()->json(compact('biodatas'));

        } else {
            return $this->getAllSummari();
        }
    }

    public function searchCertLicenByName($name=null){
        if (trim($name) != null ) {
            $biodatas = mbio::join('flight_licence_numbers', 'flight_licence_numbers.biodata_id', '=', 'biodatas.id')
                        ->where('flight_licence_numbers.active', true)
                        ->where('biodatas.is_deleted', false)
                        ->where('biodatas.name', 'like', "%{$name}%")
                        ->orderBy('biodatas.name', 'DESC')
                        ->get([
                            'biodatas.id',
                            'biodatas.name',
                            'flight_licence_numbers.number as licence_number'
                        ]);
            return response()->json(compact('biodatas'));
        } else {
            return $this->getAllPeopleWithFlightNum();
        }
    }
}
