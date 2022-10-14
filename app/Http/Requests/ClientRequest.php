<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ClientRequest extends FormRequest
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
                'email' => ['required', 'email'],
                'last_name' => ['required', 'string'],
            ]
            +
            ($this->isMethod('POST') ? $this->store() : $this->update());
    }

    protected function store()
    {
        return [];
    }

    protected function update()
    {
        return [];
    }
}
