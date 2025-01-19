<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Entree extends Model
{
    use HasFactory;

    protected $fillable = ['produit_id', 'qteEntree', 'prix', 'total','nomProduit'];


    /**
     * Relation inverse avec Produit (beaucoup-à-un)
     */
    public function produit()
    {
        return $this->belongsTo(Produit::class);
    }
}
