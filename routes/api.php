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
    Route::post('/seance', [FluxController::class, 'createSeance']);
    Route::post('/seances', [FluxController::class, 'createSeances']);
    Route::post('/seances/date', [FluxController::class, 'createSeancesDate']);

    Route::get('/seance/{seance_id}', [FluxController::class, 'getSeance']);
    Route::get('/seances', [FluxController::class, 'getSeances']);
    Route::get('/seances/{seance_id}/enseignants/apprenants', [FluxController::class, 'getSeanceAndEnseignantAndApprenants']);

    Route::delete('/seance/{seance_id}', [FluxController::class, 'deleteSeance']);
    Route::delete('/seances/date/', [FluxController::class, 'deleteSeancesDate']);
    //Enseignants
    Route::post('/enseignant', [FluxController::class, 'createEnseignant']);
    Route::post('/enseignants', [FluxController::class, 'createEnseignants']);
    Route::post('/enseignants/update', [FluxController::class, 'updataEnseignant']);

    Route::get('/enseignant/{enseignant_id}', [FluxController::class, 'getEnseignant']);
    Route::get('/enseignants', [FluxController::class, 'getEnseignants']);
    Route::get('/enseignant/seance/{seance_id}', [FluxController::class, 'getEnseignantAssociateSeance']);
    Route::get('/seance/enseignant/{enseignant_id}', [FluxController::class, 'getSeanceAssociateEnseigne']);

    Route::delete('/enseignant/{enseignant_id}', [FluxController::class, 'deleteEnseignant']);

    Route::delete('/enseignant/{enseignant_id}/seance/{seance_id}', [FluxController::class, 'deleteEnseignantSeance']);

    //Apprenants
    Route::post('/apprenant', [FluxController::class, 'createApprenant']);
    Route::post('/apprenants', [FluxController::class, 'createApprenants']);
    Route::post('/apprenant/update', [FluxController::class, 'updateApprenant']);

    Route::get('/apprenant/{apprenant_id}', [FluxController::class, 'getApprenant']);
    Route::get('/apprenants', [FluxController::class, 'getApprenants']);
    Route::get('/seance/{seance_id}/apprenant', [FluxController::class, 'getSeanceAssociateApprenant']);
    Route::get('/apprenant/{apprenant_id}/seance', [FluxController::class, 'getApprenantAssociateSeance']);

    Route::delete('/apprenant/{apprenant_id}/seance/{seance_id}', [FluxController::class, 'deleteApprenantSeance']);
    Route::delete('/apprenant/{apprenant_id}', [FluxController::class, 'deleteApprenants']);
});
