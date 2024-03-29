<?php

namespace App\Http\Requests\karyawan;

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
            'aksa_mandiri' => 'nullable|integer',
            'dplk_pensiun' => 'nullable|integer',
            'jumlah_potongan_kena_pajak' => 'nullable|integer',
            'jumlah_set_potongan_kena_pajak' => 'nullable|integer',
            'ptkp' => 'nullable|integer',
            'pkp' => 'nullable|integer',
            'pajak_pph21' => 'nullable|integer',
            'jumlah_set_pajak' => 'nullable|integer',
            'potongan_tak_kena_pajak' => 'nullable|integer',
            'pendapatan_bersih' => 'nullable|integer',
        ];
    }
}
