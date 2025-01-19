<?php

namespace App\Http\Controllers;

use App\Models\Produit;
use App\Services\ProduitValidationService;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ProduitController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $produits = Produit::searchByName($request->search);
        return view('produit.liste', compact('produits'));
    }

    public function search(Request $request)
    {
        $search = $request->query('query', '');  // Prendre la valeur de "query" ou une chaîne vide si non définie
        $produits = Produit::searchByName($search);

        return response()->json($produits);
    }





    /**
     * Store a newly created resource in storage.
     */
    protected $produitValidationService;

    public function __construct(ProduitValidationService $produitValidationService)
    {
        $this->produitValidationService = $produitValidationService;
    }

    public function store(Request $request)
    {

        $validatedData = $this->produitValidationService->validate($request->all());

        $validatedData['qteDetail'] = $validatedData['qteProduit'] * $validatedData['nombre'];
        $validatedData['montant'] = $validatedData['qteProduit'] * $validatedData['prixProduit'];
        $validatedData['nom'] = strtoupper($validatedData['nom']);
        $validatedData['nomDetail'] = strtoupper($validatedData['nomDetail']);
        $validatedData['nbreVendu'] = $validatedData['nombre'] === null ? null : 0;

        // Vérification de la duplication
        $existingProduct = Produit::where('nom', $validatedData['nom'])->first();

        if ($existingProduct) {
            notify()->error('Un produit similaire existe déjà.');
            return redirect()->route('produit.liste')->withInput();
        }

        Produit::create($validatedData);
        notify()->success('Produit créé avec succès.');
        return redirect()->route('produit.liste');
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
        $produit = Produit::find($id);
        return view('produit.modifier', compact('produit'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $produit = Produit::find($id);
        $this->produitValidationService->validate($request->all());

        $produit->nom = $request->input('nom');
        $produit->qteProduit = $request->input('qteProduit');
        $produit->prixProduit = $request->input('prixProduit');
        $produit->prixAchat = $request->input('prixAchat');
        $produit->nomDetail = $request->input('nomDetail');
        $produit->prixDetail = $request->input('prixDetail');
        $produit->nombre = $request->input('nombre');
        $produit->montant = $request->input('qteProduit') * $request->input('prixProduit');

        $produit->save();

        notify()->success('Produit modifié avec succès.');
        return redirect()->route('produit.liste');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $delete = Produit::find($id);
        $delete->delete();
        notify()->success('Produit supprimé avec succès.');
        return redirect()->route('produit.liste');
    }

    public function restore(string $id)
    {
        $delete = Produit::withTrashed()->find($id);
        $delete->restore();
        notify()->success('Produit restauré avec succès.');
        return redirect()->route('produit.liste');
    }
}
