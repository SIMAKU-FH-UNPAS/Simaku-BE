<?php

namespace App\Http\Requests\karyawan;

use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;
use App\Models\karyawan\Karyawan_Master_Transaksi;

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
            'karyawan_bank_id' => [
                'required',
                'integer',
                Rule::exists('karyawan_bank', 'id')
                    ->where('karyawan_id', Karyawan_Master_Transaksi::find($this->transaksiId)->karyawan_id)
                    ->whereNull('deleted_at'),
            ],
            'status_bank' => 'required|string|in:Payroll,Non Payroll'
        ];
    }
}
