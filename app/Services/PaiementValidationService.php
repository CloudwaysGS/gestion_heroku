<?php

namespace App\Services;

use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;

class PaiementValidationService
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
            'montant' => 'required|numeric|min:0',
            'id' => 'required|string|max:255',
        ], [
            'required' => 'Le champ :attribute est obligatoire.',
            'numeric' => 'Le champ :attribute doit être un nombre.',
            'min' => 'Le champ :attribute doit être au moins de :min.',
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return $validator->validated();
    }

}
