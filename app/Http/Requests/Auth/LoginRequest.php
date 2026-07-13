<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Must be true for everyone to attempt login
    }

    /**
     * Get the validation rules that apply to the request.
     */
       protected $errorBag = 'login'; 
    public function rules(): array
    {

        return [
            'gmail'    => 'required|email',
            'pass' => 'required|string|min:8',
        ];
    }

    /**
     * Custom error messages for a more professional feel.
     */
    public function messages(): array
    {
        return [
            'gmail.required'    => 'L\'adresse email est obligatoire.',
            'gmail.email'       => 'Veuillez saisir un email valide.',
            'pass.required' => 'Le mot de passe est obligatoire.',
        ];
    }
}