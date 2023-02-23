<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;

class CreateDosenTetapRequest extends FormRequest
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
            'nama_dostap' => 'required|string|max:255',
            'no_pegawai_dostap' => 'required|string|max:255',
            'golongan_dostap' =>'required|string|max:255',
            'status_dostap' => 'required|string|max:255',
            'jabatan_dostap' => 'required|string|max:255',
            'alamat_KTP_dostap' => 'required|string|max:255',
            'alamat_saatini_dostap' => 'required|string|max:255',
            'nama_bank_dostap' => 'required|string|max:255',
            'total_pendapatan_id' => 'required|integer|exists:total_pendapatan,id',
        ];
    }
}
