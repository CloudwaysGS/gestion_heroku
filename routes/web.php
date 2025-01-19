<?php

use App\Http\Controllers\AccueilleController;
use App\Http\Controllers\AddepotController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\DepotController;
use App\Http\Controllers\DetteController;
use App\Http\Controllers\DetteFournisseurController;
use App\Http\Controllers\EntreeController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\Facture2Controller;
use App\Http\Controllers\FactureController;
use App\Http\Controllers\FacturothequeController;
use App\Http\Controllers\FournisseurController;
use App\Http\Controllers\PaidFournisseurController;
use App\Http\Controllers\PaiementController;
use App\Http\Controllers\ProduitController;
use App\Http\Controllers\SortieController;
use App\Models\Client;
use App\Models\Facturotheque;
use App\Models\Produit;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RemoveDepController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisteredUserController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
Route::get('/', function () {
    return view('login');
});

Route::get('/accueille', function () {
    return view('accueille');
})->middleware(['auth', 'verified'])->name('accueille');

Route::get('/register', [RegisteredUserController::class, 'create'])
    ->middleware('guest')
    ->name('register');

Route::post('/register', [RegisteredUserController::class, 'store'])
    ->middleware('guest');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::post('/produit/ajout', [ProduitController::class, 'store']);
    Route::post('/entree/ajout', [EntreeController::class, 'store']);
    Route::post('/client/ajout', [ClientController::class, 'store']);
    Route::post('/dette/ajout', [DetteController::class, 'store']);
    Route::post('/paiement/ajout', [PaiementController::class, 'store']);
    Route::post('/paidfournisseur/ajout', [PaidFournisseurController::class, 'store']);
    Route::post('/facture/ajout', [FactureController::class, 'store']);
    Route::post('/facture2', [Facture2Controller::class, 'store'])->name('facture2.store');
    Route::post('/ajout', [SortieController::class, 'store']);
    Route::post('/depotEntree/ajout', [AddepotController::class, 'store'])->name('depotEntree.store');
    Route::post('/depotSortie/ajout', [RemoveDepController::class, 'store'])->name('depotSortie.store');

    Route::get('/produit/delete/{id}', [ProduitController::class, 'destroy']);

    Route::get('/produit/restore/{id}', [ProduitController::class, 'restore']);

    Route::get('/produit/{id}/modifier', [ProduitController::class, 'edit'])->name('produit.modifier'); // To display the edit form
    Route::get('/entree/{id}/modifier', [EntreeController::class, 'edit'])->name('entree.modifier'); // Correction ici
    Route::get('/sortie/{id}/modifier', [SortieController::class, 'edit'])->name('sortie.modifier'); // Correction ici
    Route::get('/client/{id}/modifier', [ClientController::class, 'edit'])->name('client.modifier'); // Correction ici
    Route::get('/dette/{id}/modifier', [DetteController::class, 'edit'])->name('dette.modifier'); // Correction ici
    Route::get('/facture/{id}/modifier', [FactureController::class, 'edit'])->name('facture.modifier'); // Correction ici
    Route::get('/facture2/{id}/modifier', [Facture2Controller::class, 'edit'])->name('facture2.modifier'); // Correction ici
    Route::get('/facturotheque/modifier/{id}', [FacturothequeController::class, 'edit'])->name('facturotheque.modifier');
    Route::get('/depotEntree/edit/{id}', [AddepotController::class, 'edit'])->name('depotEntree.edit');
    Route::get('/depotSortie/edit/{id}', [RemoveDepController::class, 'edit'])->name('depotSortie.edit');

    Route::get('/dette/{id}/paiement', [PaiementController::class, 'paiement'])->name('paiement.ajout'); // Correction ici
    Route::get('/dette/{id}/paidfournisseur', [PaidFournisseurController::class, 'create'])->name('dette.paidFournisseur');
    Route::get('/depotEntree/create', [AddepotController::class, 'create'])->name('depotEntree.create');
    Route::get('/depotSortie/create', [RemoveDepController::class, 'create'])->name('depotSortie.create');

    Route::put('/produit/{id}', [ProduitController::class, 'update'])->name('produit.update'); // To update the product
    Route::put('/entree/{id}', [EntreeController::class, 'update'])->name('entree.update'); // To update the entre
    Route::put('/sortie/{id}', [SortieController::class, 'update'])->name('sortie.update'); // To update the entre
    Route::put('/client/{id}', [ClientController::class, 'update'])->name('client.update'); // To update the entre
    Route::put('/dette/{id}', [DetteController::class, 'update'])->name('dette.update'); // To update the entre
    Route::post('/facturotheque/{id}', [FacturothequeController::class, 'showAcompte'])->name('facturotheque.showAcompte');
    Route::put('/facture/{id}', [FactureController::class, 'update'])->name('facture.update'); // To update the entre
    Route::put('/facture2/{id}', [Facture2Controller::class, 'update'])->name('facture2.update'); // To update the entre
    Route::put('/depotEntree/{id}', [AddepotController::class, 'update'])->name('depotEntree.update'); // To update the entre
    Route::put('/depotSortie/{id}', [RemoveDepController::class, 'update'])->name('depotSortie.update'); // To update the entre

    Route::get('/sortie', [SortieController::class, 'index'])->name('sortie.liste');
    Route::get('/entree', [EntreeController::class, 'index'])->name('entree.liste');
    Route::get('/dette', [DetteController::class, 'index'])->name('dette.liste');
    Route::get('/client', [ClientController::class, 'index'])->name('client.liste');
    Route::get('/facture', [FactureController::class, 'index'])->name('facture.liste');
    Route::get('/ventes', [AccueilleController::class, 'index'])->name('statistique.ventes');
    Route::get('/facture2', [Facture2Controller::class, 'index'])->name('facture2.index');
    Route::get('/depotEntree', [AddepotController::class, 'index'])->name('depot_entree.index');
    Route::get('/depotSortie', [RemoveDepController::class, 'index'])->name('depot_sortie.index');

    Route::get('/accueille', [AccueilleController::class, 'caisse'])->name('accueille');
    Route::get('/paiement', [PaiementController::class, 'index'])->name('paiement.liste');
    Route::get('/produits', [ProduitController::class, 'index'])->name('produit.liste');
    Route::get('/statistique/depenses', [ExpenseController::class, 'index'])->name('statistique.depenses');

    Route::resource('facturotheque', FacturothequeController::class);
    Route::resource('expenses', ExpenseController::class);
    Route::resource('fournisseur', FournisseurController::class);
    Route::resource('dettefournisseur', DetteFournisseurController::class);
    Route::resource('dettefournisseur', DetteFournisseurController::class);
    Route::resource('depot', DepotController::class);

    Route::get('/client/delete/{id}', [ClientController::class, 'destroy'])->name('client.delete');
    Route::get('/entree/delete/{id}', [EntreeController::class, 'destroy']);
    Route::get('/sortie/delete/{id}', [SortieController::class, 'destroy']);
    Route::get('/dette/delete/{id}', [DetteController::class, 'destroy']);
    Route::get('/dep/supp/{id}', [DepotController::class, 'destroy'])->name('depot.confirmDestroy');
    Route::get('/depotEntree/supp/{id}', [AddepotController::class, 'destroy'])->name('depotEntree.confirmDestroy');
    Route::get('/depotSortie/supp/{id}', [RemoveDepController::class, 'destroy'])->name('depotSortie.confirmDestroy');
    Route::delete('/expenses_destry/{id}', [ExpenseController::class, 'destroy'])->name('expenses.destroyExpense');


    Route::get('/fournisseur/remove/{id}', [DetteFournisseurController::class, 'destroy'])->name('fournisseur.remove');

    Route::get('/facture/delete/{id}', [FactureController::class, 'destroy']);
    Route::get('/facturotheque/delete/{id}', [FacturothequeController::class, 'destroy']);
    Route::get('/facture2/{id}', [Facture2Controller::class, 'destroy'])->name('facture2.destroy');

    Route::get('/dette/searchAjax', [DetteController::class, 'searchAjax']);
    Route::get('/dettefournisseur/searchAjax', [DetteFournisseurController::class, 'searchAjax']);
    Route::get('/mesfacture/searchAjax', [FacturothequeController::class, 'searchAjax']);

    Route::get('/produits/search', [ProduitController::class, 'search']);
    Route::get('/depot/search', [DepotController::class, 'search']);

    Route::get('/clients', [ClientController::class, 'index'])->name('client.index');
    Route::get('/dette/detail/{id}', [DetteController::class, 'show'])->name('dette.détail');
    Route::delete('/factures/delete-all', [FactureController::class, 'deleteAll'])->name('factures.deleteAll');
    Route::delete('/factures2/delete-all', [Facture2Controller::class, 'deleteAll'])->name('factures2.deleteAll');
    Route::get('/facturotheque/acompte/{id}', [FacturothequeController::class, 'avance'])->name('facturotheque.avance');
    Route::get('/facturotheque/export-pdf/{id}', [FacturothequeController::class, 'exportPdf'])->name('facturotheque.export-pdf');
    Route::get('/facturotheque/exportTicket-pdf/{id}', [FacturothequeController::class, 'exportTicket'])->name('facturotheque.exportTicket-pdf');
    Route::get('/expenses/{id}/extraire', [ExpenseController::class, 'extraire'])->name('expenses.extraire');
    Route::get('/facturotheque/payer/{id}', [FacturothequeController::class, 'payer'])->name('facturotheque.payer');
    Route::get('/export-pdf-total', [AccueilleController::class, 'exportPDF'])->name('export_total.pdf');


    Route::get('/ajout', function() {
        return view('produit.ajout');
    });

    Route::get('/client/ajout', function() {
        return view('client.ajout');
    });

    Route::get('/paiement/ajout', function() {
        return view('paiement.ajout');
    });

    Route::get('/entree/ajout', function() {
        $produits = Produit::all(); // Charger les produits
        return view('entree.ajout', compact('produits'));
    });

    Route::get('/sortie/ajout', function() {
        $produits = Produit::all(); // Charger les produits
        return view('sortie.ajout', compact('produits'));
    });

    Route::get('/dette/ajout', function() {
        $clients = Client::all(); // Charger les clients
        return view('dette.ajout', compact('clients'));
    });
});

require __DIR__.'/auth.php';
