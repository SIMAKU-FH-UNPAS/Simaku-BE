<?php

namespace App\Http\Requests\karyawan;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdatePajakRequest extends FormRequest
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
            'aksa_mandiri' => 'nullable|integer',
            'dplk_pensiun' => 'nullable|integer',
            'jml_pot_kn_pajak' => 'nullable|integer',
            'jml_set_pot_kn_pajak' => 'nullable|integer',
            'ptkp' => 'nullable|integer',
            'pkp' => 'nullable|integer',
            'pajak_pph21' => 'nullable|integer',
            'jml_set_pajak' => 'nullable|integer',
            'pot_tk_kena_pajak' => 'nullable|integer',
            'pendapatan_bersih' => 'nullable|integer',
            'karyawan_id' => 'required|integer|exists:karyawan,id',
            'karyawan_gaji_universitas_id' => 'required|integer|exists:karyawan_gaji_universitas,id',
            'karyawan_gaji_fakultas_id' => 'required|integer|exists:karyawan_gaji_fakultas,id',
            'karyawan_potongan_id' => 'required|integer|exists:karyawan_potongan,id'


        ];
    }
}
