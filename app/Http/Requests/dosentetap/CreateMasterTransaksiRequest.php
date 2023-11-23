<?php

namespace App\Http\Requests\dosentetap;

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
            'dosen_tetap_id'=> 'required|integer|exists:dosen_tetap,id,deleted_at,NULL',
            'dostap_bank_id'=> 'required|integer|exists:dostap_bank,id,deleted_at,NULL'

        ];
    }
}