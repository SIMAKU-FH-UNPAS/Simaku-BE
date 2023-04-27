<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class CreateGajiUnivRequest extends FormRequest
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
            'gaji_pokok' => 'required|integer|max:255',
            'tj_struktural' => 'required|integer|max:255',
            'tj_pres_kerja' => 'required|integer|max:255',
            'u_lembur_hk' => 'required|integer|max:255',
            'u_lembur_hl' => 'required|integer|max:255',
            'trans_kehadiran' => 'required|integer|max:255',
            'tj_fungsional' => 'required|integer|max:255',
            'gaji_pusat' => 'required|integer|max:255',
            'tj_khs_istimewa' => 'required|integer|max:255',
            'tj_tambahan' => 'required|integer|max:255',
            'honor_univ' => 'required|integer|max:255',
            'tj_suami_istri' => 'required|integer|max:255',
            'tj_anak' => 'required|integer|max:255',
            'total_gaji_univ' => 'nullable',
            'pegawai_id' => 'required|integer|exists:pegawai,id'


        ];
    }
}
