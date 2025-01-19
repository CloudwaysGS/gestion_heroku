<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Produit extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'nom',
        'qteProduit',
        'prixProduit',
        'nomDetail',
        'prixAchat',
        'montant',
        'nombre',
        'prixDetail',
        'qteDetail',
        'nbreVendu',
        'prixRevient',
        ];

    /**
     * Relation un-à-plusieurs avec SortieProduit
     */
    public function sortie(): HasMany
    {
        return $this->hasMany(Sortie::class);
    }

    public function entree(): HasMany
    {
        return $this->hasMany(Entree::class);
    }


    public static function searchByName($search = null)
    {
        return self::query()
            ->when($search, function ($query, $search) {
                $query->where('nom', 'like', '%' . $search . '%');
            })
            ->orderBy('created_at', 'desc')
            ->get();
    }



}
