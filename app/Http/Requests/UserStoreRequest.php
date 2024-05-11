<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserStoreRequest extends FormRequest
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
            'name' => ['required','string','max:191'],
            'account_type' => ['required','string','max:191'],
            'email' => ['required','email','unique:users,email'],
            'password' => ['required','string','max:191','confirmed','min:6'],
        ];
    }
}
