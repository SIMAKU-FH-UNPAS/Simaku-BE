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
            'pensiun' => 'required|integer',
            'bruto_pajak' => 'required|integer',
            'bruto_murni' => 'required|integer',
            'biaya_jabatan' => 'required|integer',
            'aksa_mandiri' => 'required|integer',
            'dplk_pensiun' => 'required|integer',
            'jumlah_potongan_kena_pajak' => 'required|integer',
            'jumlah_set_potongan_kena_pajak' => 'required|integer',
            'ptkp' => 'required|integer',
            'pkp' => 'required|integer',
            'pajak_pph21' => 'required|integer',
            'jumlah_set_pajak' => 'required|integer',
            'potongan_tak_kena_pajak' => 'required|integer',
            'pendapatan_bersih' => 'required|integer'

        ];
    }
}
