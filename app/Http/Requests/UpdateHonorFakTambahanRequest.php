<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;

class UpdateHonorFakTambahanRequest extends FormRequest
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
            'dostap_gaji_fakultas_id' => 'required|integer|exists:dostap_gaji_fakultas,id',
            'nama_honor_FH' => 'required|string|max:255',
            'besar_honor_FH' => 'required|integer'
        ];
    }
}
