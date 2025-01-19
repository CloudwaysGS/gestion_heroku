<?php

namespace App\Services;

use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;

class ProduitValidationService
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
            'qteProduit' => 'required|numeric|min:0.01',
            'prixProduit' => 'required|numeric|min:0.01',
            'prixAchat' => 'nullable|numeric|min:0.01',
            'nomDetail' => 'nullable|string|max:255',
            'prixDetail' => 'nullable|numeric|min:0.01',
            'nombre' => 'nullable|numeric|min:0.01',

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
}
