<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Facturotheque extends Model
{
    use HasFactory;

    protected $fillable = [
        'nbreLigne',
        'nomCient',
        'total',
        'adresse',
        'telephone',
        'numFacture',
        'etat',
        'avance',
        'reste',
        'depot',
    ];

    // Dans le modèle Facturotheque
    public function factures()
    {
        return $this->hasMany(Facture::class, 'facturotheque_id');
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public static function searchByName($search = null, $limit = 10)
    {
        $query = self::query();

        if ($search) {
            $query->where('nomCient', 'like', '%' . $search . '%');
        }

        return $query->orderBy('etat', 'asc')
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

}
