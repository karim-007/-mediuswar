<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TransactionUpdateRequest extends FormRequest
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
     */
    public function rules(): array
    {
        return [
            'user_id' => ['required','not_in:0','exists:users,id'],
            'transaction_type' => ['required'],
            'amount' => ['required','not_in:0','numeric'],
            'fee' => ['required','not_in:-1','numeric'],
            'date' => ['required','date'],
        ];
    }
}
