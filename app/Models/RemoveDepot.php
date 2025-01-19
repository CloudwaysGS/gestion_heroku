<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RemoveDepot extends Model
{
    use HasFactory;

    protected $fillable = [
        'depot_id',
        'qteSortie',
        'prix',
        'total',
        'nomProduit',
    ];

    /**
     * Relation inverse avec Produit (beaucoup-à-un)
     */
    public function depot()
    {
        return $this->belongsTo(Depot::class);
    }
}
