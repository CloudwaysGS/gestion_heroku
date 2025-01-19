<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Services\ClientValidationService;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        $search = $request->input('search');
        $query = Client::query();

        if (!empty($search)) {
            // Filtrer les clients en fonction du nom
            $query->where('nom', 'like', '%' . $search . '%');
        }

        // Pagination avec 10 résultats par page
        $clients = $query->orderBy('created_at', 'desc')->paginate(10);

        // Vérifier si la requête est AJAX
        if ($request->ajax()) {
            return view('client.search_results', compact('clients'))->render();
        }


        // Si ce n'est pas une requête AJAX, retourner la vue complète
        return view('client.liste', compact('clients'));
    }

    protected $clientValidationService;

    public function __construct(ClientValidationService $clientValidationService)
    {
        $this->clientValidationService = $clientValidationService;
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $this->clientValidationService->validate($request->all());
        $validatedData['nom'] = strtoupper($validatedData['nom']);
        Client::create($validatedData);
        notify()->success('Client créé avec succès.');
        return redirect()->route('client.liste');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {
        $client = Client::find($id);
        return view('client.modifier', compact('client'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $client = Client::find($id);
        $this->clientValidationService->validate($request->all());

        $client->nom = $request->input('nom');
        $client->telephone = $request->input('telephone');
        $client->adresse = $request->input('adresse');
        $client->save();

        notify()->success('Client modifié avec succès.');
        return redirect()->route('client.liste');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $client = Client::find($id);

        if (!$client) {
            notify()->error('Client introuvable.');
            return redirect()->route('client.liste');
        }

        $client->delete();
        notify()->success('Client supprimé avec succès.');
        return redirect()->route('client.liste');
    }

}
