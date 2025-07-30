<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\Availability;

class AvailabilityRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $availabilityId = $this->route('availability');
        $serviceId = $this->input('service_id');
        $start = $this->input('start_datetime');
        $end = $this->input('end_datetime');

        $rules = [
            'service_id' => ['required', 'exists:services,id'],
            'start_datetime' => [
                'required',
                'date',
                'before:end_datetime',
                function (
                    $attribute,
                    $value,
                    $fail
                ) use ($serviceId, $end, $availabilityId) {
                    if ($serviceId && $end) {
                        $query = \App\Models\Availability::where('service_id', $serviceId)
                            ->where(function ($q) use ($value, $end) {
                                $q->whereBetween('start_datetime', [$value, $end])
                                    ->orWhereBetween('end_datetime', [$value, $end])
                                    ->orWhere(function ($q2) use ($value, $end) {
                                        $q2->where('start_datetime', '<=', $value)
                                            ->where('end_datetime', '>=', $end);
                                    });
                            });
                        if ($availabilityId) {
                            $query->where('id', '!=', $availabilityId);
                        }
                        if ($query->exists()) {
                            $fail('Ce créneau chevauche un autre créneau pour ce service.');
                        }
                    }
                },
            ],
            'end_datetime' => ['required', 'date', 'after:start_datetime'],
            'status' => ['required', \Illuminate\Validation\Rule::in(['available', 'reserved'])],
        ];
        if (auth()->user() && auth()->user()->role === 'admin') {
            $rules['user_id'] = ['required', 'exists:users,id', function ($attribute, $value, $fail) {
                $user = \App\Models\User::find($value);
                if (!$user || $user->role !== 'api') {
                    $fail('L\'API sélectionné est invalide.');
                }
            }];
        }
        return $rules;
    }

    public function messages(): array
    {
        return [
            'service_id.required' => 'Le service est obligatoire.',
            'service_id.exists' => 'Le service sélectionné est invalide.',
            'start_datetime.required' => 'La date/heure de début est obligatoire.',
            'start_datetime.date' => 'La date/heure de début doit être une date valide.',
            'start_datetime.before' => 'La date/heure de début doit précéder la date de fin.',
            'end_datetime.required' => 'La date/heure de fin est obligatoire.',
            'end_datetime.date' => 'La date/heure de fin doit être une date valide.',
            'end_datetime.after' => 'La date/heure de fin doit être postérieure à la date de début.',
            'status.required' => 'Le statut est obligatoire.',
            'status.in' => 'Le statut doit être "available" ou "reserved".',
            'user_id.required' => 'L\'API est obligatoire.',
            'user_id.exists' => 'L\'API sélectionné est invalide.',
        ];
    }
}
