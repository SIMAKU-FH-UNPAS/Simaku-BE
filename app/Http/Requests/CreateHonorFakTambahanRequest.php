<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class CreateHonorFakTambahanRequest extends FormRequest
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
            'gaji_fakultas_id' => 'required|integer|exists:gaji_fakultas,id',
            'nama_honor_FH' => 'required|string|max:255',
            'besar_honor_FH' => 'required|integer'
        ];
    }
}