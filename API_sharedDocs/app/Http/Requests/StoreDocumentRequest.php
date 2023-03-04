<?php

namespace App\Http\Requests;

use App\Rules\IntegerArray;
use Hamcrest\Type\IsInteger;
use Illuminate\Foundation\Http\FormRequest;

class StoreDocumentRequest extends FormRequest
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
            'title' => ['string', 'required'],
            'body' => ['string', 'required'],
            'user_ids' => [
                'array',
                'required',
                // custom validation with
                new IntegerArray()

                // custom validation with closure method
                // attribute = field name
                // function($attribute, $value, $fail) {
                //     // we loop thru the values array and make sure if every one of them is an integer
                //     $isInteger = collect($value)->every( fn($element) => is_int($element) );

                //     if(!$isInteger) {
                //         $fail(  $attribute . ' field can only be integers.');
                //     }
                // }
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
