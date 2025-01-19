<?php

namespace App\Http\Controllers;

use App\Models\Entree;
use App\Models\Produit;
use App\Services\EntreeValidationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EntreeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $entree = Entree::orderBy('created_at', 'desc')->get();
        return view('entree.liste', compact('entree'));
    }

    protected $entreeValidationService;

    public function __construct(EntreeValidationService $entreeValidationService)
    {
        $this->entreeValidationService = $entreeValidationService;
    }
        /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validation des données
        $validatedData = $this->entreeValidationService->validate($request->all());

        // Recherche du produit
        $produit = Produit::find($validatedData['nom']); // Utilisation de `find` pour simplifier la recherche
        if (!$produit) {
            return redirect()->back()->withErrors(['nom' => 'Produit introuvable']);
        }

        // Mise à jour de la quantité du produit
        $produit->increment('qteProduit', $validatedData['qteEntree']);

        // Calcul du total et création de l'entrée
        Entree::create([
            'produit_id' => $produit->id, // Utilisation directe de l'ID du produit
            'qteEntree' => $validatedData['qteEntree'],
            'prix' => $validatedData['prix'],
            'total' => $validatedData['qteEntree'] * $validatedData['prix'],
            'nomProduit' => $produit->nom,
        ]);

        // Redirection avec notification de succès
        notify()->success('Entrée créée avec succès.');
        return redirect()->route('entree.liste');
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
        $entree = Entree::find($id);
        return view('entree.modifier', compact('entree'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Recherche de l'entrée
        $entree = Entree::find($id);

        if (!$entree) {
            return redirect()->back()->withErrors(['id' => 'Entrée introuvable.']);
        }

        // Validation des données de la requête
        $validatedData = $this->entreeValidationService->validateEdit($request->all());

        // Recherche du produit associé
        $produit = Produit::find($validatedData['produit_id']);
        if (!$produit) {
            return redirect()->back()->withErrors(['produit_id' => 'Produit introuvable.']);
        }

        // Variables pour les calculs
        $qteInitiale = $entree->qteEntree;
        $qteNouvelle = $validatedData['qteEntree'];
        $diffQte = $qteNouvelle - $qteInitiale;

        // Utilisation d'une transaction pour garantir la cohérence des modifications
        DB::transaction(function () use ($entree, $produit, $validatedData, $diffQte, $qteNouvelle) {
            // Mise à jour des valeurs de l'entrée
            $entree->qteEntree = $qteNouvelle;
            $entree->prix = $validatedData['prix'];

            // Mise à jour des quantités du produit
            $produit->qteProduit += $diffQte;

            if ($produit->qteProduit + $diffQte < 0) {
                throw new \Exception('Stock insuffisant pour effectuer cette mise à jour.');
            }

            $entree->save();
            $produit->save();
        });

        notify()->success('Entrée modifiée avec succès.');
        return redirect()->route('entree.liste');
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $delete = Entree::find($id);
        $delete->delete();
        notify()->success('entree supprimé avec succès.');
        return redirect()->route('entree.liste');
    }

}
