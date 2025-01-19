<?php

namespace App\Http\Controllers;

use App\Models\Produit;
use App\Models\Sortie;
use App\Services\SortieValidationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SortieController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sortie = Sortie::orderBy('created_at', 'desc')->get();
        return view('sortie.liste', compact('sortie'));
    }

    protected $sortieValidationService;

    public function __construct(SortieValidationService $sortieValidationService)
    {
        $this->sortieValidationService = $sortieValidationService;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validation des données
        $validatedData = $this->sortieValidationService->validate($request->all());

        // Recherche du produit par son ID (assurez-vous que 'nom' est un ID valide)
        $produit = Produit::find($validatedData['nom']); // Utilisation de `find` pour simplifier la recherche
        if (!$produit) {
            return redirect()->back()->withErrors(['nom' => 'Produit introuvable']);
        }

        // Vérifier que la quantité en stock est suffisante pour la sortie
        if ($produit->qteProduit < $validatedData['qteSortie']) {
            return redirect()->back()->withErrors(['qteSortie' => 'La quantité demandée dépasse le stock disponible.']);
        }

        // Mise à jour de la quantité du produit (décroissance)
        $produit->decrement('qteProduit', $validatedData['qteSortie']);

        // Calcul du total et création de la sortie
        Sortie::create([
            'produit_id' => $produit->id,  // Utilisation correcte de l'ID du produit
            'qteSortie' => $validatedData['qteSortie'],
            'prix' => $validatedData['prix'],
            'total' => $validatedData['qteSortie'] * $validatedData['prix'],
            'nomProduit' => $produit->nom,
        ]);

        // Redirection avec notification de succès
        notify()->success('Sortie créée avec succès.');
        return redirect()->route('sortie.liste');  // Assurez-vous que la route 'sortie.liste' existe
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
        $sortie = Sortie::find($id);
        return view('sortie.modifier', compact('sortie'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {

        try {
            // Validation des données
            $validatedData = $this->sortieValidationService->validateEdit($request->all());
            // Mise à jour via le service
            $this->sortieValidationService->updateSortie($validatedData, $id);

            notify()->success('Sortie modifiée avec succès.');
            return redirect()->route('sortie.liste');
        }catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $delete = Sortie::find($id);
        $delete->delete();
        notify()->success('entree supprimé avec succès.');
        return redirect()->route('sortie.liste');
    }
}
