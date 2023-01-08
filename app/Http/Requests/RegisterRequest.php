<?php

namespace App\Http\Requests;

use App\Rules\OnlyLowercaseAndNumbers;
use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'username' => ['required', 'max:15', 'min:3', 'unique:users,username', new OnlyLowercaseAndNumbers],
            'email' => ['required', 'email', 'max:255', 'unique:emails,address'],
            'password' => ['required', 'confirmed', 'max:255', 'min:8', new OnlyLowercaseAndNumbers],
        ];
    }
}
