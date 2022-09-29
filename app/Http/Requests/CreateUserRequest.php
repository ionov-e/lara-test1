<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

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

    protected function prepareForValidation()
    {
        $this->merge([
            'email' => $this->emailInput,
            'name' => $this->nameInput,
            'password' => $this->passwordInput,
            'key' => $this->keyInput,
            'url' => $this->urlInput,
        ]);
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
            'password' => ['required', 'min:6', 'max:25'],
            'key' => ['required'],
            'url' => ['required', 'url'],
        ];
    }

    public function messages(): array
    {
        return [
            'email.required' => 'Email необходимо заполнить email'
        ];
    }
}
