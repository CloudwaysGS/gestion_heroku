<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\Facture;
use App\Models\Facturotheque;
use App\Models\Produit;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

class AccueilleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Liste des périodes à analyser
        $periods = [
            'Aujourd\'hui' => today(),
            'Hier' => today()->subDay(),
            'Avant_hier' => today()->subDays(2),
            'Cette semaine' => [now()->startOfWeek(), now()->endOfWeek()],
            'Ce mois' => [now()->startOfMonth(), now()->endOfMonth()],
            'Derniers trois mois' => [now()->subMonths(3)->startOfMonth(), now()->endOfMonth()],
            'Cette année' => [now()->startOfYear(), now()->endOfYear()],
            'Année dernière' => [now()->subYear()->startOfYear(), now()->subYear()->endOfYear()],
        ];

        // Préparer les données
        $salesData = [];

        foreach ($periods as $key => $period) {
            if (is_array($period)) {
                // Si la période est un intervalle (ex: cette semaine)
                $totals = Facturotheque::selectRaw('
                SUM(total) as total,
                SUM(CASE WHEN avance IS NOT NULL THEN avance ELSE total END) as totalWithAvance
            ')->whereBetween('created_at', $period)->first();

                $max = Facturotheque::whereBetween('created_at', $period)->max('total');
                $min = Facturotheque::whereBetween('created_at', $period)->min('total');

                // Récupérer le produit le plus vendu pour cette période
                $topProduct = Facture::whereBetween('created_at', $period)
                    ->select('produit_id', DB::raw('SUM(montant) as total_sales'))
                    ->groupBy('produit_id')
                    ->orderByDesc('total_sales')
                    ->first();
            } else {
                // Si la période est une date spécifique (ex: aujourd'hui, hier)
                $totals = Facturotheque::selectRaw('
                SUM(total) as total,
                SUM(IF(avance IS NOT NULL, avance, total)) as totalWithAvance
            ')->whereDate('created_at', $period)->first();
                
                $max = Facturotheque::whereDate('created_at', $period)->max('total');
                $min = Facturotheque::whereDate('created_at', $period)->min('total');

                // Récupérer le produit le plus vendu pour cette période
                $topProduct = Facture::whereDate('created_at', $period)
                    ->select('produit_id', DB::raw('SUM(montant) as total_sales'))
                    ->groupBy('produit_id')
                    ->orderByDesc('total_sales')
                    ->first();
            }

            $salesData[$key] = [
                'total' => $totals->total,
                'totalWithAvance' => $totals->totalWithAvance,
                'max' => $max,
                'min' => $min,
                'topProduct' => $topProduct ? $topProduct->produit_id : null, // Assurez-vous d'avoir l'ID du produit
            ];
        }

        // Passer les données à la vue
        return view('statistique.ventes', [
            'salesData' => $salesData,
        ]);
    }





    /**
     * Show the form for creating a new resource.
     */
    public function caisse()
    {
        $aujourdhui = Carbon::today()->toDateString();

        $totaux = $this->calculTotaux($aujourdhui);

        // Nombre total de produits (avec mise en cache)
        $nombreProduit = Cache::remember('nombre_produit', 60, function () {
            return Produit::count();
        });

        // Retourner les données à la vue
        return view('accueille', array_merge($totaux, compact('nombreProduit')));
    }

    private function calculTotaux($aujourdhui)
    {
        return Cache::remember("totaux_du_jour_{$aujourdhui}", 60, function () use ($aujourdhui) {
            $totalFactures = Facturotheque::selectRaw('
            SUM(total) as totalFactures,
            SUM(IF(avance IS NOT NULL, avance, total)) as totalFacturesAujourdhui
        ')->whereDate('created_at', $aujourdhui)->first();

            $totalDepenses = Expense::selectRaw('
            SUM(amount) as totalDepenses,
            SUM(IF(DATE(created_at) = ?, amount, 0)) as totalDepensesAujourdhui
        ', [$aujourdhui])->first();

            return [
                'totalFactures' => $totalFactures->totalFactures ?? 0,
                'totalFacturesAujourdhui' => $totalFactures->totalFacturesAujourdhui ?? 0,
                'totalDepenses' => $totalDepenses->totalDepenses ?? 0,
                'totalDepensesAujourdhui' => $totalDepenses->totalDepensesAujourdhui ?? 0,
                'totalVenduAuj' => ($totalFactures->totalFacturesAujourdhui ?? 0) - ($totalDepenses->totalDepensesAujourdhui ?? 0),
                'soldeCaisse' => ($totalFactures->totalFactures ?? 0) - ($totalDepenses->totalDepenses ?? 0),
            ];
        });
    }


    public function exportPDF()
    {
        $aujourdhui = Carbon::today()->toDateString();

        $totaux = $this->calculTotaux($aujourdhui);

        $totalVenduAuj = $totaux['totalVenduAuj'];
        $date = now()->format('d/m/Y H:i');

        $pdf = Pdf::loadView('depenses.total_vendu_auj', compact('totalVenduAuj', 'date'));

        return $pdf->stream('total_vendu_auj.pdf');
    }

}
