<?php

namespace App\Http\Controllers;

use App\Models\Depot;
use App\Models\Produit;
use App\Services\DepotValidationService;
use App\Services\ProduitValidationService;
use Illuminate\Http\Request;

class DepotController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $depots = Depot::searchByName($request->search);
        return view('depot.liste', compact('depots'));
    }

    public function search(Request $request)
    {
        $search = $request->query('query', '');  // Prendre la valeur de "query" ou une chaîne vide si non définie
        $produits = Depot::searchByName($search);

        return response()->json($produits);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $depots = Depot::all();
        $produits = Produit::all();
        return view('depot.ajout', compact('depots', 'produits'));
    }

    protected $depotValidationService;

    public function __construct(DepotValidationService $depotValidationService)
    {
        $this->depotValidationService = $depotValidationService;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $this->depotValidationService->validate($request->all());
        $produit = Produit::find($validatedData['nom']);

        $validatedData['montant'] = $validatedData['qteProduit'] * $produit->prixProduit;
        $validatedData['nom'] = $produit->nom;
        $validatedData['prixProduit'] = $produit->prixProduit;
        $validatedData['prixAchat'] = $produit->prixAchat;

        $existingProduct = Depot::where('nom', $validatedData['nom'])->first();

        if ($existingProduct) {
            notify()->error('Un produit similaire existe déjà.');
            return redirect()->route('depot.index')->withInput();
        }

        Depot::create($validatedData);
        notify()->success('Produit créé avec succès.');
        return redirect()->route('depot.index');
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
        $depot = Depot::find($id);
        return view('depot.modifier', compact('depot'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $produit = Depot::find($id);
        $this->depotValidationService->validate($request->all());

        $produit->nom = $request->input('nom');
        $produit->qteProduit = $request->input('qteProduit');
        $produit->prixProduit = $request->input('prixProduit');
        $produit->prixAchat = $request->input('prixAchat');
        $produit->montant = $request->input('qteProduit') * $request->input('prixProduit');

        $produit->save();

        notify()->success('Produit modifié avec succès.');
        return redirect()->route('depot.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        
        $depot = Depot::find($id);
        if (!$depot) {
            return redirect()->back()->with('error', 'Dépôt introuvable.');
        }

        $depot->delete();

        notify()->success('Dépôt supprimé avec succès.');
        return redirect()->route('depot.index');
    }



}
