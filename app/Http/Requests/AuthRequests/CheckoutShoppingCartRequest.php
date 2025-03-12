<?php

namespace App\Http\Requests\AuthRequests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\Validation\Validator;

class CheckoutShoppingCartRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {

        if ($this->input('guest') == 'true' ) {
            return [
                    'CreditCardNumber' => [
                        'required',
                        'numeric',
                        'digits_between:13,19' // Valid card numbers are typically between 13 to 19 digits

                    ],
                    'ExpMonth' => [
                        'required',
                        'numeric',
                        'between:1,12', // Valid months are from 1 to 12
                    ],
                    'ExpYear' => [
                        'required',
                        'integer',
                        'min:' . now()->year, // The year should be the current year or later
                    ],
                ];
        }else{
            return [];
        }
    }

    protected function failedValidation(Validator $validator): void
    {
        throw new HttpResponseException(
            new JsonResponse([
                'status' => 422,
                'message' => $validator->errors()->first(),
                'errors' => $validator->errors(),
            ], 422)
        );
    }
}
