<?php

namespace App\Http\Controllers;

use App\Models\RemoveDepot;
use Illuminate\Http\Request;
use App\Services\EntreeDepotValidationService;
use App\Models\Depot;

class RemoveDepController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = RemoveDepot::query();

        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where('nom', 'like', "%{$search}%");
        }

        $depots = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('depot.sortieDepot', compact('depots'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $depots = RemoveDepot::all();
        $produits = Depot::all();
        return view('depot.ajout_sortie', compact('depots', 'produits'));
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
        
        $validatedData = $this->entreeDepotValidationService->validateSortie($request->all());

        $produit = Depot::find($validatedData['nom']);
        
        if (!$produit) {
            return redirect()->back()->withErrors(['nom' => 'Produit introuvable']);
        }

        $produit->decrement('qteProduit', $validatedData['qteSortie']);

        RemoveDepot::create([
            'depot_id' => $produit->id, // Utilisation directe de l'ID du produit
            'qteSortie' => $validatedData['qteSortie'],
            'prix' => $produit->prixProduit,
            'total' => $validatedData['qteSortie'] * $produit->prixProduit,
            'nomProduit' => $produit->nom,
        ]);

        notify()->success('Sortie créée avec succès.');
        return redirect()->route('depot_sortie.index');
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
        $depot = RemoveDepot::find($id);
        return view('depot.editSortieDepot', compact('depot'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {

        $produit = RemoveDepot::find($id);
        
        $qteInitiale = $produit->qteSortie;
        $qteNouvelle = $request->input('qteSortie');
        $diffQte = $qteNouvelle - $qteInitiale;
        
        $this->entreeDepotValidationService->validateSortie($request->all());

        $depot = Depot::find($produit->depot_id);
        
        if ($diffQte > 0) {
        
            $depot->decrement('qteProduit', $diffQte);
        } elseif ($diffQte < 0) {

            $depot->increment('qteProduit', abs($diffQte));
        }else{
            notify()->success('Produit modifié avec succès.');
            return redirect()->route('depot_sortie.index');
        }

        $produit->update([
            'nomProduit' => $request->input('nom'),
            'qteSortie' => $qteNouvelle,
            'prix' => $request->input('prix'),
            'total' => $qteNouvelle * $request->input('prix'),
        ]);

        $produit->save();

        notify()->success('Produit modifié avec succès.');
        return redirect()->route('depot_sortie.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $depot = RemoveDepot::find($id);

        if (!$depot) {
            return redirect()->back()->with('error', 'Dépôt introuvable.');
        }
        $produit = Depot::find($depot->depot_id);

        $depot->delete();

        $produit->increment('qteProduit', $depot->qteSortie);
        $produit->save();

        notify()->success('Produit supprimé avec succès.');
        return redirect()->route('depot_sortie.index');
    }
}
