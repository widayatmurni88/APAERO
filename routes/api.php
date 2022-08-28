<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User;
use App\Http\Controllers\EngineTypes\EngineType;
use App\Http\Controllers\AircrafTypes\AircrafType;
use App\Http\Controllers\certifikates\Certificate as Cert;
use App\Http\Controllers\licences\Licence;
use App\Http\Controllers\biodatas\Biodata as Bio;
use App\Http\Controllers\pilotCertificates\PilotCertificate as PCert;
use App\Http\Controllers\pilotLicences\PilotLicence as PLicen;
use App\Http\Controllers\flightExps\FlightExp as FExp;
use App\Http\Controllers\pilotInCommands\PilotInCommand as PIC;
use App\Http\Controllers\typeOfOperations\TypeOfOperation as Top;
use App\Http\Controllers\pilotTypeOfOperations\PilotTypeOfOperation as Ptop;
use App\Http\Controllers\works\Works;
use App\Http\Controllers\works\Projects;
use App\Http\Controllers\works\Employments;
use App\Http\Controllers\Dashboard as Dash;
use App\Http\Controllers\Upload;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

// Route::post('register', [User::class, 'register_user']);
Route::post('login', [User::class, 'login']);

// USERS ZONES
Route::middleware(['jwt.verify'])->group(function () {
    Route::post('upload', [Upload::class, 'uploadUsrImg']);
    Route::get('usr/logout', [User::class, 'logout']);

    Route::prefix('usr')->group(function () {
        Route::post('/addPassport', [Bio::class, 'addNewPassport']);
        Route::post('/addFLightLicence', [Bio::class, 'addNewFLightLicence']);
        Route::get('/info', [Bio::class, 'userInfo']);
        Route::get('/biodata', [Bio::class, 'getUserBio']);
        Route::get('/account', [User::class, 'getCurrentAccountInfo']);
        Route::post('/update', [User::class , 'updateEmailnPass']);
        Route::post('/updateBiodata', [Bio::class, 'updateBiodata']);
    });

    Route::prefix('works')->group(function () {
        Route::get('/{bio_id}', [Works::class, 'getWorks']);
        Route::prefix('projects')->group(function () {
            Route::get('/{bio_id}', [Works::class, 'getProjects']);
            Route::post('/addProject', [Projects::class, 'addProject']);
            Route::delete('/remove/{item_id}', [Projects::class, 'removeById']);
        });
        Route::prefix('/employments')->group(function () {
            Route::get('/{bio_id}', [Works::class, 'getEmployments']);
            Route::post('/addEmployment', [Employments::class, 'addEmployment']);
            Route::delete('/remove/{item_id}', [Employments::class, 'removeById']);
        });
    });

    Route::prefix('certs')->group(function () {
        Route::get('/', [Cert::class, 'getAllData']);
        Route::prefix('user')->group(function () {
            Route::get('/{bio_id}/{show_all}', [PCert::class, 'getCertsByPeopleId']);
            Route::post('/addCert', [PCert::class, 'addCert']);
            Route::delete('/remove/{item_id}', [PCert::class, 'removeByItem']);
        });
    });

    Route::prefix('licens')->group(function () {
        Route::get('/', [Licence::class, 'getAllData']);
        Route::prefix('user')->group(function () {
            Route::get('/{bio_id}', [PLicen::class, 'getLicensByPeopleId']);
            Route::post('/addLicen', [PLicen::class, 'storeData']);
            Route::delete('/remove/{item_id}', [Plicen::class, 'removeById']);
        });
    });

    Route::get('actypes', [AircrafType::class, 'showAll']);
    Route::get('tops', [Top::class, 'getData']);

    Route::prefix('fexps')->group(function () {
        Route::get('/getTotal/{bio_id}', function ($bio_id) {
            $ptop = new Ptop();
            $pic = new PIC();
            $fexp = new FExp();
            $fexps = [[
                    'code' => 'fexp',
                    'name' => 'Flight Experience',
                    'time_type' => 'Hr',
                    'total_time' => $fexp->getTotalFExpByPeople($bio_id)->hours_flight ?? 0,
                ], [
                    'code' => 'pic',
                    'name' => 'Pilot In Command',
                    'time_type' => 'Hr',
                    'total_time' => $pic->getPicsById($bio_id)->hours_flight ?? 0,
                ], [
                    'code' => 'top',
                    'name' => 'Type Of Operation',
                    'time_type' => 'Hr',
                    'total_time' => $ptop->getTotalHoursByPeopleId($bio_id)->hours_operation ?? 0,
                ],
            ];
            return response()->json(compact('fexps'));
        });

        Route::get('/ratings/{bio_id}', [Bio::class, 'getRattings']);
        
        Route::prefix('fexp')->group(function () {
            Route::get('/{bio_id}', [FExp::class, 'getFexpByPeopleId']);
            // Route::get('/{bio_id}', [FExp::class, 'getFexpByPeopleId']);
            Route::post('/addFexp', [FExp::class, 'storeData']);
        });

        Route::prefix('pic')->group(function () {
            Route::get('/{bio_id}', [PIC::class, 'getPicData']);
            Route::get('/getData/{bio_id}', [PIC::class, 'getPicDataById']);
            Route::post('/addPic', [PIC::class, 'storeData']);
        });

        Route::prefix('top')->group(function () {
            Route::get('/{bio_id}', [Ptop::class, 'getTops']);
            Route::post('/addTop', [Ptop::class, 'storeData']);
        });
    });
});


// ADMIN ZONES
Route::middleware(['jwt.admin'])->group(function () {
    Route::get('super/logout', [User::class, 'logout']);

    Route::prefix('dashboard')->group(function () {
        Route::get('/', [Dash::class, 'index']);
    });

    Route::prefix('account')->group(function () {
        Route::post('/create', [Bio::class, 'create']);
        Route::delete('/deleteById/{id}', [Bio::class, 'deleteById']);
        Route::post('/updatePeople', [Bio::class, 'updatePeople']);
    });
    
    Route::prefix('biodatas')->group(function () {
        Route::get('/search-by-name/{name?}', [Bio::class, 'searchByName']);
        Route::get('/getPeopleById/{peopleId}', [Bio::class, 'getPeopleById']);
        Route::get('/getAllSummari', [Bio::class, 'getAllSummari']);
        Route::get('/getAllPeopleWithFlightNum', [Bio::class, 'getAllPeopleWithFlightNum']);
        Route::get('/search-cert-licence-by-name/{name?}', [Bio::class, 'searchCertLicenByName']);
        Route::post('/addCert', [PCert::class, 'store']);
        Route::post('/addLicence', [PLicen::class, 'store']);
        Route::get('/getPeopleWithCertById/{peopleId}', [PCert::class, 'getPeopleWithCertById']);
        Route::get('/getPeopleWithLicenceById/{peopleId}', [PLicen::class, 'getPeopleWithLicenceById']);
        Route::get('/getPeopleWithExps', [FExp::class, 'getAllPeopleExps']);
        Route::get('/search-people-exps-by-name/{name?}', [FExp::class, 'searchPeopleExpsByName']);
    
        Route::prefix('/flightExperience')->group(function () {
            Route::get('/getFExpById/{id}', [FExp::class, 'getPeopleWithFExById']);
            Route::post('/store', [FExp::class, 'storeData']);
            Route::get('/peopleSummary/{id}', [FExp::class, 'getFexGroupByTypeEng']);
    
            Route::prefix('/pic')->group(function () {
                Route::post('/store', [PIC::class, 'storeData']);
            });
    
            Route::prefix('/top')->group(function () {
                Route::post('/store', [Ptop::class, 'storeData']);
            });
        });
    
    });
    
    Route::prefix('aircraf')->group(function () {
        Route::prefix('engine_type')->group(function () {
              Route::get('/getAll', [EngineType::class, 'showAll']);
              Route::post('/store', [EngineType::class,'storeData']);
        });
        Route::get('/', [AircrafType::class, 'showAll']);
        Route::get('/search-by-name/{name?}', [AircrafType::class, 'searchByName']);
        Route::get('getDataById/{id}', [AircrafType::class, 'getDataById']);
        Route::post('/store', [AircrafType::class, 'storeData']);
        Route::post('/update', [AircrafType::class, 'update']);
        Route::delete('deleteById/{id}', [AircrafType::class, 'deleteData']);
    });
    
    Route::prefix('cert')->group(function () {
        Route::get('/', [Cert::class, 'getAllData']);
        Route::get('/getDataById/{id}', [Cert::class, 'getDataById']);
        Route::delete('/delete/{id}', [Cert::class, 'deleteById']);
        Route::post('/store', [Cert::class, 'storeData']);
        Route::post('/update', [Cert::class, 'update']);
        Route::get('/getIdName', [Cert::class, 'getIdName']);
        Route::get('/search-by-name/{name?}', [Cert::class, 'searchByName']);
    });
    
    Route::prefix('licence')->group(function () {
        Route::get('/', [Licence::class, 'getAllData']);
        Route::post('/store', [Licence::class, 'storeData']);
        Route::post('/update', [Licence::class, 'updateData']);
        Route::delete('/deleteById/{id}', [Licence::class, 'deleteById']);
        Route::get('/getDataById/{id}', [Licence::class, 'getDataById']);
        Route::get('/search-by-name/{name?}', [Licence::class, 'searchByName']);
    });
    
    Route::prefix('top')->group(function () {
        Route::get('/', [Top::class, 'getData']);
        Route::get('/{id}', [Top::class, 'getById']);
        Route::post('/store', [Top::class, 'storeData']);
        Route::post('/updateData', [Top::class, 'updateData']);
        Route::delete('/deleteById/{id}', [Top::class, 'deleteById']);
    });
});