<?php

namespace App\Http\Requests;

use App\Rules\CheckApiAuth;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules;

class CreateUserRequest extends FormRequest
{

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'email', 'unique:users'],
            'name' => ['required'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'url' => ['required', 'url'],
            'key' => ['required', new CheckApiAuth()],
        ];
    }

    public function messages(): array
    {
        return [
            'email.required' => 'Email необходимо заполнить email'
        ];
    }
}
