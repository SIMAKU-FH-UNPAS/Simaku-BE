<?php

namespace App\Http\Requests\dosentetap;

use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;

class UpdateMasterTransaksiRequest extends FormRequest
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
            'dostap_bank_id' => [
                'required',
                'integer',
                Rule::exists('dostap_bank', 'id')
                    ->where('dosen_tetap_id', $this->dosen_tetap_id)
                    ->whereNull('deleted_at'),
            ],
        ];
    }
}
