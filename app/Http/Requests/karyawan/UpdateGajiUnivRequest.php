<?php

namespace App\Http\Requests\karyawan;

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
            'gaji_pokok' => 'required|integer',
            'tunjangan_fungsional' => 'required|integer',
            'tunjangan_struktural' => 'required|integer',
            'tunjangan_khusus_istimewa' => 'required|integer',
            'tunjangan_presensi_kerja' => 'required|integer',
            'tunjangan_tambahan' => 'required|integer',
            'tunjangan_suami_istri' => 'required|integer',
            'tunjangan_anak' => 'required|integer',
            'uang_lembur_hk' => 'required|integer',
            'uang_lembur_hl' => 'required|integer',
            'transport_kehadiran' => 'required|integer',
            'honor_universitas' => 'required|integer',
        ];
    }
}

