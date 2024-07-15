<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
<<<<<<< HEAD
use App\Http\Resources\ApprenantResource;
use App\Http\Resources\EmargementResource;
use App\Http\Resources\EnseignantResource;
use App\Http\Resources\SeanceDetailResource;
use App\Http\Resources\SeanceResource;
use App\Models\Apprenant;
use App\Models\Emargement;
use App\Models\Enseignant;
use App\Models\Seance;
use http\Env\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
=======
use App\Models\Seance;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
>>>>>>> 491d98493b85d953d9f9ccb5fab06146e34ef305

class SeanceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
<<<<<<< HEAD
     * @return \Illuminate\Http\JsonResponse
     */


    public function createSeanceWithApprenantAndEnseignant(Request $request)
    {
        $validatedData = $request->validate([
            'intitule' => 'required|string',
            'matiere_id' => 'required|integer',
            'date_debut' => 'required|date',
            'date_fin' => 'required|date',
            'duration' => 'required|integer',
            'seance_id_externe' => 'required|integer',
            'apprenants' => 'required|array|min:1',
            'apprenants.*' => 'integer|exists:apprenants,id',
            'enseignant' => 'required|integer|exists:enseignants,id',
        ]);

        $dateDebut = Carbon::parse($validatedData['date_debut'])->timestamp;
        $dateFin = Carbon::parse($validatedData['date_fin'])->timestamp;

        $existSeance = Seance::where([
            'intitule' => $validatedData['intitule'],
            'matiere_id' => $validatedData['matiere_id'],
            'dabte_debut' => $dateDebut,
            'date_fin' => $dateFin
        ])->first();

        if ($existSeance) {
            return response()->json([
                'error' => 'cett séance avec existe déjà.'
            ], 400);
        }

        $seanceData = [
            'intitule' => $validatedData['intitule'],
            'matiere_id' => $validatedData['matiere_id'],
            'duration' => $validatedData['duration'],
            'seance_id_externe' => $validatedData['seance_id_externe'],
            'dabte_debut' => $dateDebut,
            'date_fin' => $dateFin
        ];

        $seance = Seance::updateOrCreate($seanceData);

        $enseignant = Enseignant::find($validatedData['enseignant']);
        if (!$enseignant) {
            return response()->json([
                'error' => 'Enseignant non trouvé.'
            ], 404);
        }
        if ($seance->enseignant()->where('enseignant_id', $enseignant->id)->exists()) {
            return response()->json([
                'error' => 'L\'enseignant est déjà lié à cette séance.'
            ], 400);
        }
        $seance->enseignant()->attach($enseignant);


        $apprenants = Apprenant::find($validatedData['apprenants']);
        if ($apprenants->count() != count($validatedData['apprenants'])) {
            return response()->json([
                'error' => 'Certains apprenants n\'ont pas été trouvés.'
            ], 404);
        }

        foreach ($apprenants as $apprenant) {
            if ($seance->apprenants()->where('apprenant_id', $apprenant->id)->exists()) {
                return response()->json([
                    'error' => 'L\'apprenant ' . $apprenant->id . ' est déjà lié à cette séance.'
                ], 400);
            }
        }
        $seance->apprenants()->sync($apprenants->pluck('id')->toArray());

        $seanceResource = new SeanceResource($seance);
        return response()->json([
            'message' => 'Séance créée avec succès',
            'seance' => $seanceResource
        ], 201);
    }

    public function createSeances(Request $request)
    {
        $validateData = $request->validate([
            'date_debut' => 'required|date',
            'date_fin' => 'required|date|after_or_equal:date_debut',
            'seance.*.intitule' => 'required|string',
            'seance.*.matiere_id' => 'required|integer',
            'seance.*.date_debut' => 'required|date',
            'seance.*.date_fin' => 'required|date|after_or_equal:seance.*.date_debut',
            'seance.*.duration' => 'required|integer',
            'seance.*.seance_id_externe' => 'required|integer',
            'apprenants' => 'required|array|min:1',
            'apprenants.*' => 'integer|exists:apprenants,id',
            'enseignant' => 'required|integer|exists:enseignants,id',
        ]);

        $dateDebutJour = Carbon::parse($validateData['date_debut']);
        $dateFinJour = Carbon::parse($validateData['date_fin']);

        foreach ($validateData['seance'] as $seanceData) {
            $dateDebut = Carbon::parse($seanceData['date_debut'])->timestamp;
            $dateFin = Carbon::parse($seanceData['date_fin'])->timestamp;

            $existSeance = Seance::where([
                'intitule' => $seanceData['intitule'],
                'matiere_id' => $seanceData['matiere_id'],
                'dabte_debut' => $dateDebutJour,
                'date_fin' => $dateFinJour,
                'duration' => $seanceData['duration'],
                'seance_id_externe' => $seanceData['seance_id_externe']
            ])->first();

            if ($existSeance) {
                return response()->json([
                    'error' => 'Cette une ou plusieurs séance(s) existe(nt) déjà'
                ], 400);
            }

            $seance = Seance::updateOrCreate([
                'intitule' => $seanceData['intitule'],
                'matiere_id' => $seanceData['matiere_id'],
                'dabte_debut' => $dateDebut,
                'date_fin' => $dateFin,
                'duration' => $seanceData['duration'],
                'seance_id_externe' => $seanceData['seance_id_externe']
            ]);

            $enseignant = Enseignant::find($validateData['enseignant']);
            if (!$enseignant) {
                return response()->json([
                    'error' => 'Enseignant non trouvé.'
                ], 404);
            }

            if ($seance->enseignant()->where('enseignant_id', $enseignant->id)->exists()) {
                return response()->json([
                    'error' => 'L\'enseignant est déjà lié à cette séance.'
                ], 400);
            }

            $seance->enseignant()->attach($enseignant);

            $apprenants = Apprenant::find($validateData['apprenants']);
            if ($apprenants->count() != count($validateData['apprenants'])) {
                return response()->json([
                    'error' => 'Certains apprenants n\'ont pas été trouvés.'
                ], 404);
            }

            foreach ($apprenants as $apprenant) {
                if ($seance->apprenants()->where('apprenant_id', $apprenant->id)->exists()) {
                    return response()->json([
                        'error' => 'L\'apprenant ' . $apprenant->id . ' est déjà lié à cette séance.'
                    ], 400);
                }
            }

            $seance->apprenants()->sync($apprenants->pluck('id')->toArray());
        }

        return response()->json(['message' => 'Séances créées avec succès.']);
    }

    public function deleteSeance($seance_id){
        $seance = Seance::find($seance_id);
        if (!$seance){
            return response()->json(['error' => 'séance inconnu'], 400);
        }
        $seance->enseignant()->detach();
        $seance->delete();

        return response()->json(['message' => 'Séance supprimé avec succès']);
    }

    public function getApprenantSeances($apprenant_id)
    {
        $apprenant = Apprenant::with('seances')->find($apprenant_id);
        return response()->json([
            'apprenant' => $apprenant
        ]);
    }

    public function getEnseignantSeances($enseignant_id)
    {
        $enseignant = Enseignant::with('seances')->find($enseignant_id);
        return response()->json([
            'enseignant' => $enseignant,
        ]);
    }

    public function getEnseignantSeanceApprenant(Request $request)
    {
        $validateData = $request->validate([
            'date_debut' => 'required|date',
            'date_fin' => 'required|date'
        ]);

        $dateDebut = Carbon::parse($validateData['date_debut'])->timestamp;
        $dateFin = Carbon::parse($validateData['date_fin'])->timestamp;

        if ($dateDebut > $dateFin) {
            return response()->json(['error' => 'La date de début ne peut pas être après la date de fin.'], 400);
        }

        $seancesFiltrees = Seance::where(function ($query) use ($dateDebut, $dateFin) {
            $query->whereBetween('dabte_debut', [$dateDebut, $dateFin]);
        })->get();

        if ($seancesFiltrees->isEmpty()) {
            return response()->json(['error' => 'Aucune séance ne correspond aux dates fournies.'], 404);
        }

        return response()->json($seancesFiltrees);
    }

    public function getEnseignantSeance($enseignant_id, $seance_id)
    {
        $enseignant = Enseignant::with(['seances' => function ($query) use ($seance_id) {
            $query->where('seances.id', $seance_id);
        }])->findOrFail($enseignant_id);

        return response()->json([
            'enseignant' => $enseignant,
        ]);
    }

    public function getApprenantSeance($apprenant_id, $seance_id)
    {
        $apprenant = Apprenant::with(['seances' => function ($query) use ($seance_id) {
            $query->where('seances.id', $seance_id);
        }])->findOrFail($apprenant_id);

        return response()->json([
            'apprenant' => $apprenant
        ]);
    }

    public function createEnseignantEmargement(Request $request, $enseignant_id, $seance_id)
    {
        $enseignant = Enseignant::findOrFail($enseignant_id);
        $seance = Seance::findOrFail($seance_id);

        $validateData = $request->validate([
            'date_emargement' => 'required|date',
            'statut_presence' => 'required|boolean'
        ]);

        $dateEmargement = Carbon::parse($validateData['date_emargement'])->timestamp;

        $emargement = Emargement::create([
            'seance_id' => $seance->id,
            'objet_type' => Enseignant::class,
            'objet_id' => $enseignant->id,
            'date_emargement' => $dateEmargement,
            'statut_presence' => $validateData['statut_presence']
        ]);

        $enseignant->seances()->attach($seance->id, ['emargement_id' => $emargement->id]);
        $emargement = new EmargementResource($emargement);

        return response()->json(['emargement' => $emargement], 201);
    }

    public function creatApprenantEmargment(Request $request, $apprenant_id, $seance_id)
    {
        $apprenant = Apprenant::findOrFail($apprenant_id);
        $seance = Seance::findOrFail($seance_id);

        $validateData = $request->validate([
            'date_emargement' => 'required|date',
            'statut_presence' => 'required|boolean'
        ]);

        $dateEmargement = Carbon::parse($validateData['date_emargement'])->timestamp;

        $emargement = Emargement::create([
            'seance_id' => $seance->id,
            'objet_type' => Apprenant::class,
            'objet_id' => $apprenant->id,
            'date_emargement' => $dateEmargement,
            'statut_presence' => $validateData['statut_presence']
        ]);

        $apprenant->seances()->attach($seance->id, ['emargement_id' => $emargement->id]);
        $emargement = new EmargementResource($emargement);
        return response()->json(['emargement' => $emargement], 201);
    }

    public function getEnseignantSeanceEmargement($enseignant_id)
    {
        $enseignant = Enseignant::with('seances')->find($enseignant_id);
        return response()->json([
            'enseignant' => new EnseignantResource($enseignant),
        ]);
    }

    public function getApprenantSeanceEmargement($apprenant_id)
    {
        $apprenant = Apprenant::with('seances.pivot.emargement')->find($apprenant_id);
        return response()->json(new ApprenantResource($apprenant));
    }

    public function getSeance($id)
    {
        $seance = Seance::with(['enseignant', 'apprenants'])->findOrFail($id);
        if (!$seance) {
            return response()->json(['error' => 'Séance non trouvée.'], 404);
        }
        $seances = new SeanceDetailResource($seance);
        return response()->json(['seance' => $seances]);
    }

    public function getSeances()
    {
        $seances = Seance::with(['apprenants', 'enseignant'])->get();
        if (!$seances) {
            return response()->json(['error' => 'Séance non trouvée.'], 404);
        }
        $seances = SeanceResource::collection($seances);
        return response()->json(['seances' => $seances]);
    }


    public function createEnseignant(Request $request)
    {
        $validateData = $request->validate([
            'nom' => 'required|string',
            'prenom' => 'required|string',
            'email' => 'required|string'
        ]);


    }


=======
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'intitule' => 'required|string|max:255',
            'dabte_debut' => 'required',
            'date_fin' => 'required',
        ]);

        $dateDebut = Carbon::parse($request->date_debut)->timestamp;
        $dateFin = Carbon::parse($request->date_fin)->timestamp;

        $seance = Seance::create([
            'intitule' => $request->intitule,
            'dabte_debut' => $dateDebut,
            'date_fin' => $dateFin
        ]);
        return response()->json(['message' => 'seance creer avec succès', 'seance' => $seance],201);
    }

>>>>>>> 491d98493b85d953d9f9ccb5fab06146e34ef305
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
