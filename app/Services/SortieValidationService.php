<?php

namespace App\Services;

use App\Models\Produit;
use App\Models\Sortie;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;

class SortieValidationService
{
    /**
     * Validate product data with custom error messages.
     *
     * @param array $data
     * @return array
     * @throws ValidationException
     */
    public function validate(array $data)
    {
        $validator = Validator::make($data, [
            'nom' => 'required|string|max:255',
            'qteSortie' => 'required|integer|min:1',
            'prix' => 'required|numeric|min:0',
        ], [
            'required' => 'Le champ :attribute est obligatoire.',
            'string' => 'Le champ :attribute doit être une chaîne de caractères.',
            'integer' => 'Le champ :attribute doit être un nombre entier.',
            'numeric' => 'Le champ :attribute doit être un nombre.',
            'min' => 'Le champ :attribute doit être au moins de :min.',
            'max' => 'Le champ :attribute ne peut pas dépasser :max caractères.',
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return $validator->validated();
    }

    public function validateEdit(array $data)
    {
        $validator = Validator::make($data, [
            'produit_id' => 'required|string|max:255',
            'qteSortie' => 'required|integer|min:1',
            'prix' => 'required|numeric|min:0',
        ], [
            'required' => 'Le champ :attribute est obligatoire.',
            'string' => 'Le champ :attribute doit être une chaîne de caractères.',
            'integer' => 'Le champ :attribute doit être un nombre entier.',
            'numeric' => 'Le champ :attribute doit être un nombre.',
            'min' => 'Le champ :attribute doit être au moins de :min.',
            'max' => 'Le champ :attribute ne peut pas dépasser :max caractères.',
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return $validator->validated();
    }

    public function updateSortie(array $data, string $id): bool
    {
        $sortie = Sortie::find($id);
        if (!$sortie) {
            throw new \Exception('Sortie introuvable.');
        }

        $produit = Produit::find($data['produit_id']);
        if (!$produit) {
            throw new \Exception('Produit introuvable.');
        }

        // Calculs
        $qteInitiale = $sortie->qteSortie;
        $qteNouvelle = $data['qteSortie'];
        $diffQte = $qteInitiale - $qteNouvelle;

        // Transaction
        DB::transaction(function () use ($sortie, $produit, $data, $diffQte, $qteNouvelle) {
            $sortie->qteSortie = $qteNouvelle;
            $sortie->prix = $data['prix'];
            $produit->qteProduit += $diffQte;

            if ($produit->qteProduit + $diffQte < 0) {
                throw new \Exception('Stock insuffisant pour effectuer cette mise à jour.');
            }

            $sortie->save();
            $produit->save();

        });

        return true;
    }

}
