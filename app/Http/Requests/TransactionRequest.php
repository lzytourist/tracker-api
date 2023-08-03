<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TransactionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return in_array($this->get('transaction_type'), ['BALANCE', 'EXPENSE', 'LOAN_GIVEN', 'LOAN_TAKEN']);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        $rules = [
            'title' => 'required|min:2',
            'amount' => 'required|integer',
            'transaction_type' => 'required|in:BALANCE,EXPENSE,LOAN_GIVEN,LOAN_TAKEN'
        ];

        if (in_array($this->get('transaction_type'), ['LOAN_GIVEN', 'LOAN_TAKEN'])) {
            $rules['paid'] = 'required|boolean';
        }

        return $rules;
    }
}
