<?php

namespace App\Http\Controllers\API\dosenlb;

use App\Models\dosenlb\Doslb_Pajak;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class HitungPajakController extends Controller
{
         // Fungsi untuk Komponen Pendapatan
    public function Hitung_Pajak_Pendapatan($request, $pendapatanId){

        $pajakpegawai = Doslb_Pajak::where('doslb_pendapatan_id', $pendapatanId)->get();
        foreach ($pajakpegawai as $pajak) {
         // Ambil data dari database
           $existingPajak = Doslb_Pajak::find($pajak->id);

           // Simpan nilai-nilai berikut pada properti $request
           $request->merge([
               'dosen_luar_biasa_id' => $existingPajak->dosen_luar_biasa_id,
               'doslb_pendapatan_id' => $existingPajak->doslb_pendapatan_id,
               'doslb_potongan_id' => $existingPajak->doslb_potongan_id
           ]);

          // Hitung ulang nilai-nilai pajak berdasarkan perubahan data Komponen Pendapatan
         $pajakobjek = new Doslb_Pajak;
         $pajak_pph25 = $pajakobjek->hitung_pajak_pph25($request);
         $pendapatan_bersih = $pajakobjek->hitung_pendapatan_bersih($request);


         $pajak -> update([
             'pajak_pph25' => $pajak_pph25,
             'pendapatan_bersih' => $pendapatan_bersih
         ]);
        }
     }
    // Fungsi untuk Potongan
    public function Hitung_Pajak_Pot($request, $potonganId){

        $pajakpegawai = Doslb_Pajak::where('doslb_potongan_id', $potonganId)->get();
        foreach ($pajakpegawai as $pajak) {
         // Ambil data dari database
           $existingPajak = Doslb_Pajak::find($pajak->id);

           // Simpan nilai-nilai berikut pada properti $request
           $request->merge([
            'dosen_luar_biasa_id' => $existingPajak->dosen_luar_biasa_id,
            'doslb_pendapatan_id' => $existingPajak->doslb_pendapatan_id,
            'doslb_potongan_id' => $existingPajak->doslb_potongan_id
           ]);

          // Hitung ulang nilai-nilai pajak berdasarkan perubahan data Gaji_Universitas
          $pajakobjek = new Doslb_Pajak;
          $pajak_pph25 = $pajakobjek->hitung_pajak_pph25($request);
          $pendapatan_bersih = $pajakobjek->hitung_pendapatan_bersih($request);

         $pajak -> update([
            'pajak_pph25' => $pajak_pph25,
            'pendapatan_bersih' => $pendapatan_bersih
         ]);
        }
     }
    }
