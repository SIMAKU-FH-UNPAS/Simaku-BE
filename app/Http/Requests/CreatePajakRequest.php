<?php

namespace App\Http\Requests;

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
            'pensiun' => 'nullable|integer',
            'bruto_pajak' => 'nullable|integer',
            'bruto_murni' => 'nullable|integer',
            'biaya_jabatan' => 'nullable|integer',
            'as_bumi_putera' => 'nullable|integer',
            'dplk_pensiun' => 'nullable|integer',
            'jml_pot_kn_pajak' => 'nullable|integer',
            'set_potongan_kn_pajak' => 'nullable|integer',
            'ptkp' => 'nullable|integer',
            'pkp' => 'nullable|integer',
            'pajak_pph21' => 'nullable|integer',
            'jml_set_pajak' => 'nullable|integer',
            'pot_tk_kena_pajak' => 'nullable|integer',
            'total_pajak' => 'nullable|integer',
            'pegawai_id' => 'nullable|integer|exists:pegawai,id'


        ];
    }
}
