<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Facture;
use App\Models\Facture2;
use App\Models\Produit;
use App\Services\FactureValidationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Facture2Controller extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $facture2 = Facture2::where('etat', 1)->orderBy('created_at', 'desc')->get();
        $produits = Produit::all();
        $clients = Client::all();
        $details = Produit::select('id', 'nomDetail')
            ->distinct()
            ->where('nomDetail', '!=', '') // Élimine les chaînes vides
            ->get();

        $totalMontants = Facture2::where('etat', 1)->sum('montant');
        return view('facture2.liste', compact('facture2', 'produits', 'clients', 'details', 'totalMontants'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    protected $factureValidationService;

    public function __construct(FactureValidationService $factureValidationService)
    {
        $this->factureValidationService = $factureValidationService;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $factures = Facture2::where('etat', 1)->with('client')->get();

        if (is_null($request->client_id) && $factures->isEmpty()) {
            notify()->error('Choisir un client svp!!!');
            return redirect()->route('facture2.liste');
        }


        $validatedData = $this->factureValidationService->validate($request->all());
        $produitId = $request->nom ?? $validatedData['nomDetail'];
        $prixField = $request->nom ? 'prixProduit' : 'prixDetail';
        $qteField = $request->nom ? 'qteProduit' : 'qteDetail';

        if ($validatedData['nomDetail'] == null){

            DB::beginTransaction();

            try {
                $produit = Produit::findOrFail($produitId);
                $client = $request->client_id
                    ? Client::findOrFail($request->client_id)
                    : ($factures->isNotEmpty() ? $factures->first()->client : null);

                $nomFacture = $request->nom ? $produit->nom : $produit->nomDetail;
                $existingFacture = Facture2::where('nom', $nomFacture)->where('etat', 1)->first();

                if ($existingFacture) {
                    notify()->error('Un produit avec ce nom existe déjà.');
                    return redirect()->route('facture2.liste');
                }


                $montant = $validatedData['quantite'] * $produit->$prixField;
                $qteProduit = $produit->$qteField - $validatedData['quantite'];

                if ($produit->$qteField < $validatedData['quantite']) {
                    notify()->error('Quantité demandée non disponible en stock.');
                    return redirect()->route('facture2.liste');
                }

                $totalMontants = Facture2::where('etat', 1)->sum('montant');

                $facture = Facture2::create([
                    'nom' => $request->nom ? $produit->nom : $produit->nomDetail,
                    'quantite' => $validatedData['quantite'],
                    'client_id' => $client?->id,
                    'prix' => $produit->$prixField,
                    'montant' => $montant,
                    'etat' => 1,
                    'nomClient' => $client?->nom,
                    'total' => $totalMontants + $montant,
                    'produit_id' => $produitId,
                ]);

                $produit->update([
                    'qteProduit' => $qteProduit,
                    'qteDetail' => $qteProduit * $produit->nombre,
                    'montant' => $qteProduit * $produit->prixProduit,
                ]);

                DB::commit();

                return $request->ajax()
                    ? response()->json(['success' => 'Facture créée avec succès.', 'facture' => $facture])
                    : redirect()->route('facture2.liste')->with('success', 'Facture créée avec succès.');
            } catch (\Exception $e) {
                DB::rollBack();
                notify()->success('Success');
                return redirect()->route('facture2.index');

            }
        }
        elseif ($validatedData['nom'] == null){

            DB::beginTransaction();

            try {
                $produit = Produit::findOrFail($produitId);

                $client = $request->client_id
                    ? Client::findOrFail($request->client_id)
                    : ($factures->isNotEmpty() ? $factures->first()->client : null);

                $nomFacture = $request->nom ? $produit->nom : $produit->nomDetail;
                $existingFacture = Facture2::where('nom', $nomFacture)->where('etat', 1)->first();

                if ($existingFacture) {
                    notify()->error('Un produit avec ce nom existe déjà.');
                    return redirect()->route('facture2.liste');
                }

                $qteProduit = $produit->qteProduit - ($validatedData['quantite'] / $produit->nombre) ;
                $qteDetail = $produit->$qteField - $validatedData['quantite'];
                $nbreVendu = $validatedData['quantite'] / $produit->nombre;
                $montant = $validatedData['quantite'] * $produit->prixDetail;

                if ($produit->$qteField < $validatedData['quantite']) {

                    notify()->error('Quantité demandée non disponible en stock.');
                    return redirect()->route('facture2.liste');
                }

                $totalMontants = Facture2::where('etat', 1)->sum('montant');

                $facture = Facture2::create([
                    'nom' => $request->nom ? $produit->nom : $produit->nomDetail,
                    'quantite' => $validatedData['quantite'],
                    'client_id' => $client?->id,
                    'prix' => $produit->$prixField,
                    'montant' => $montant,
                    'etat' => 1,
                    'nomClient' => $client?->nom,
                    'total' => $totalMontants + $montant,
                    'produit_id' => $produitId,
                ]);

                $produit->update([
                    'qteProduit' => $qteProduit,
                    'qteDetail' => $qteDetail,
                    'nbreVendu' => $nbreVendu,
                    'montant' => $qteProduit * $produit->prixProduit,
                ]);

                DB::commit();

                return $request->ajax()
                    ? response()->json(['success' => 'Facture créée avec succès.', 'facture' => $facture])
                    : redirect()->route('facture2.liste')->with('success', 'Facture créée avec succès.');
            } catch (\Exception $e) {
                DB::rollBack();
                notify()->success('Success');
                return redirect()->route('facture2.index');
            }
        }

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
        $facture = Facture2::find($id);

        return view('facture2.modifier', compact('facture'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {

        $facture = Facture2::findOrFail($id);

        if ($request->input('isAjax')) {
            // Traitement des requêtes AJAX
            $request->validate([
                'prix' => 'nullable|numeric|min:0',
            ]);

            if ($request->has('prix')) {
                $facture->prix = $request->input('prix');
                $facture->montant = $facture->prix * $facture->quantite;
                $facture->save();

                $totalMontants = Facture2::where('etat', 1)->sum('montant');

                return response()->json([
                    'success' => true,
                    'message' => 'Mise à jour effectuée avec succès.',
                    'newMontant' => $facture->montant,
                    'totalMontants' => number_format($totalMontants, 2),
                ]);
            }
        }
        // Validation des données
        $validatedData = $this->factureValidationService->validate($request->all());

        $qteInitiale = $facture->quantite;
        $qteNouvelle = $validatedData['quantite'];

        // Récupération du produit lié
        $produit = Produit::findOrFail($facture->produit_id);

        if ($validatedData['nom'] == $produit->nomDetail){
            $facture->nom = $request->input('nom');
            $facture->quantite = $qteNouvelle;
            $facture->prix = $request->prix; // On récupère le prix actuel du produit
            $facture->montant = $facture->prix * $qteNouvelle;

            $diffQte = $qteNouvelle - $qteInitiale;
            if($diffQte > 0){
                $newQteDetail = $produit->qteDetail - $diffQte;
            }elseif ($diffQte < 0){
                $newQteDetail = $produit->qteDetail + abs($diffQte);
            }elseif ($diffQte == 0){

                notify()->success('Facture mise à jour avec succès.');
                return redirect()->route('facture2.index');
            }
            $facture->save();

            //Mise à jour produit
            $produit->qteDetail = $newQteDetail;
            $produit->qteProduit = $newQteDetail / $produit->nombre;
            $produit->nbreVendu = abs($diffQte) / $produit->nombre;
            $produit->montant = $produit->qteProduit * $produit->prixProduit;

            $produit->save();

            notify()->success('Facture mise à jour avec succès.');
            return redirect()->route('facture2.index');
        }

        // Mise à jour des informations de la facture
        $facture->nom = $request->input('nom');
        $facture->quantite = $qteNouvelle;
        $facture->prix = $request->prix; // On récupère le prix actuel du produit
        $facture->montant = $facture->prix * $qteNouvelle;

        // Gestion du stock du produit
        $produit->qteProduit += $qteInitiale; // On rétablit la quantité initiale dans le stock
        $produit->qteProduit -= $qteNouvelle; // On retire la nouvelle quantité du stock

        if ($produit->qteProduit < 0) {
            notify()->error('Stock insuffisant pour la quantité demandée.');
            return redirect()->route('facture2.index');
        }

        // Sauvegarde des modifications
        $facture->save();
        $produit->save();

        notify()->success('Facture mise à jour avec succès.');
        return redirect()->route('facture2.index');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Récupérer la facture
        $facture = Facture2::find($id);

        if (!$facture) {
            notify()->error('Facture introuvable.');
            return redirect()->route('facture2.index');
        }

        // Récupérer le produit associé
        $produit = Produit::find($facture->produit_id);

        if ($produit) {
            // Rétablir les quantités selon les conditions
            if ($produit->nomDetail === $facture->nom) {
                $restoreQteProduit = $facture->quantite / $produit->nombre;
                $produit->qteProduit += $restoreQteProduit;
                $produit->qteDetail = $produit->qteProduit * $produit->nombre;
                $produit->montant = $produit->qteProduit * $produit->prixProduit;
            } elseif ($produit->nom === $facture->nom) {

                $produit->qteProduit += $facture->quantite;
            }

            $produit->save();
        }

        // Supprimer la facture
        $facture->delete();

        notify()->success('Facture supprimée avec succès et stock mis à jour.');
        return redirect()->route('facture2.index');
    }


    public function deleteAll()
    {

        // Démarrer une transaction pour garantir l'intégrité des données
        DB::transaction(function () {
            // Récupérer les factures avec état = 1 et leurs produits associés
            $factures = Facture2::where('etat', 1)->with('produit')->get();

            if ($factures->isEmpty()) {
                notify()->warning('Aucune facture n\'a été trouvée.');
                return;
            }

            foreach ($factures as $facture) {
                $produit = $facture->produit;

                // Vérifier si le produit est associé
                if ($produit) {
                    if ($produit->nomDetail === $facture->nom) {
                        // Calculer la restauration pour les détails
                        $restoreQteProduit = $facture->quantite / ($produit->nombre ?: 1); // Éviter division par zéro
                        $produit->qteProduit += $restoreQteProduit;
                        $produit->qteDetail = $produit->qteProduit * $produit->nombre;
                        $produit->montant = $produit->qteProduit * $produit->prixProduit;
                    } else {
                        // Rétablir la quantité normale
                        $produit->qteProduit += $facture->quantite;
                    }

                    $produit->save();
                }

                // Supprimer la facture
                $facture->delete();
            }

            // Message de succès
            notify()->success('Toutes les factures ont été supprimées et les stocks ont été rétablis avec succès.');
        });

        // Rediriger vers la liste des factures
        return redirect()->route('facture2.index');
    }
}
