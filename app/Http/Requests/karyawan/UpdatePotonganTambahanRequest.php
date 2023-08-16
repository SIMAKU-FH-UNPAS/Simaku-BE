<?php

namespace App\Http\Requests\karyawan;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdatePotonganTambahanRequest extends FormRequest
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
            'nama_potongan' => 'required|string|max:255',
            'besar_potongan' => 'required|integer',
            'karyawan_potongan_id' => 'required|integer|exists:karyawan_potongan,id'
        ];
    }
}
