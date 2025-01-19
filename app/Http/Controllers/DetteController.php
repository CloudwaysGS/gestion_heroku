<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Dette;
use App\Services\DetteValidationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DetteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        
        $dette = Dette::searchByName($request->search ?? '');
        return view('dette.liste', compact('dette'));
    }

    public function searchAjax(Request $request)
    {
        try {
            $query = $request->query('query', '');
            $page = max(1, (int) $request->query('page', 1)); // Garantir une page minimum de 1
            $size = max(1, (int) $request->query('size', 5)); // Garantir une taille minimum de 1

            $detteQuery = Dette::query();

            if ($query) {
                $detteQuery->where('nom', 'like', '%' . $query . '%');
            }

            $total = $detteQuery->count();
            $items = $detteQuery->orderBy('etat', 'asc')
                ->orderBy('created_at', 'desc')
                ->skip(($page - 1) * $size)
                ->take($size)
                ->get();

            return response()->json([
                'items' => $items,
                'total' => $total,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Une erreur est survenue lors de la récupération des données.',
            ], 500);
        }
    }



    protected $detteValidationService;

    public function __construct(DetteValidationService $detteValidationService)
    {
        $this->detteValidationService = $detteValidationService;
    }

    public function store(Request $request)
    {

            $validatedData = $this->detteValidationService->validate($request->all());
            $client = Client::findOrFail($validatedData['client_id']);

            Dette::create([
                'client_id' => $validatedData['client_id'],
                'nom' => $client['nom'],
                'montant' => $validatedData['montant'],
                'reste' => $validatedData['montant'],
                'commentaire' => $validatedData['commentaire'],
                'etat' => 'impayée',
            ]);

            notify()->success('Dette créée avec succès.');
            return redirect()->route('dette.liste');
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $dette = Dette::findOrFail($id);
        return view('dette.détail', compact('dette'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $dette = Dette::find($id);
        return view('dette.modifier', compact('dette'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $dette = Dette::find($id);

        $dette->nom = $request->input('nom');
        $dette->montant = $request->input('reste');
        $dette->reste = $request->input('reste');
        $dette->commentaire = $request->input('commentaire');

        $dette->save();

        notify()->success('Dette modifié avec succès.');
        return redirect()->route('dette.liste');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $dette = Dette::find($id);
        if (!$dette) {
            notify()->error('Dette introuvable.');
            return redirect()->route('dette.liste');
        }

        if ($dette->etat === 'payée') {
            $dette->delete();
            notify()->success('Dette supprimée avec succès.');
        } else {
            notify()->error('Impossible de supprimer une dette non payée.');
        }

        return redirect()->route('dette.liste');

    }
}
