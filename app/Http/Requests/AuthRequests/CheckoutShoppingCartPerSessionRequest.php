<?php

namespace App\Http\Requests\AuthRequests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\Validation\Validator;

class CheckoutShoppingCartPerSessionRequest extends FormRequest
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

            $rules = [
                'email' => 'required|email',
                'first_name' => 'required',
                'last_name' => 'required',
            ];

            if ($this->input('payment_mode') === 'by_card') {
                $rules = array_merge($rules, [
                    'CreditCardNumber' => 'required|numeric|digits_between:13,19',
                    'ExpMonth' => 'required|numeric|between:1,12',
                    'ExpYear' => 'required|integer|min:' . now()->year,
                    'CardHolder' => 'required|string|max:255',
                ]);
            }

            return $rules;
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
