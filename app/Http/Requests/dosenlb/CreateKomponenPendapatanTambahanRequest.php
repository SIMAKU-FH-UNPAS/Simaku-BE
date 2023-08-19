<?php

namespace App\Http\Requests\dosenlb;

use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;

class CreateKomponenPendapatanTambahanRequest extends FormRequest
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
            'doslb_pendapatan_id' => 'required|integer|exists:doslb_komponen_pendapatan,id',
            'nama_komponen' => 'required|string|max:255',
            'besar_komponen' => 'required|integer'
        ];
    }
}
