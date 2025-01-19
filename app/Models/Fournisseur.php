<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fournisseur extends Model
{
    use HasFactory;

    protected $fillable = ['nom', 'telephone', 'adresse'];

    public static function searchByName($search = null, $limit = 2)
    {
        $query = self::query();

        if ($search) {
            $query->where('nom', 'like', '%' . $search . '%');
        }

        return $query->orderBy('created_at', 'desc')
            ->paginate($limit);
    }

}
