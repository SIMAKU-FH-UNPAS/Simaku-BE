<?php

namespace App\Http\Controllers\API\dosentetap;

use App\Models\dosentetap\Dostap_Pajak;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class HitungPajakController extends Controller
{
     // Fungsi untuk Gaji Universitas
     public function Hitung_Pajak_Univ($request, $gajiunivId){

        $pajakpegawai = Dostap_Pajak::where('dostap_gaji_universitas_id', $gajiunivId)->get();
        foreach ($pajakpegawai as $pajak) {
         // Ambil data dari database
           $existingPajak = Dostap_Pajak::find($pajak->id);

           // Simpan nilai-nilai berikut pada properti $request
           $request->merge([
               'pensiun' => $existingPajak->pensiun,
               'aksa_mandiri' => $existingPajak->aksa_mandiri,
               'dplk_pensiun' => $existingPajak->dplk_pensiun,
               'ptkp' => $existingPajak->ptkp,
               'dostap_gaji_universitas_id' => $existingPajak->dostap_gaji_universitas_id,
               'dostap_gaji_fakultas_id' => $existingPajak->dostap_gaji_fakultas_id,
               'dostap_potongan_id' => $existingPajak->dostap_potongan_id,
           ]);

          // Hitung ulang nilai-nilai pajak berdasarkan perubahan data Gaji_Universitas
         $pajakobjek = new Dostap_Pajak;
         $bruto_pajak = $pajakobjek->hitung_bruto_pajak($request);
         $bruto_murni = $pajakobjek->hitung_bruto_murni($request);
         $biaya_jabatan = $pajakobjek->hitung_biaya_jabatan();
         $jml_pot_kn_pajak = $pajakobjek->hitung_jml_pot_kn_pajak($request);
         $jml_set_pot_kn_pajak = $pajakobjek->hitung_jml_set_pot_kn_pajak();
         $pkp = $pajakobjek->hitung_pkp($request);
         $pajak_pph21 = $pajakobjek->hitung_pajak_pph21($request);
         $jml_set_pajak = $pajakobjek->hitung_jml_set_pajak($request);
         $pot_tk_kena_pajak = $pajakobjek->hitung_pot_tk_kena_pajak($request);
         $pendapatan_bersih = $pajakobjek->hitung_pendapatan_bersih();


         $pajak -> update([
             'bruto_pajak' => $bruto_pajak,
             'bruto_murni' => $bruto_murni,
             'biaya_jabatan' => $biaya_jabatan,
             'jml_pot_kn_pajak' => $jml_pot_kn_pajak,
             'jml_set_pot_kn_pajak' => $jml_set_pot_kn_pajak,
             'pkp' => $pkp,
             'pajak_pph21' => $pajak_pph21,
             'jml_set_pajak' => $jml_set_pajak,
             'pot_tk_kena_pajak' => $pot_tk_kena_pajak,
             'pendapatan_bersih' => $pendapatan_bersih,
             'dosen_tetao_id' => $request->dosen_tetap_id,
         ]);
        }
     }

         // Fungsi untuk Gaji Fakultas
    public function Hitung_Pajak_Fak($request, $gajifakId){

        $pajakpegawai = Dostap_Pajak::where('dostap_gaji_fakultas_id', $gajifakId)->get();
        foreach ($pajakpegawai as $pajak) {
         // Ambil data dari database
           $existingPajak = Dostap_Pajak::find($pajak->id);

           // Simpan nilai-nilai berikut pada properti $request
           $request->merge([
               'pensiun' => $existingPajak->pensiun,
               'aksa_mandiri' => $existingPajak->aksa_mandiri,
               'dplk_pensiun' => $existingPajak->dplk_pensiun,
               'ptkp' => $existingPajak->ptkp,
               'dosen_tetap_id' => $existingPajak->dosen_tetap_id,
               'dostap_gaji_universitas_id' => $existingPajak->dostap_gaji_universitas_id,
               'dostap_gaji_fakultas_id' => $existingPajak->dostap_gaji_fakultas_id,
               'dostap_potongan_id' => $existingPajak->dostap_potongan_id
           ]);

          // Hitung ulang nilai-nilai pajak berdasarkan perubahan data Gaji_Universitas
         $pajakobjek = new Dostap_Pajak;
         $bruto_pajak = $pajakobjek->hitung_bruto_pajak($request);
         $bruto_murni = $pajakobjek->hitung_bruto_murni($request);
         $biaya_jabatan = $pajakobjek->hitung_biaya_jabatan();
         $jml_pot_kn_pajak = $pajakobjek->hitung_jml_pot_kn_pajak($request);
         $jml_set_pot_kn_pajak = $pajakobjek->hitung_jml_set_pot_kn_pajak();
         $pkp = $pajakobjek->hitung_pkp($request);
         $pajak_pph21 = $pajakobjek->hitung_pajak_pph21($request);
         $jml_set_pajak = $pajakobjek->hitung_jml_set_pajak($request);
         $pot_tk_kena_pajak = $pajakobjek->hitung_pot_tk_kena_pajak($request);
         $pendapatan_bersih = $pajakobjek->hitung_pendapatan_bersih();


         $pajak -> update([
             'bruto_pajak' => $bruto_pajak,
             'bruto_murni' => $bruto_murni,
             'biaya_jabatan' => $biaya_jabatan,
             'jml_pot_kn_pajak' => $jml_pot_kn_pajak,
             'jml_set_pot_kn_pajak' => $jml_set_pot_kn_pajak,
             'pkp' => $pkp,
             'pajak_pph21' => $pajak_pph21,
             'jml_set_pajak' => $jml_set_pajak,
             'pot_tk_kena_pajak' => $pot_tk_kena_pajak,
             'pendapatan_bersih' => $pendapatan_bersih
         ]);
        }
     }
    // Fungsi untuk Potongan
    public function Hitung_Pajak_Pot($request, $potonganId){

        $pajakpegawai = Dostap_Pajak::where('dostap_potongan_id', $potonganId)->get();
        foreach ($pajakpegawai as $pajak) {
         // Ambil data dari database
           $existingPajak = Dostap_Pajak::find($pajak->id);

           // Simpan nilai-nilai berikut pada properti $request
           $request->merge([
               'pensiun' => $existingPajak->pensiun,
               'aksa_mandiri' => $existingPajak->aksa_mandiri,
               'dplk_pensiun' => $existingPajak->dplk_pensiun,
               'ptkp' => $existingPajak->ptkp,
               'dosen_tetap_id' => $existingPajak->dosen_tetap_id,
               'dostap_gaji_universitas_id' => $existingPajak->dostap_gaji_universitas_id,
               'dostap_gaji_fakultas_id' => $existingPajak->dostap_gaji_fakultas_id,
               'dostap_potongan_id' => $existingPajak->dostap_potongan_id
           ]);

          // Hitung ulang nilai-nilai pajak berdasarkan perubahan data Gaji_Universitas
         $pajakobjek = new Dostap_Pajak;
         $bruto_pajak = $pajakobjek->hitung_bruto_pajak($request);
         $bruto_murni = $pajakobjek->hitung_bruto_murni($request);
         $biaya_jabatan = $pajakobjek->hitung_biaya_jabatan();
         $jml_pot_kn_pajak = $pajakobjek->hitung_jml_pot_kn_pajak($request);
         $jml_set_pot_kn_pajak = $pajakobjek->hitung_jml_set_pot_kn_pajak();
         $pkp = $pajakobjek->hitung_pkp($request);
         $pajak_pph21 = $pajakobjek->hitung_pajak_pph21($request);
         $jml_set_pajak = $pajakobjek->hitung_jml_set_pajak($request);
         $pot_tk_kena_pajak = $pajakobjek->hitung_pot_tk_kena_pajak($request);
         $pendapatan_bersih = $pajakobjek->hitung_pendapatan_bersih();


         $pajak -> update([
             'bruto_pajak' => $bruto_pajak,
             'bruto_murni' => $bruto_murni,
             'biaya_jabatan' => $biaya_jabatan,
             'jml_pot_kn_pajak' => $jml_pot_kn_pajak,
             'jml_set_pot_kn_pajak' => $jml_set_pot_kn_pajak,
             'pkp' => $pkp,
             'pajak_pph21' => $pajak_pph21,
             'jml_set_pajak' => $jml_set_pajak,
             'pot_tk_kena_pajak' => $pot_tk_kena_pajak,
             'pendapatan_bersih' => $pendapatan_bersih
         ]);
        }
     }
    }
