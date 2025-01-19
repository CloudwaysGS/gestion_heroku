<?php

namespace App\Http\Controllers;

use App\Models\Fournisseur;
use Illuminate\Http\Request;

class FournisseurController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $fournisseurs = Fournisseur::searchByName($request->search ?? '', 10);
        return view('fournisseur.liste', compact('fournisseurs'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('fournisseur.ajout');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'telephone' => 'nullable|string|max:20',
            'adresse' => 'nullable|string',
        ]);
        $validated['nom'] = strtoupper($validated['nom']);

        Fournisseur::create($validated);

        notify()->success('Fournisseur ajouté avec succès.');
        return redirect()->route('fournisseur.index');
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
    public function edit(Fournisseur $fournisseur)
    {
        return view('fournisseur.modifier', compact('fournisseur'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Fournisseur $fournisseur)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'telephone' => 'nullable|string|max:20',
            'adresse' => 'nullable|string',
        ]);

        $fournisseur->update($validated);

        notify()->success('Fournisseur mis à jour avec succès.');
        return redirect()->route('fournisseur.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Fournisseur $fournisseur)
    {
        $fournisseur->delete();

        notify()->success('Fournisseur supprimé avec succès.');
        return redirect()->route('fournisseur.index');
    }
}
