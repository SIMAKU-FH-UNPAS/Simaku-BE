<?php

namespace App\Http\Requests\dosenlb;

use Illuminate\Foundation\Http\FormRequest;

use Illuminate\Support\Facades\Auth;
class CreateBankRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'banks' => 'required|array',
            'banks.*.nama_bank' => 'required|string',
            'banks.*.no_rekening' => 'required|string'
        ];
    }
}
