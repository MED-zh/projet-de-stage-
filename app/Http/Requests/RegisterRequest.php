<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Anyone can attempt to register
    }
     protected $errorBag = 'register'; 

    public function rules(): array
    {
        return [
            'name'        => 'required|string|max:255',
            'email'       => 'required|email|unique:users,email',
            'phone'       => 'required|string',
            'adresse'     => 'required|string',
            'password'    => 'required|min:6', 
            // 🔹 THE MAGIC LINE: Checks if it exists in contracts AND is unique in users
            'contrat_num' => 'required|exists:contrats,contrat_num|unique:users,contrat_num',
        ];
    }

    public function messages(): array
    {
        return [
            'email.unique'       => 'Cette adresse email est déjà utilisée.',
        ];
    }
}