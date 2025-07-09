<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ServiceRequest extends FormRequest
{
    /**
     * Déterminer si l'utilisateur est autorisé à faire cette requête.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Obtenir les règles de validation qui s'appliquent à la requête.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $serviceId = $this->route('service');

        return [
            'name' => [
                'required',
                'string',
                'max:120',
                Rule::unique('services')->ignore($serviceId),
            ],
            'description' => 'nullable|string',
            'duration_minutes' => 'required|integer|min:5|max:240',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Obtenir les messages d'erreur personnalisés.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Le nom du service est requis.',
            'name.max' => 'Le nom du service ne peut pas dépasser 120 caractères.',
            'name.unique' => 'Ce nom de service existe déjà.',
            'duration_minutes.required' => 'La durée en minutes est requise.',
            'duration_minutes.integer' => 'La durée doit être un nombre entier.',
            'duration_minutes.min' => 'La durée minimale est de 5 minutes.',
            'duration_minutes.max' => 'La durée maximale est de 240 minutes.',
        ];
    }
}
