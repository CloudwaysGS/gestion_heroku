<?php

namespace App\Http\Controllers;

use App\Models\AddDepot;
use App\Models\Depot;
use App\Models\Produit;
use App\Services\EntreeDepotValidationService;
use Illuminate\Http\Request;

class AddepotController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = AddDepot::query();

        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where('nom', 'like', "%{$search}%");
        }

        $depots = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('depot.entree', compact('depots'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $depots = AddDepot::all();
        $produits = Depot::all();
        return view('depot.ajout_entree', compact('depots', 'produits'));
    }

    protected $entreeDepotValidationService;

    public function __construct(EntreeDepotValidationService $entreeDepotValidationService)
    {
        $this->entreeDepotValidationService = $entreeDepotValidationService;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        
        $validatedData = $this->entreeDepotValidationService->validate($request->all());

        $produit = Depot::find($validatedData['nom']); 
        
        if (!$produit) {
            return redirect()->back()->withErrors(['nom' => 'Produit introuvable']);
        }

        $produit->increment('qteProduit', $validatedData['qteEntree']);

        AddDepot::create([
            'depot_id' => $produit->id, // Utilisation directe de l'ID du produit
            'qteEntree' => $validatedData['qteEntree'],
            'prix' => $produit->prixProduit,
            'total' => $validatedData['qteEntree'] * $produit->prixProduit,
            'nomProduit' => $produit->nom,
        ]);

        notify()->success('Entrée créée avec succès.');
        return redirect()->route('depot_entree.index');
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
        $depot = AddDepot::find($id);
        
        return view('depot.editEntreeDepot', compact('depot'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        
        $produit = AddDepot::find($id);

        $qteInitiale = $produit->qteEntree;
        $qteNouvelle = $request->input('qteEntree');
        $diffQte = $qteNouvelle - $qteInitiale;

        $this->entreeDepotValidationService->validate($request->all());

        $produit->update([
            'nomProduit' => $request->input('nom'),
            'qteEntree' => $qteNouvelle,
            'prix' => $request->input('prix'),
            'total' => $qteNouvelle * $request->input('prix'),
        ]);

        $depot = Depot::find($produit->depot_id);
        $depot->increment('qteProduit', $diffQte);

        $produit->save();

        notify()->success('Produit modifié avec succès.');
        return redirect()->route('depot_entree.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        
        $depot = AddDepot::find($id);
        
        if (!$depot) {
            return redirect()->back()->with('error', 'Dépôt introuvable.');
        }
        $produit = Depot::find($depot->depot_id);
        
        $depot->delete();

        $produit->decrement('qteProduit', $depot->qteEntree);
        $produit->save();

        notify()->success('Produit supprimé avec succès.');
        return redirect()->route('depot_entree.index');
    }
}
