<?php

namespace App\Http\Requests\karyawan;

use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;

class UpdateKaryawanRequest extends FormRequest
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
            'nama' => 'required|string|max:255',
            'no_pegawai' => 'required|string|max:255',
            'npwp' => 'string|max:255',
            'status' => 'required|string|in:Aktif,Tidak Aktif',
            'golongan' =>'required|string|in:IIA,IIB,IIC,IID,IIIA,IIIB,IIIC,IIID,IVA,IVB,IVC,IVD,IVE',
            'jabatan' => 'required|string|max:255',
            'alamat_KTP' => 'required|string|max:255',
            'alamat_saat_ini' => 'required|string|max:255',
            'nomor_hp' => 'required|string|max:255',
        ];
    }
}
