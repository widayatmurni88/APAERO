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

Route::post('register', [User::class, 'register_user']);
Route::post('login', [User::class, 'login']);

// ADMIN ZONES
Route::prefix('account')->group(function () {
    Route::post('/create', [Bio::class, 'create']);
    Route::delete('/deleteById/{id}', [Bio::class, 'deleteById']);
});

Route::prefix('biodatas')->group(function () {
    Route::get('/getAllSummari', [Bio::class, 'getAllSummari']);
    Route::get('/getAllPeopleWithFlightNum', [Bio::class, 'getAllPeopleWithFlightNum']);
    Route::post('/addCert', [PCert::class, 'store']);
    Route::post('/addLicence', [PLicen::class, 'store']);
    Route::get('/getPeopleWithCertById/{peopleId}', [PCert::class, 'getPeopleWithCertById']);
    Route::get('/getPeopleWithLicenceById/{peopleId}', [PLicen::class, 'getPeopleWithLicenceById']);
    Route::get('/getPeopleWithExps', [FExp::class, 'getAllPeopleExps']);

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
});

Route::prefix('licence')->group(function () {
    Route::get('/', [Licence::class, 'getAllData']);
    Route::post('/store', [Licence::class, 'storeData']);
    Route::post('/update', [Licence::class, 'updateData']);
    Route::delete('/deleteById/{id}', [Licence::class, 'deleteById']);
    Route::get('/getDataById/{id}', [Licence::class, 'getDataById']);
});

Route::prefix('top')->group(function () {
    Route::get('/', [Top::class, 'getData']);
    Route::get('/{id}', [Top::class, 'getById']);
    Route::post('/store', [Top::class, 'storeData']);
    Route::post('/updateData', [Top::class, 'updateData']);
    Route::delete('/deleteById/{id}', [Top::class, 'deleteById']);
});