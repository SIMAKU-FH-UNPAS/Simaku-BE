<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;

class UpdatePegawaiRequest extends FormRequest
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
            'status' => 'required|string|in:Aktif,Tidak Aktif',
            'posisi' =>'required|string|in:Dosen Tetap,Dosen Luar Biasa,Karyawan',
            'golongan_id' => 'required|integer|exists:golongan,id',
            'jabatan' => 'required|string|max:255',
            'alamat_KTP' => 'required|string|max:255',
            'alamat_saatini' => 'required|string|max:255',
            'nama_bank' => 'required|string|max:255',
            'norek_bank' => 'required|string|max:255'
        ];
    }
}
