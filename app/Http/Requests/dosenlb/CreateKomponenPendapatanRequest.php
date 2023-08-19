<?php

namespace App\Http\Requests\dosenlb;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class CreateKomponenPendapatanRequest extends FormRequest
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
            'tj_guru_besar' => 'nullable|integer',
            'honor_mengajar' => 'nullable|integer',
            'total_komponen_pendapatan' => 'nullable|integer',
            'dosen_luar_biasa_id' => 'required|integer|exists:dosen_luar_biasa,id'
        ];
    }
}
