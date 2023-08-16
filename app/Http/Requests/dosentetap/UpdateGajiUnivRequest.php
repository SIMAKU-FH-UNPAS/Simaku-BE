<?php

namespace App\Http\Requests\dosentetap;

use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;

class UpdateGajiUnivRequest extends FormRequest
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
            'dosen_tetap_id' => 'required|integer|exists:dosen_tetap,id',
            'gaji_pokok' => 'required|integer',
            'tj_struktural' => 'required|integer',
            'tj_pres_kerja' => 'required|integer',
            'u_lembur_hk' => 'required|integer',
            'u_lembur_hl' => 'required|integer',
            'trans_kehadiran' => 'required|integer',
            'tj_fungsional' => 'required|integer',
            'tj_khs_istimewa' => 'required|integer',
            'tj_tambahan' => 'required|integer',
            'honor_univ' => 'required|integer',
            'tj_suami_istri' => 'required|integer',
            'tj_anak' => 'required|integer',
            'total_gaji_univ' => 'nullable'
        ];
    }
}
