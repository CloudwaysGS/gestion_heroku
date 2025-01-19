<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dette extends Model
{
    use HasFactory;

    protected $fillable = ['client_id', 'facturotheque_id', 'nom', 'montant', 'reste', 'commentaire', 'etat','depot'];


    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public static function searchByName($search = null, $limit = 2)
    {
        $query = self::query();

        if ($search) {
            $query->where('nom', 'like', '%' . $search . '%');
        }

        return $query->orderBy('etat', 'asc')
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }




}
