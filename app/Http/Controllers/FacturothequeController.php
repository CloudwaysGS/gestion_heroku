<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Dette;
use App\Models\Facture;
use App\Models\Facturotheque;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FacturothequeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $mesfactures = Facturotheque::searchByName($request->search ?? '');
        
        return view('facturotheque.liste', compact('mesfactures'));
    }

    public function searchAjax(Request $request)
    {
        $query = $request->query('query', '');
        $page = $request->query('page', 1);
        $size = $request->query('size', 5);

        $facturesQuery = Facturotheque::query();

        if ($query) {
            $facturesQuery->where('nomCient', 'like', '%' . $query . '%');
        }

        $total = $facturesQuery->count();
        $items = $facturesQuery->orderBy('created_at', 'desc')
            ->skip(($page - 1) * $size)
            ->take($size)
            ->get();

        return response()->json([
            'items' => $items,
            'total' => $total,
        ]);
    }




    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

        $factures = Facture::where('etat', 1)->with('client')->get();
        if ($factures->isEmpty()) {
            notify()->info('Aucune facture à traiter.');
            return redirect()->route('facture.liste');
        }

        $count = $factures->count();
        $totalMontants = $factures->sum('montant');

        $firstClient = $factures->first()->client;

        $clientNom = $firstClient ? $firstClient->nom : 'Inconnu';
        $adresse = $firstClient ? $firstClient->adresse : 'Non spécifiée';
        $telephone = $firstClient ? $firstClient->telephone : 'Non spécifié';

        // Générer une référence de facture unique
        $nextId = Facture::max('id') + 1;
        $reference = 'FACT-' . now()->format('Ymd') . '-' . str_pad($nextId, 5, '0', STR_PAD_LEFT);

        DB::beginTransaction();
        try {
            $facturotheque = Facturotheque::create([
                'nbreLigne' => $count,
                'nomCient' => $clientNom,
                'total' => $totalMontants,
                'reste' => $totalMontants,
                'adresse' => $adresse,
                'telephone' => $telephone,
                'numFacture' => $reference,
                'etat' => 'en cours',
            ]);

            $facturothequeId = $facturotheque->id;

            // Ajouter l'ID à chaque facture et mettre à jour leur état
            foreach ($factures as $facture) {
                $facture->update([
                    'etat' => 0,
                    'facturotheque_id' => $facturothequeId,
                ]);
            }

            DB::commit();

            notify()->success('Succès : Factures traitées.');
            return redirect()->route('facturotheque.index');
        } catch (\Exception $e) {
            DB::rollBack();
            notify()->error('Une erreur est survenue : ' . $e->getMessage());
            return back()->withErrors('Une erreur est survenue : ' . $e->getMessage());
        }
    }

    /**
     * payer a newly created resource in storage.
     */
    public function payer(string $id)
    {
        $payer = Facturotheque::find($id);

        // Vérifier si la facture existe
        if (!$payer) {
            notify()->error('Facture introuvable.');
            return redirect()->back();
        }

        try {
            DB::transaction(function () use ($payer) {
                // Mettre à jour l'état de la facture
                $payer->update([
                    'etat' => 'payée',  // État de la facture
                    'avance' => null,   // Avance mise à null
                    'reste' => 0,       // Reste à payer à 0
                ]);

                // Rechercher la dette liée à cette facture
                $dette = Dette::where('facturotheque_id', $payer->id)->first();

                // Vérifier si une dette est associée
                if ($dette) {
                    $dette->update([
                        'etat' => 'payée',         // État de la dette
                        'reste' => 0,             // Reste à payer à 0
                        'depot' => $payer->depot, // Mettre à jour le dépôt
                    ]);
                } else {
                    // Ajouter une notification ou une action alternative
                    notify()->info('Aucune dette associée à cette facture.');
                    return redirect()->route('facturotheque.index');
                }
            });

            notify()->success('Facture payée avec succès.');
            return redirect()->route('facturotheque.index');
        } catch (\Exception $e) {
            // Gestion des erreurs en cas d'échec de la transaction
            notify()->error('Une erreur est survenue lors du paiement de la facture.');
            return redirect()->back();
        }
    }




   
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // Vérifier si des factures sont actuellement verrouillées (état à 1)
        $facturesActives = Facture::where('etat', 1)->exists();

        if ($facturesActives) {
            notify()->info('Une ou plusieurs factures sont actuellement verrouillées.');
            return redirect()->route('facturotheque.index');
        }

        $facturotheque = Facturotheque::find($id);

        if (!$facturotheque) {
            notify()->error('La Facturothèque demandée est introuvable.');
            return redirect()->route('facturotheque.index');
        }

        // Mettre à jour l'état des factures associées
        $facturotheque->factures()->update(['etat' => 1]);

        $facturotheque->delete();

        notify()->success('Les factures associées ont été mises à jour et la facturothèque a été supprimée avec succès.');

        return redirect()->route('facture.liste');
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $facture = Facturotheque::find($id);

        if (!$facture) {
            notify()->error('Facture introuvable.');
            return redirect()->route('facturotheque.index');
        }

        // Supprimer les factures associées
        $facture->factures()->delete();

        $facture->delete();

        notify()->success('Facture et données associées supprimées avec succès.');
        return redirect()->route('facturotheque.index');
    }

    public function exportPdf(string $id)
    {
        $facturotheque = Facturotheque::findOrFail($id);

        if($facturotheque->reste == $facturotheque->total){
            $facturotheque->reste = null;
        }

        if($facturotheque->avance == $facturotheque->total){
            $facturotheque->avance = null;
        }

        $reference = $facturotheque->numFacture;
        $date = now();
        $factures = $facturotheque->factures()->get();
        
        $vendeur = auth()->user();
        $avance = $facturotheque->avance;
        $reste = $facturotheque->reste;
        $client = $factures->first(); // Assurez-vous que la relation client existe

        $totalMontants = $factures->sum('montant');

        $pdf = Pdf::loadView('facture.pdf', [
            'facture' => $factures,
            'totalMontants' => $totalMontants,
            'client' => $client,
            'vendeur' => $vendeur,
            'reference' =>$reference,
            'date' => $date,
            'reste' => $reste,
            'avance' => $avance,
            'depot' => $facturotheque->depot,
        ]);

        return $pdf->stream('facture.pdf');
    }

    public function exportTicket(string $id)
    {
        $facturotheque = Facturotheque::findOrFail($id);

        // Mise à jour des données avant le rendu
        if ($facturotheque->reste == $facturotheque->total) {
            $facturotheque->reste = null;
        }

        if ($facturotheque->avance == $facturotheque->total) {
            $facturotheque->avance = null;
        }

        $reference = $facturotheque->numFacture;
        $date = now();
        $factures = $facturotheque->factures()->get();
        $vendeur = auth()->user();
        $avance = $facturotheque->avance;
        $reste = $facturotheque->reste;
        $client = $factures->first(); // Vérifiez que la relation client existe
        $totalMontants = $factures->sum('montant');

        // Génération du PDF avec la taille de page définie
        $pdf = Pdf::loadView('facture.ticket', [
            'facture' => $factures,
            'totalMontants' => $totalMontants,
            'client' => $client,
            'vendeur' => $vendeur,
            'reference' => $reference,
            'date' => $date,
            'reste' => $reste,
            'avance' => $avance,
            'depot' => $facturotheque->depot,
        ])->setPaper([0, 0, 226.77, 841.89], 'portrait'); // 80mm x 297mm

        return $pdf->stream('facture.ticket.pdf', compact('id'));
    }

    public function avance(string $id)
    {
        $facture = Facturotheque::find($id);
        return view('facturotheque.avance', compact('facture'));
    }

    public function showAcompte(Request $request, $id)
    {
        $facture = Facturotheque::findOrFail($id);
        
        if ($facture->etat == 'avance') {
            notify()->error('Une avance a déjà été enregistrée. Veuillez consulter la section "Dette".');
            return redirect()->route('facturotheque.index');
        }

        if ($facture->reste == 0) {
            notify()->success('Facture déjà payée.');
            return redirect()->route('facturotheque.index');
        }

        $request->validate([
            'avance' => 'required|numeric|min:0',
        ]);

        // Calculs
        $acompte = $request->avance;
        $resteAPayer = max(0, $facture->reste - $acompte);
        $etatFacture = $resteAPayer == 0 ? 'payée' : 'avance';
        $depot = $facture->reste - $acompte < 0 ? abs($facture->reste - $acompte) : null;

        // Mise à jour de la facture
        $facture->update([
            'avance' => $acompte,
            'reste' => $resteAPayer,
            'etat' => $etatFacture,
            'depot' => $depot,
        ]);

        // Si la facture est totalement payée
        if ($resteAPayer == 0) {
            Dette::where('nom', $facture->nomCient)
                ->where('montant', $facture->total)
                ->update([
                    'etat' => 'payée',
                    'reste' => 0,
                    'depot' => $depot,
                ]);

            notify()->success('Facture payée avec succès.');
            return redirect()->route('facturotheque.index');
        }

        // Gestion de la dette
        $client = Client::where('nom', $facture->nomCient)->first();
        if (!$client) {
            return redirect()->back()->with('error', 'Client introuvable.');
        }

        Dette::updateOrCreate(
            [
                'client_id' => $client->id,
                'facturotheque_id' => $facture->id,
                'nom' => $facture->nomCient,
                'montant' => $facture->total,
            ],
            [
                'reste' => $resteAPayer,
                'commentaire' => 'Avance de la facture',
                'etat' => 'impayée',
            ]
        );

        notify()->success('Avance enregistrée avec succès.');
        return redirect()->route('facturotheque.index');
    }






}
