<?php

namespace App\Http\Requests\dosenlb;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdatePotonganRequest extends FormRequest
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
            'sp_FH' => 'nullable|integer',
            'infaq' => 'nullable|integer',
            'total_potongan' => 'nullable|integer',
            'dosen_luar_biasa_id' => 'required|integer|exists:dosen_luar_biasa,id'
        ];
    }
}
