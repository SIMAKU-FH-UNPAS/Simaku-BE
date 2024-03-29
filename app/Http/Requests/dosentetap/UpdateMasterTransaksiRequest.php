<?php

namespace App\Http\Requests\dosentetap;

use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;
use App\Models\dosentetap\Dostap_Master_Transaksi;

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
                    ->where(function ($query) {
                        $query->where('id', $this->dostap_bank_id)
                              ->where('dosen_tetap_id', Dostap_Master_Transaksi::find($this->transaksiId)->dosen_tetap_id)
                              ->whereNull('deleted_at');
                    }),
            ],
            'status_bank' => 'required|string|in:Payroll,Non Payroll'
        ];
    }
}
