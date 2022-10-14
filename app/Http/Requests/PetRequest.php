<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PetRequest extends FormRequest
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
                'alias' => ['required', 'string'],
                'type_id' => ['required', 'numeric', 'min:0'],
                'breed_id' => ['required', 'numeric', 'min:0'],
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
