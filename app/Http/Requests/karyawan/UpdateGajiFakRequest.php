<?php

namespace App\Http\Requests\karyawan;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateGajiFakRequest extends FormRequest
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
            'honor' => 'nullable|integer',
            'total_gaji_fakultas' => 'nullable|integer',
            'karyawan_id' => 'required|integer|exists:karyawan,id'
        ];
    }
}
