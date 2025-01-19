<?php

namespace App\Services;

use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;

class DetteValidationService
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

            'client_id' => 'required|exists:clients,id', // Vérifie que le client existe
            'montant' => 'required|numeric|min:0.01',
            'commentaire' => 'nullable|string|max:255',
        ], [
            'required' => 'Le champ :attribute est obligatoire.',
            'numeric' => 'Le champ :attribute doit être un nombre.',
            'min' => 'Le champ :attribute doit être au moins de :min.',
            'max' => 'Le champ :attribute ne peut pas dépasser :max caractères.',
            'in' => 'Le champ :attribute doit être une des valeurs suivantes : :values.',
            'exists' => 'Le :attribute sélectionné n\'est pas valide.',
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return $validator->validated();
    }
}
