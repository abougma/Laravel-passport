<?php

use App\Http\Controllers\Api\FluxController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\SeanceController;

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

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
  //  return $request->user();
//});


Route::post('/seance', [SeanceController::class, 'createSeanceWithApprenantAndEnseignant'])->middleware('client');
Route::post('/seances', [SeanceController::class, 'createSeances'])->middleware('client');
Route::get('/get/seances/{id}',[SeanceController::class, 'getSeance'])->middleware('client');
Route::get('/get/all/seances', [SeanceController::class,'getSeances'])->middleware('client');

Route::get('/get/apprenant/{apprenant_id}/seance', [SeanceController::class, 'getApprenantSeances'])->middleware('client');
Route::get('/get/enseignant/{ensegnant_id}/seance', [SeanceController::class, 'getEnseignantSeances'])->middleware('client');

Route::get('/get/enseignant/{enseignant_id}/seance/{seance}', [SeanceController::class, 'getEnseignantSeance'])->middleware('client');
Route::get('/get/apprenant/{apprenant_id}/seance/{seance}', [SeanceController::class, 'getApprenantSeance'])->middleware('client');

Route::get('/get/data/seances', [SeanceController::class, 'getEnseignantSeanceApprenant'])->middleware('client');

Route::delete('/delete/seance/{seance_id}', [SeanceController::class, 'deleteSeance'])->middleware('client');

Route::post('/emargement/enseignant/{enseignant_id}/seance/{seance_id}', [SeanceController::class, 'createEnseignantEmargement'])->middleware('client');
Route::post('/emargement/apprenant/{apprenant_id}/seance/{seance_id}', [SeanceController::class, 'creatApprenantEmargment'])->middleware('client');
Route::get('/data/enseignant/{enseignant_id}/', [SeanceController::class, 'getEnseignantSeanceEmargement'])->middleware('client');
Route::get('/data/apprenant/{apprenant_id}', [SeanceController::class, 'getApprenantSeanceEmargement'])->middleware('client');


Route::middleware('client', 'getClient')->group(function (){
    //Séances
    Route::post('/create/seance', [FluxController::class, 'createSeance']);
    Route::post('/create/seances', [FluxController::class, 'createSeances']);

    Route::get('/get/seance/{seance_id}', [FluxController::class, 'getSeance']);
    Route::get('/get/seances', [FluxController::class, 'getSeances']);
    Route::get('/get/seances/{seance_id}/enseignants/apprenants', [FluxController::class, 'getSeanceAndEnseignantAndApprenants']);

    Route::delete('/delete/seance/{seance_id}', [FluxController::class, 'deleteSeance']);

    //Enseignants
    Route::post('/create/enseignant', [FluxController::class, 'createEnseignant']);

    Route::get('/get/enseignant/{enseignant_id}', [FluxController::class, 'getEnseignant']);
    Route::get('/get/enseignant/seance/{seance_id}', [FluxController::class, 'getEnseignantAssociateSeance']);
    Route::get('/get/seance/enseignant/{enseignant_id}', [FluxController::class, 'getSeanceAssociateEnseigne']);


    Route::delete('/delete/enseignant/{enseignant_id}', [FluxController::class, 'deleteEnseignant']);

    Route::delete('/delete/enseignant/{enseignant_id}/seance/{seance_id}', [FluxController::class, 'deleteEnseignantSeance']);

    //Apprenants
    Route::post('/create/apprenant', [FluxController::class, 'createApprenant']);

    Route::get('/get/apprenant/{apprenant_id}', [FluxController::class, 'getApprenant']);
    Route::get('/get/seance/{seance_id}/apprenant', [FluxController::class, 'getSeanceAssociateApprenant']);
    Route::get('/get/apprenant/{apprenant_id}/seance', [FluxController::class, 'getApprenantAssociateSeance']);

    Route::delete('/delete/apprenant/{apprenant_id}/seance/{seance_id}', [FluxController::class, 'deleteApprenantSeance']);
});

Route::post('/seance', [SeanceController::class, 'store'])->middleware('client');

//Route::middleware('client')->post('/seance', [SeanceController::class, 'store']);

