<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;

class UpdateGajiFakRequest extends FormRequest
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
            'tj_tambahan' => 'nullable|integer',
            'honor_kinerja' => 'nullable|integer',
            'tj_pres_kerja' => 'nullable|integer',
            'honor_klb_mengajar' => 'nullable|integer',
            'honor_mengajar_DPK' => 'nullable|integer',
            'peny_honor_mengajar' => 'nullable|integer',
            'tj_guru_besar' => 'nullable|integer',
            'total_gaji_fakultas' => 'nullable|integer',
            'pegawai_id' => 'nullable|integer|exists:pegawai,id'

        ];
    }
}
