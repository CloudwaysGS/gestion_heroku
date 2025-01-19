<?php

namespace App\Http\Controllers;

use App\Models\DetteFournisseur;
use App\Models\PaidFournisseur;
use App\Services\PaiementValidationService;
use Illuminate\Http\Request;

class PaidFournisseurController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $paiement = PaidFournisseur::all();

        return view('paiement.liste', compact('paiement'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(string $id)
    {
        $paidfournisseur = DetteFournisseur::find($id);

        return view('fournisseur_paid.ajout', compact('paidfournisseur','id'));
    }

    protected $paiementValidationService;

    public function __construct(PaiementValidationService $paiementValidationService)
    {
        $this->paiementValidationService = $paiementValidationService;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validation des données
        $validatedData = $this->paiementValidationService->validate($request->all());

        // Récupération de la dette
        $dette = DetteFournisseur::findOrFail($validatedData['id']); // Utilisez `findOrFail` pour éviter les erreurs silencieuses

        if (!isset($validatedData['montant']) || $validatedData['montant'] <= 0) {
            return back()->withErrors('Le montant est invalide.')->withInput();
        }

        if ($dette->reste <= 0) {
            notify()->error('Dette déjà payée.');
            return redirect()->route('dettefournisseur.index');
        }
        $reste = $dette->reste - $validatedData['montant'];

        if ($reste < 0) {

            // L'état de la dette devient "payée"
            $dette->update([
                'reste' => 0, // La dette est soldée
                'etat' => 'payée',
                'depot' => abs($reste), // Dépôt excédentaire
            ]);
            $client = $dette->client()->first();
            $validatedData['nom'] = $client ? $client->nom : 'Client inconnu';
            // Création du paiement avec des données cohérentes
            PaidFournisseur::create([
                'montant' => $validatedData['montant'],
                'reste' => 0, // Paiement couvre tout le reste
                'fournisseur_id' => $dette->id,
                'nom' => $validatedData['nom'],
            ]);

            notify()->success('Paiement effectué avec succès.');
            return redirect()->route('dettefournisseur.index');
        }

        if ($reste == 0) {
            $dette->etat = 'payée';
        }
        // Mise à jour de la dette
        $dette->update([
            'reste' => $reste,
            'etat' => $dette->etat,
        ]);

        PaidFournisseur::create([
            'montant' => $validatedData['montant'],
            'reste' => $reste,
            'fournisseur_id' => $dette->id,
            'nom' => $dette->nom,
            'reste' =>$dette->reste,
        ]);

        // Notification et redirection
        notify()->success('Paiement effectué avec succès.');
        return redirect()->route('dettefournisseur.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
