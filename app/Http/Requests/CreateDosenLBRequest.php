<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class CreateDosenLBRequest extends FormRequest
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
            'nama_dosluar' => 'required|string|max:255',
            'no_pegawai_dosluar' => 'required|string|max:255',
            'golongan_dosluar' =>'required|string|max:255',
            'status_dosluar' => 'required|string|max:255',
            'jabatan_dosluar' => 'required|string|max:255',
            'alamat_KTP_dosluar' => 'required|string|max:255',
            'alamat_saatini_dosluar' => 'required|string|max:255',
            'nama_bank_dosluar' => 'required|string|max:255',
            'total_pendapatan_id' => 'required|integer|exists:total_pendapatan,id',
        ];
    }
}
