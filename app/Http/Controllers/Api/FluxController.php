<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ApprenantResource;
use App\Http\Resources\EnseignantResource;
use App\Http\Resources\SeanceResource;
use App\Models\Apprenant;
use App\Models\Enseignant;
use App\Models\Seance;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;


/* Filtrer la suppression des seances en fonction de la source_id et la source_name

 * Proposer une solution pour que quand j'envoie un apprenant a l'id 35000 de mon api a mon outils par exemple
 * dans mon outils puisse savoir quelle est le compte utilisateur de cet apprenant,
 * vu que chaque apprenant est lier a un compte utilisateur.
 *
 */

class FluxController extends Controller
{
    // Séances

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * Création d'une seance
     */

    public function createSeance(Request $request)
    {
        $validateData = $request->validate([
            'intitule' => 'required|string',
            'date_debut' => 'required|date',
            'date_fin' => 'required|date',
            'duration' => 'required|integer',
            'seance_id_externe' => 'required|string',
            'matiere_id' => 'required|integer',
            'enseignants' => 'required|array',
            'enseignant_id' => 'integer|exists:enseignants,id',
            'apprenants' => 'required|array',
            'apprenants.*' => 'integer|exists:apprenants,id',
            'id' => 'required|integer'
        ]);

        $sourceName = $request->get('source_name');
        $sourceId = $validateData['id'];

        $dateDebut = Carbon::parse($validateData['date_debut'])->timestamp;
        $dateFin = Carbon::parse($validateData['date_fin'])->timestamp;

        $seance = Seance::updateOrCreate(
            [
                'intitule' => $validateData['intitule'],
                'matiere_id' => $validateData['matiere_id'],
                'dabte_debut' => $dateDebut,
                'date_fin' => $dateFin,
                'duration' => $validateData['duration'],
                'seance_id_externe' => $validateData['seance_id_externe']
            ],
            [
                'source_name' => $sourceName,
                'source_id' => $sourceId,
            ]
        );

        $seance->enseignant()->attach($validateData['enseignants']);
        $seance->apprenants()->sync($validateData['apprenants']);

        $seanceRessource =  new SeanceResource($seance);
        return response()->json([
            'message' => 'Séance créée ou mis à jour avec succès',
            'seance' => $seanceRessource
        ], 201);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * Création de plusieurs séances
     */

    /*
    public function createSeances(Request $request)
    {
        $validateData = $request->validate([
            'seances' => 'required|array',
            'seances.*.intitule' => 'required|string',
            'seances.*.date_debut' => 'required|date',
            'seances.*.date_fin' => 'required|date',
            'seances.*.duration' => 'required|integer',
            'seances.*.seance_id_externe' => 'required|string',
            'seances.*.matiere_id' => 'required|integer',
            'seances.*.id' => 'required|integer',
            'seances.*.enseignant_id' => 'required|integer|exists:enseignants,id',
            'seances.*.apprenants' => 'required|array',
            'seances.*.apprenants.*' => 'integer|exists:apprenants,id'
        ]);

        $sourceName = $request->get('source_name');
        $createdSeances = [];

        foreach ($validateData['seances'] as $seanceData) {
            $sourceId = $seanceData['id'];

            $dateDebut = Carbon::parse($seanceData['date_debut'])->timestamp;
            $dateFin = Carbon::parse($seanceData['date_fin'])->timestamp;

            $seance = Seance::updateOrCreate(
                [
                    'source_name' => $sourceName,
                    'source_id' => $sourceId
                ],
                [
                    'intitule' => $seanceData['intitule'],
                    'duration' => $seanceData['duration'],
                    'seance_id_externe' => $seanceData['seance_id_externe'],
                    'matiere_id' => $seanceData['matiere_id'],
                    'dabte_debut' => $dateDebut,
                    'date_fin' => $dateFin
                ]
            );

            $seance->enseignants()->sync([$seanceData['enseignant_id']]);
            $seance->apprenants()->sync($seanceData['apprenants']);
            $createdSeances[] = $seance;
        }

        $seanceRessource = SeanceResource::collection($createdSeances);

        return response()->json([
            'message' => 'Séances créées avec succès',
            'seances' => $seanceRessource
        ], 201);
    }
    */

    public function createSeancesDate(Request $request)
    {
        $validateData = $request->validate([
            'date_debut' => 'required|date',
            'date_fin' => 'required|date',
            'seances' => 'required|array',
            'seances.*.intitule' => 'required|string',
            'seances.*.matiere_id' => 'required|integer',
            'seances.*.duration' => 'required|integer',
            'seances.*.seance_id_externe' => 'required|string',
            'seances.*.dateDebut' => 'required|date',
            'seances.*.dateFin' => 'required|date',
            'seances.*.id' => 'required|integer',
            'seances.*.enseignants' => 'required|array',
            'seances.*.enseignants.*.*' => 'required|integer|exists:enseignants,id',
            'seances.*.apprenants' => 'required|array',
            'seances.*.apprenants.*.*' => 'required|integer|exists:apprenants,id'
        ]);

        $dateDebut = Carbon::parse($validateData['date_debut'])->startOfDay()->timestamp;
        $dateFin = Carbon::parse($validateData['date_fin'])->endOfDay()->timestamp;

        $existSeances = Seance::whereBetween('dabte_debut', [$dateDebut, $dateFin])->get();
        $newSeances = collect($validateData['seances'])->pluck('id')->toArray();

        $deleteSeance = $existSeances->filter(function ($seance) use ($newSeances){
            return !in_array($seance->id, $newSeances);
        });

        foreach ($deleteSeance as $seance){
            $seance->enseignant()->detach();
            $seance->apprenants()->detach();
            $seance->delete();
        }

        $createSeances = [];

        foreach ($validateData['seances'] as $seanceData){
            $sourceName = $request->get('source_name');
            $sourceId = $seanceData['id'];

            $date_debut_seance = Carbon::parse($seanceData['dateDebut'])->timestamp;
            $date_fin_seance = Carbon::parse($seanceData['dateFin'])->timestamp;

            $seance = Seance::updateOrCreate(
                [
                    'source_id' => $sourceId,
                    'source_name' => $sourceName
                ],
                [
                    'intitule' => $seanceData['intitule'],
                    'duration' => $seanceData['duration'],
                    'matiere_id' => $seanceData['matiere_id'],
                    'seance_id_externe' => $seanceData['seance_id_externe'],
                    'dabte_debut' => $date_debut_seance,
                    'date_fin' => $date_fin_seance
                ]
            );
            $seance->enseignant()->sync($seanceData['enseignants']);
            $seance->apprenants()->sync($seanceData['apprenants']);

            $createSeances[] = $seance;
        }

        $seanceRessource = SeanceResource::collection($createSeances);

        return response()->json([
            'message' => 'Seance créer ou mis à avec succès',
            'seances' => $seanceRessource
        ], 201);
    }

    /**
     * @param $seance_id
     * @return \Illuminate\Http\JsonResponse
     * Récuperer une séance specifique
     */

    public function getSeance($seance_id)
    {
        $seance = Seance::find($seance_id);

        if (!$seance){
            return response()->json([
                'error' => 'Aucune séance ne correspond'
            ],400);
        }

        $seanceRessource = new SeanceResource($seance);
        return response()->json([
            'seance' => $seanceRessource
        ],200);
    }

    /**
     * @param $seance_id
     * @return \Illuminate\Http\JsonResponse
     * Récuperer une séance ainsi que l'enseignant et les apprenants
     */

    public function getSeanceAndEnseignantAndApprenants($seance_id)
    {
        $seance = Seance::with('enseignants', 'apprenants')->find($seance_id);
        return response()->json([
            'seance' => $seance,
        ],200 );

    }

    /**
     * @return \Illuminate\Http\JsonResponse
     * Récuperer toute les seances
     */

    public function getSeances()
    {
        $seances = Seance::paginate(3);
        if (!$seances) {
            return response()->json([
                'error' => 'Aucune séance existante'
            ], 400);
        }

        $seanceRessource = SeanceResource::collection($seances);
        return response()->json([
            'seances' => $seanceRessource,
            'pagination' => [
                'current_page' => $seances->currentPage(),
                'per_page' => $seances->perPage(),
                'total' => $seances->total(),
                'last_page' => $seances->lastPage()
            ]
        ], 200);
    }


    /**
     * @param $seance_id
     * @return \Illuminate\Http\JsonResponse
     * Supprimer un séance
     */

    public function deleteSeance($seance_id)
    {
        $seance = Seance::find($seance_id);
        if (!$seance){
            return response()->json([
                'error' => 'Aucune séance ne correspond'
            ],400 );
        }
        $seance->delete();

        return response()->json([
            'message' => 'Séance supprimer avec succès'
        ], 200);
    }

    public function deleteSeancesDate(Request $request)
    {
        $validateData = $request->validate([
            'seances' => 'required|array',
            'seances.*.date_debut' => 'required|date',
            'seances.*.date_fin' => 'required|date'
        ]);

        $sourceName = $request->get('source_name');
        $totalDeletedSeances = 0;

        foreach ($validateData['seances'] as $seanceData) {
            $dateDebut = Carbon::parse($seanceData['date_debut'])->startOfDay()->timestamp;
            $dateFin = Carbon::parse($seanceData['date_fin'])->endOfDay()->timestamp;

            $existSeances = Seance::where('source_name', $sourceName)
                ->whereBetween('dabte_debut', [$dateDebut, $dateFin])
                ->get();

            if ($existSeances->isEmpty()) {
                return response()->json([
                    'error' => "Aucune séance n'est comprise dans cette intervalle de dates pour le source_name spécifié"
                ], 403);
            }

            foreach ($existSeances as $seanceData) {
                $seanceData->enseignant()->detach();
                $seanceData->apprenants()->detach();
                $seanceData->delete();
                $totalDeletedSeances++;
            }
        }

        return response()->json([
            'message' => " les $totalDeletedSeances séances à cette date ont été supprimées",
        ], 200);
    }


    //Enseignants

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * Création d'un enseignant
     */
    public function createEnseignant (Request $request){

        $validateData = $request->validate([
            'nom' => 'required|string',
            'prenom' => 'required|string',
            'email' => 'required|string',
            'id' => 'required|integer'
        ]);

        $sourceName = $request->get('source_name');
        $sourceId = $validateData['id'];

        $user = User::where('email', $validateData['email'])->first();

        if (!$user) {
            $user = User::create([
                'name' => $validateData['prenom'] . ' ' . $validateData['nom'],
                'email' => $validateData['email'],
                'password' => bcrypt('SigneTesSeances2024')
            ]);
        }

        $enseignant = Enseignant::updateOrCreate(
            [
                'source_name' => $sourceName,
                'source_id' => $sourceId
            ],
            [
                'nom' => $validateData['nom'],
                'prenom' => $validateData['prenom'],
                'email' => $validateData['email'],
                'user_id' => $user->id
            ]
        );

        $enseignantRessource = new EnseignantResource($enseignant);
        return response()->json([
            'message' => 'Enseignant créer ou mis à jour avec succès',
            'enseignant' => $enseignantRessource

        ], 201);
    }

    public function createEnseignants(Request $request)
    {
        $validateData = $request->validate([
            'enseignants' => 'required|array',
            'enseignants.*.nom' => 'required|string',
            'enseignants.*.prenom' => 'required|string',
            'enseignants.*.email' => 'required|string',
            'enseignants.*.id' => 'required|integer',
            'enseignants.*.enseignant_id_externe' => 'required|integer'
        ]);

        $createEnseignant = [];

        foreach ($validateData['enseignants'] as $enseignantData){
            $sourceName = $request->get('source_name');
            $sourceId = $enseignantData['id'];

            $user = User::where('email',$validateData['email'])->first();

            if (!$user){
                $user = User::create([
                    'name' => $validateData['prenom'] . ' ' . $validateData['nom'],
                    'email' => $validateData['email'],
                    'password' => bcrypt('SigneTesSeances2024')
                ]);
            }

            $enseignant = Enseignant::updateOrCreate(
                [
                    'source_name' => $sourceName,
                    'source_id' => $sourceId
                ],
                [
                'nom' => $enseignantData['nom'],
                'prenom' => $enseignantData['prenom'],
                'email' => $enseignantData['email'],
                'enseignant_id_externe' => $enseignantData['enseignant_id_externe'],
                'user_id' => $user->id
                ]
            );

            $createEnseignant[] = $enseignant;
        }
        return response()->json([
            'message' => 'Enseignant créer',
            'enseignants' => $createEnseignant
        ],201);
    }

    public function updataEnseignant(Request $request)
    {
        $validateData = $request->validate([
            'enseignants' => 'required|array',
            'enseignants.*.nom' => 'required|string',
            'enseignants.*.prenom' => 'required|string',
            'enseignants.*.email' => 'required|string',
            'enseignants.*.id' => 'required|integer'
        ]);

        $enseignantIds = collect($validateData['enseignants'])->pluck('id')->toArray();

        $deleteEnseignant = Enseignant::whereNotIn('source_id',$enseignantIds)->get();
        foreach ($deleteEnseignant as $enseignant){
            $enseignant->seances()->detach();
            $enseignant->delete();
        }
        $updateEnseignant = [];

        foreach ($validateData['enseignants'] as $enseignantData){
            $sourceName = $request->get('source_name');
            $sourceId = $enseignantData['id'];

            $user = User::where('email', $enseignantData['email'])->first();
            if (!$user){
                $user = User::create([
                    'name' => $enseignantData['prenom'] . ' ' . $enseignantData['nom'],
                    'email' => $enseignantData['email'],
                    'password' => bcrypt('SigneTesSeances2024')
                ]);
            }
            $enseignant = Enseignant::updateOrCreate(
                [
                    'source_name' => $sourceName,
                    'source_id' => $sourceId
                ],
                [
                    'nom' => $enseignantData['nom'],
                    'prenom' => $enseignantData['prenom'],
                    'email' => $enseignantData['email'],
                    'user_id' => $user->id
                ]
            );

            $updateEnseignant[] =$enseignant;
        }
        $enseignantUpdateOrCreate [] = count($updateEnseignant);
        $enseignantRessource = EnseignantResource::collection($updateEnseignant);
        return response()->json([
            'message' => "Enseignant créer ou  mis à jour avec succès",
            'enseignant' => $enseignantRessource,
            'enseignant_update_or_create' => $enseignantUpdateOrCreate
        ], 200);
    }

    /**
     * @param $enseignant_id
     * @return \Illuminate\Http\JsonResponse
     * Récuperation d'un enseignant
     */
    public function getEnseignant($enseignant_id)
    {
        $enseignant = Enseignant::find($enseignant_id);
        if (!$enseignant){
            return response()->json([
                'error' => 'Enseignant non trouvé'
            ], 400);
        }

        $enseignantRessource =  new EnseignantResource($enseignant);
        return response()->json([
            'message' => 'Enseignant récuperer avec succès',
            'enseignant' => $enseignantRessource
        ], 200);
    }

    public function getEnseignants(Request $request){

        $request->validate([
            'perPage' => 'nullable|numeric'
        ]);
        if ($request->perPage){
            $perPage = intval($request->perPage);
        }else{
            $perPage = 3;
        }
        $enseignants = Enseignant::paginate($perPage);

        if (!$enseignants){
            return response()->json([
                'error' => "Aucun enseignant disponible"
            ], 403);
        }

        $enseignantRessource = EnseignantResource::collection($enseignants);
        return response()->json([
            'enseignant' => $enseignantRessource,
            'pagination' => [
                'current_page' => $enseignants->currentPage(),
                'per_page' => $enseignants->perPage(),
                'total' => $enseignants->total(),
                'last_page' => $enseignants->lastPage()
            ]
            ], 200);
    }

    /**
     * @param $enseignant_id
     * @return \Illuminate\Http\JsonResponse
     * Supprimer un enseignant
     */

    public function deleteEnseignant($enseignant_id)
    {
        $enseignant = Enseignant::find($enseignant_id);
        if (!$enseignant){
            return response()->json([
                'error' => 'Aucun enseignant ne correspond'
            ], 400);
        }
        $enseignant->delete();

        return response()->json([
            'message' => 'Enseignant supprimé avec succès'
        ], 200);
    }

    /**
     * @param $seance_id
     * @return \Illuminate\Http\JsonResponse
     * Récuperer les enseignants associer à une séance
     */

    public function getEnseignantAssociateSeance($seance_id){
        $seance = Seance::with('enseignants')->find($seance_id);
        //$enseignants = $seance->enseignants;
        //dd($enseignants);
        return response()->json([
            //'enseignants' => $enseignants,
            'seance' => $seance
        ], 200);
    }

    /**
     * @param $enseignant_id
     * @return \Illuminate\Http\JsonResponse
     * Récupérer un enseignant ainsi que les  séances associer
     */

    public function getSeanceAssociateEnseigne($enseignant_id)
    {
        $enseignant = Enseignant::with('seances')->find($enseignant_id);
        return response()->json([
            'enseignant' => $enseignant
        ],200);
    }


    /**
     * @param $seance_id
     * @param $enseignant_id
     * @return \Illuminate\Http\JsonResponse
     * Supprimer un enseignant associer à une séance
     */
    public function deleteEnseignantSeance($seance_id, $enseignant_id)
    {
        $enseignant = Enseignant::find($enseignant_id);
        $seance = Seance::find($seance_id);

        if (!$seance->enseignants->contains($enseignant)){
            return response()->json([
                    'message' => "L'enseignant n'est pas associer à la séance"
            ],400);
        }
        $seance->enseignants()->detach($enseignant_id);
        return response()->json([
            'message' => "Enseignant supprimer avec succès",
        ],201);
    }

    //Apprenants

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * Création d'un apprenant
     */

    public function createApprenant(Request $request)
    {
        $validateData = $request->validate([
            'nom' => 'required|string',
            'ine' => 'required|string',
            'prenom' => 'required|string',
            'email' => 'required|string|email|max:255|unique:users',
            'id' => 'required|integer'
        ]);

        $sourceName = $request->get('source_name');
        $sourceId = $validateData['id'];

        $user = User::where('email', $validateData['email'])->first();

        if (!$user) {
            $user = User::create([
                'name' => $validateData['prenom'] . ' ' . $validateData['nom'],
                'email' => $validateData['email'],
                'password' => bcrypt('SigneTesSeances2024')
            ]);
        }

        $apprenant = Apprenant::updateOrCreate(
            [
                'source_name' => $sourceName,
                'source_id' => $sourceId
            ],
            [
                'nom' => $validateData['nom'],
                'ine' => $validateData['ine'],
                'prenom' => $validateData['prenom'],
                'email' => $validateData['email'],
                'user_id' => $user->id
            ]
        );

        $apprenantRessource = new ApprenantResource($apprenant);
        return response()->json([
            'message' => 'Apprenant créé ou mis à jour avec succès',
            'apprenant' => $apprenantRessource
        ], 201);
    }


    public function updateApprenant(Request $request)
    {
        $validateData = $request->validate([
            'apprenants' => 'required|array',
            'apprenants.*.nom' => 'required|string',
            'apprenants.*.prenom' => 'required|string',
            'apprenants.*.ine' => 'required|string',
            'apprenants.*.email' => 'required|email',
            'apprenants.*.id' => 'required|integer'
        ]);

        $apprenantIds = collect($validateData['apprenants'])->pluck('id')->toArray();

        $deleteApprenant = Apprenant::whereNotIn('source_id', $apprenantIds)->get();
        foreach ($deleteApprenant as $apprenant){
            $apprenant->seances()->detach();
            $apprenant->delete();
        }

        $updateApprenant = [];

        foreach ($validateData['apprenants'] as $apprenantData){
            $sourceName = $request->get('source_name');
            $sourceId = $apprenantData['id'];

            $user = User::where('email', $apprenantData['email'])->first();

            if (!$user){
                $user = User::create([
                    'name' => $apprenantData['prenom'] . ' ' . $apprenantData['nom'],
                    'email' => $apprenantData['email'],
                    'password' => bcrypt('SigneTesSeance2024')
                ]);
            }
            $apprenant = Apprenant::updateOrCreate(
                [
                    'source_name' => $sourceName,
                    'source_id' => $sourceId
                ],
                [
                    'nom' => $apprenantData['nom'],
                    'prenom' => $apprenantData['prenom'],
                    'ine' => $apprenantData['ine'],
                    'email' => $apprenantData['email'],
                    'user_id' => $user->id
                ]
            );
            $updateApprenant [] = $apprenant;
        }

        $apprenantUpdateOrCreate [] = count($updateApprenant);

        $apprenantRessource = ApprenantResource::collection($updateApprenant);
        return response()->json([
            'message' => "Apprenant créer ou mis à jour avec succès",
            'apprenant' => $apprenantRessource,
            'apprenant_update_or_create' => $apprenantUpdateOrCreate
        ], 200);
    }


    /**
     * @param $apprenant_id
     * @return \Illuminate\Http\JsonResponse
     * Récuperation d'un apprenant
     */

    public function getApprenant($apprenant_id)
    {
        $apprenant = Apprenant::find($apprenant_id);
        //dd($apprenant);
        if (!$apprenant){
            return response()->json([
                'error' => 'Cet Apprenant ne correspond pas '
            ],400);
        }

        $apprenantRessource =  new ApprenantResource($apprenant);

        return response()->json([
            'message' => 'Apprenant récuperer avec succès',
            'apprenant' => $apprenantRessource
        ],201);
    }
    public function getApprenants(Request $request)
    {
        $request->validate([
            'perPage' => 'nullable|numeric'
        ]);
        if ($request->perPage){
            $perPage = intval($request->perPage);
        }else{
            $perPage = 3;
        }
        $apprenants = Apprenant::paginate($perPage);

        if (!$apprenants) {
            return response()->json([
                'error' => "Aucun apprenant disponible"
            ], 404);
        }

        $apprenantRessource = ApprenantResource::collection($apprenants);
        return response()->json([
            'message' => "Liste des apprenants",
            'apprenants' => $apprenantRessource,
            'pagination' => [
                'current_page' => $apprenants->currentPage(),
                'per_page' => $apprenants->perPage(),
                'total' => $apprenants->total(),
                'last_page' => $apprenants->lastPage()
            ]
        ], 200);
    }

    /**
     * @param $seance_id
     * @return \Illuminate\Http\JsonResponse
     * Récuperer une seance et les apprenant associer à une séance
     */

    public function getSeanceAssociateApprenant($seance_id)
    {
        $seance = Seance::with('apprenants')->find($seance_id);

        if (!$seance){
            return response()->json([
                'message' => 'Aucun apprenant associer a la seance'
            ],400);
        }

        return response()->json([
            'message' => 'Apprenants récuperer avec succès',
            'seance' => $seance
        ],201);
    }

    /**
     * @param $apprenant_id
     * @return \Illuminate\Http\JsonResponse
     * Récuperer un apprenant et la seance à laquelle il est associé
     */

    public function getApprenantAssociateSeance($apprenant_id)
    {
        $apprenant = Apprenant::with('seances')->find($apprenant_id);
        return response()->json([
            'message' => 'Apprenant bien récuperer',
            'apprenant'=> $apprenant
        ], 200);
    }

    /**
     * @param $seance_id
     * @param $apprenant_id
     * @return \Illuminate\Http\JsonResponse
     * Supprimer un apprenant associer à une séance
     */

    public function deleteApprenantSeance($seance_id, $apprenant_id)
    {
        $seance = Seance::find($seance_id);
        $apprenant = Apprenant::find($apprenant_id);

        if (!$seance->apprenants->contains($apprenant)){
            return response()->json([
                'error' => "Ce apprenant n'est pas associer à la séance"
            ], 400);
        }

        $seance->apprenants()->detach($apprenant_id);

        return response()->json([
            'message' => "l'apprenant à été supprimer avec succès"
        ], 201);
    }

    public function deleteApprenants($apprenant_id){
        $apprenant = Apprenant::find($apprenant_id);
        if (!$apprenant){
            return response()->json([
                'error' => "Apprenant non trouvé"
            ],403);
        }

        $apprenant->delete();

        return response()->json([
            'message' => "Apprenant supprimer"
        ],200);
    }
}
