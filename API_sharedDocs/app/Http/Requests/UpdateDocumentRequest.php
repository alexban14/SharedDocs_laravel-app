<?php

namespace App\Http\Requests;

use App\Rules\IntegerArray;
use Illuminate\Foundation\Http\FormRequest;

class UpdateDocumentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'string',
            'body' => 'string',
            'user_ids' => [
                'array',
                new IntegerArray
            ]
        ];
    }

    // we can also define error messages
    public function messages()
    {
        return [
            'body.required' => 'Please enter a value for body',
            'title.string' => 'Please use strings a the title body',
        ];
    }
}
