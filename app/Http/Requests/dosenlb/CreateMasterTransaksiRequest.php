<?php

namespace App\Http\Requests\dosenlb;

use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;

class CreateMasterTransaksiRequest extends FormRequest
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
            'dosen_luar_biasa_id'=> 'required|integer|exists:dosen_luar_biasa,id,deleted_at,NULL',
            'doslb_bank_id'=> 'required|integer|exists:doslb_bank,id,deleted_at,NULL'
        ];
    }
}
