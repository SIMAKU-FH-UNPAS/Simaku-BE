<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;

class UpdatePotonganRequest extends FormRequest
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
            'sp_FH' => 'nullable|integer',
            'iiku' => 'nullable|integer',
            'iid' => 'nullable|integer',
            'infaq' => 'nullable|integer',
            'abt' => 'nullable|integer',
            'total_potongan' => 'nullable|integer',
            'pegawai_id' => 'required|integer|exists:pegawai,id'

        ];
    }
}
