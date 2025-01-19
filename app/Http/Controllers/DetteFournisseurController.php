<?php

namespace App\Http\Controllers;

use App\Models\DetteFournisseur;
use App\Models\Fournisseur;
use Illuminate\Http\Request;

class DetteFournisseurController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = DetteFournisseur::query();

        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where('nom', 'like', "%{$search}%");
        }

        $dettes = $query->orderBy('created_at', 'desc')->paginate(10);
        
        return view('detteFournisseur.liste', compact('dettes'));
    }


    /*public function searchAjax(Request $request)
    {
        try {
            $query = $request->query('query', '');
            $page = max(1, (int)$request->query('page', 1));
            $size = max(1, (int)$request->query('size', 5));

            $detteQuery = DetteFournisseur::query();

            if ($query) {
                $detteQuery->where('nom', 'like', '%' . $query . '%');
            }

            $total = $detteQuery->count();
            $items = $detteQuery->orderBy('created_at', 'desc')
                ->skip(($page - 1) * $size)
                ->take($size)
                ->get();

            return response()->json([
                'items' => $items,
                'total' => $total,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Une erreur est survenue : ' . $e->getMessage(),
            ], 500);
        }
    }*/


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $fournisseurs = Fournisseur::all();
        return view('detteFournisseur.ajout', compact('fournisseurs'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $validatedData = $request->validate([
            'fournisseur_id' => 'required|exists:fournisseurs,id',
            'montant' => 'required|numeric|min:0',
        ]);

        // Créer une nouvelle dette
        $fournisseur = Fournisseur::findOrFail($validatedData['fournisseur_id']);

        DetteFournisseur::create([
            'fournisseur_id' => $validatedData['fournisseur_id'],
            'nom' => $fournisseur['nom'],
            'montant' => $validatedData['montant'],
            'reste' => $validatedData['montant'],
            'commentaire' => $request['commentaire'],
            'etat' => 'impayée',
        ]);

        notify()->success('success', 'Dette ajoutée avec succès.');
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
        $dettefournisseur = DetteFournisseur::find($id);
        return view('detteFournisseur.modifier', compact('dettefournisseur'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $dette = DetteFournisseur::find($id);

        $dette->nom = $request->input('nom');
        $dette->montant = $request->input('reste');
        $dette->reste = $request->input('reste');
        $dette->commentaire = $request->input('commentaire');

        $dette->save();

        notify()->success('Dette modifié avec succès.');
        return redirect()->route('dettefournisseur.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {

        $dette = DetteFournisseur::find($id);
        if (!$dette) {
            notify()->error('Dette introuvable.');
            return redirect()->route('dettefournisseur.index');
        }

        if ($dette->etat === 'payée') {
            $dette->delete();
            notify()->success('Dette supprimée avec succès.');
        } else {
            notify()->error('Impossible de supprimer une dette non payée.');
        }

        return redirect()->route('dettefournisseur.index');
    }
}
