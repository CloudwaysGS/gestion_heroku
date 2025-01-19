<?php

namespace App\Services;

use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;

class ClientValidationService
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
            'nom' => 'required|string|max:255', // Nom obligatoire, chaîne, max 255 caractères
            'telephone' => 'required|digits_between:8,15', // Numéro de téléphone entre 8 et 15 chiffres
            'adresse' => 'required|string|max:500', // Adresse obligatoire, chaîne, max 500 caractères
        ], [
            'required' => 'Le champ :attribute est obligatoire.',
            'string' => 'Le champ :attribute doit être une chaîne de caractères.',
            'integer' => 'Le champ :attribute doit être un nombre entier.',
            'min' => 'Le champ :attribute doit être au moins de :min.',
            'max' => 'Le champ :attribute ne peut pas dépasser :max caractères.',
            'digits_between' => 'Le champ :attribute doit contenir entre :min et :max chiffres.',
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return $validator->validated();
    }
}
