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
            'komponen_pendapatan' => 'required|array',
            // Validate each key in "gaji_fakultas" to be integer
            'komponen_pendapatan.*' => 'integer'
        ];
    }
}
