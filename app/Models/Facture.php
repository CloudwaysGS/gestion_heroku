<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Facture extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'nom',
        'quantite',
        'prix',
        'montant',
        'total',
        'nomClient',
        'etat',
        'produit_id',
        'facturotheque_id',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function facturotheque()
    {
        return $this->belongsTo(Facturotheque::class);
    }

    public function paiements()
    {
        return $this->hasMany(Paiement::class);
    }

    public function produit()
    {
        return $this->belongsTo(Produit::class, 'produit_id');
    }

}
