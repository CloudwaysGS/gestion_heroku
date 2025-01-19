<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaidFournisseur extends Model
{
    use HasFactory;

    protected $fillable = ['fournisseur_id', 'montant', 'nom', 'reste'];
}
