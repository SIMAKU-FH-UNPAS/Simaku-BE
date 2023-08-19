<?php

namespace App\Http\Requests\dosenlb;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class CreatePajakRequest extends FormRequest
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
            'pajak_pph25' => 'nullable|integer',
            'pendapatan_bersih' => 'nullable|integer',
            'dosen_luar_biasa_id' => 'required|integer|exists:dosen_luar_biasa,id',
            'doslb_pendapatan_id' => 'required|integer|exists:doslb_komponen_pendapatan,id',
            'doslb_potongan_id' => 'required|integer|exists:doslb_potongan,id'
        ];
    }
}
