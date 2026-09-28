<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string'],
            'prenom' => ['required', 'string'],
            'sexe' => ['required', 'in:homme,femme'],
            'contact' => ['required', 'string'],
            'email' => ['required', 'email',Rule::unique(User::class)->ignore($this->user()->id)],

            // 'date_naissance' => ['required', 'date'],
            // 'lieu_naissance' => ['required', 'string'],
            // 'numero_passeport' => ['required', 'string'],
            // 'universite_actuelle' => ['required', 'string'],
            // 'niveau_etude' => ['required', 'string'],
            // 'domaine_etude' => ['nullable', 'string'],
        ];
    }
}
