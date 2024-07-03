<?php

namespace App\Http\Requests\dosenlb;

use App\Models\dosenlb\Doslb_Master_Transaksi;
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
            'doslb_bank_id' => [
                'required',
                'integer',
                Rule::exists('doslb_bank', 'id')
                    ->where('dosen_luar_biasa_id', Doslb_Master_Transaksi::find($this->transaksiId)->dosen_luar_biasa_id)
                    ->whereNull('deleted_at'),
            ],
            'status_bank' => 'required|string|in:Payroll,Non Payroll'
        ];
    }
}
