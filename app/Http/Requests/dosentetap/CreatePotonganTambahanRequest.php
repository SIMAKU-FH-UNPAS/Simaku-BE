<?php

namespace App\Http\Requests\dosentetap;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class CreatePotonganTambahanRequest extends FormRequest
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
            'dostap_potongan_id' => 'required|integer|exists:dostap_potongan,id'


        ];
    }
}
