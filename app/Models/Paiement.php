<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Paiement extends Model
{
    use HasFactory;

    protected $fillable = ['dette_id', 'montant', 'nom', 'reste'];

    public function dette()
    {
        return $this->belongsTo(Dette::class);
    }

    public static function searchByName($search = null)
    {
        $query = self::query();

        if ($search) {
            $query->whereHas('dette', function ($q) use ($search) {
                $q->where('nom', 'like', '%' . $search . '%');
            });
        }

        return $query->orderBy('created_at', 'desc')->get();
    }


}
