<?php

namespace App\Http\Controllers\API\karyawan;

use App\Http\Controllers\Controller;
use App\Helpers\ResponseFormatter;
use App\Models\karyawan\Karyawan_Master_Transaksi;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function rekapitulasipendapatan() {
        // Get ALL Transaksi
        $transaksigaji = Karyawan_Master_Transaksi::all();

        // Initialize the array to store the results
        $rekapitulasipendapatan = [];

        foreach ($transaksigaji as $gaji) {

            $karyawan = $gaji->karyawan;
            $gajiuniv = $gaji->gaji_universitas;
            $gajifakultas = $gaji->gaji_fakultas;
            $gajifakultas->gaji_fakultas = json_decode($gajifakultas->gaji_fakultas, true);

            $totalGajiUniv = $gajiuniv->gaji_pokok + $gajiuniv->tunjangan_fungsional + $gajiuniv->tunjangan_struktural + $gajiuniv->tunjangan_khusus_istimewa + $gajiuniv->tunjangan_presensi_kerja + $gajiuniv->tunjangan_tambahan + $gajiuniv->tunjangan_suami_istri + $gajiuniv->tunjangan_anak + $gajiuniv->uang_lembur_hk + $gajiuniv->uang_lembur_hl + $gajiuniv->transport_kehadiran + $gajiuniv->honor_universitas;
            $totalGajiFak = array_sum($gajifakultas->gaji_fakultas);
            $totalPendapatan = $totalGajiUniv + $totalGajiFak;

            $rekapitulasipendapatan[] = [
                'periode' => [
                    'month' => $gaji->created_at->format('F'),
                    'year' => $gaji->created_at->format('Y'),
                ],
                'rekapitulasipendapatan' => [
                    'no_pegawai' => $karyawan->no_pegawai,
                    'nama' => $karyawan->nama,
                    'golongan' => $karyawan->golongan,
                    'gaji_fakultas' => $gajifakultas->gaji_fakultas,
                    'jumlah_gaji_fh' => $totalGajiFak,
                    'jumlah_gaji_pusat' => $totalGajiUniv,
                    'total' => $totalPendapatan
                ],
            ];
        }

        // Group the data by unique month and year
        $groupedData = [];
        foreach ($rekapitulasipendapatan as $item) {
            $groupKey = $item['periode']['year'] . $item['periode']['month'];
            $groupedData[$groupKey]['periode'] = $item['periode'];
            $groupedData[$groupKey]['rekapitulasipendapatan'][] = $item['rekapitulasipendapatan'];
        }

        // Check if the rekapitulasipendapatan array is empty
        if (empty($groupedData)) {
            return ResponseFormatter::error(null, 'No data found for the specified month and year', 404);
        }

        return ResponseFormatter::success(array_values($groupedData), 'Data Laporan Rekapitulasi Pendapatan Karyawan Found');
    }

    public function pendapatanbersih(Request $request)
    {
        // Get ALL Transaksi
        $transaksigaji = Karyawan_Master_Transaksi::all();

        // Initialize the array to store the results
        $laporanpendapatanbersih = [];

        foreach ($transaksigaji as $gaji) {
            $karyawan = $gaji->karyawan;
            $gajiuniv = $gaji->gaji_universitas;
            $gajifakultas = $gaji->gaji_fakultas;
            $gajifakultas->gaji_fakultas = json_decode($gajifakultas->gaji_fakultas, true);
            $potongan = $gaji->potongan;
            $potongan->potongan = json_decode($potongan->potongan, true);
            $pajak = $gaji->pajak;

            $totalGajiUniv = $gajiuniv->gaji_pokok + $gajiuniv->tunjangan_fungsional + $gajiuniv->tunjangan_struktural + $gajiuniv->tunjangan_khusus_istimewa + $gajiuniv->tunjangan_presensi_kerja + $gajiuniv->tunjangan_tambahan + $gajiuniv->tunjangan_suami_istri + $gajiuniv->tunjangan_anak + $gajiuniv->uang_lembur_hk + $gajiuniv->uang_lembur_hl + $gajiuniv->transport_kehadiran + $gajiuniv->honor_universitas;
            $totalGajiFak = array_sum($gajifakultas->gaji_fakultas);
            $totalPotongan = array_sum($potongan->potongan);
            $totalPendapatan = $totalGajiUniv + $totalGajiFak;

            $laporanpendapatanbersih[] = [
                'periode' => [
                    'month' => $gaji->created_at->format('F'),
                    'year' => $gaji->created_at->format('Y'),
                ],
                'pendapatanbersih' => [
                    'no_pegawai' => $karyawan->no_pegawai,
                    'nama' => $karyawan->nama,
                    'golongan' => $karyawan->golongan,
                    'jumlah_gaji_fh' => $totalGajiFak,
                    'jumlah_gaji_pusat' => $totalGajiUniv,
                    'jumlah_pendapatan' => $totalPendapatan,
                    'jumlah_potongan' => $totalPotongan,
                    'pendapatan_bersih' => $pajak->pendapatan_bersih,
                ],
            ];
        }

        // Group the data by unique month and year
        $groupedData = [];
        foreach ($laporanpendapatanbersih as $item) {
            $groupKey = $item['periode']['year'] . $item['periode']['month'];
            $groupedData[$groupKey]['periode'] = $item['periode'];
            $groupedData[$groupKey]['pendapatanbersih'][] = $item['pendapatanbersih'];
        }

        // Check if the rekapitulasipendapatan array is empty
        if (empty($groupedData)) {
            return ResponseFormatter::error(null, 'No data found for the specified month and year', 404);
        }

        return ResponseFormatter::success(array_values($groupedData), 'Data Laporan Pendapatan Bersih Karyawan Found');
    }

    public function laporanpajak(Request $request){
        // Get ALL Transaksi
        $transaksigaji = Karyawan_Master_Transaksi::all();

        // Initialize the array to store the results
        $laporanpajak = [];

      foreach ($transaksigaji as $gaji) {

        $karyawan = $gaji->karyawan;
        $gajiuniv = $gaji->gaji_universitas;
        $gajifakultas = $gaji->gaji_fakultas;
        $gajifakultas->gaji_fakultas = json_decode($gajifakultas->gaji_fakultas, true);
        $potongan = $gaji->potongan;
        $potongan->potongan = json_decode($potongan->potongan, true);
        $pajak = $gaji->pajak;

        $totalGajiUniv = $gajiuniv->gaji_pokok + $gajiuniv->tunjangan_fungsional + $gajiuniv->tunjangan_struktural + $gajiuniv->tunjangan_khusus_istimewa + $gajiuniv->tunjangan_presensi_kerja + $gajiuniv->tunjangan_tambahan + $gajiuniv->tunjangan_suami_istri + $gajiuniv->tunjangan_anak + $gajiuniv->uang_lembur_hk + $gajiuniv->uang_lembur_hl + $gajiuniv->transport_kehadiran + $gajiuniv->honor_universitas;
        $totalGajiFak = array_sum($gajifakultas->gaji_fakultas);
        $totalPotongan = array_sum($potongan->potongan);

        $laporanpajak[] = [
            'periode' => [
                'month' => $gaji->created_at->format('F'),
                'year' => $gaji->created_at->format('Y'),
            ],
            'laporanpajak' => [
                'no_pegawai' => $karyawan->no_pegawai,
                'nama' => $karyawan->nama,
                'gaji_pusat' => $totalGajiUniv,
                'gaji_fh' => $totalGajiFak,
                'jumlah_potongan' => $totalPotongan,
                'pensiun' => $pajak->pensiun,
                'pendapatan_bruto' => $pajak->bruto_pajak,
                'pajak' => [
                    'bruto_murni' => $pajak->bruto_murni,
                    'biaya_jabatan' => $pajak->biaya_jabatan,
                    'aksa_mandiri' => $pajak->aksa_mandiri,
                    'dplk_pensiun' => $pajak->dplk_pensiun,
                    'jumlah_potongan_kena_pajak' => $pajak->jumlah_potongan_kena_pajak,
                    'jumlah_set_potongan_kena_pajak' => $pajak->jumlah_set_potongan_kena_pajak,
                    'ptkp' => $pajak->ptkp,
                    'pkp' => $pajak->pkp,
                    'pajak_pph21' => $pajak->pajak_pph21,
                    'jumlah_set_pajak' => $pajak->jumlah_set_pajak,
                    'potongan_tak_kena_pajak' => $pajak->potongan_tak_kena_pajak,
                    'pendapatan_bersih' => $pajak->pendapatan_bersih,
                ],
            ],
        ];
    }

    // Group the data by unique month and year
    $groupedData = [];
    foreach ($laporanpajak as $item) {
        $groupKey = $item['periode']['year'] . $item['periode']['month'];
        $groupedData[$groupKey]['periode'] = $item['periode'];
        $groupedData[$groupKey]['laporanpajak'][] = $item['laporanpajak'];
    }

    // Check if the rekapitulasipendapatan array is empty
    if (empty($groupedData)) {
        return ResponseFormatter::error(null, 'No data found for the specified month and year', 404);
    }

    return ResponseFormatter::success(array_values($groupedData), 'Data Laporan Pajak Karyawan Found');
}

    public function laporanpotongan(Request $request){
        // Get ALL Transaksi
        $transaksigaji = Karyawan_Master_Transaksi::all();

        // Initialize the array to store the results
        $laporanpotongan = [];


        foreach ($transaksigaji as $gaji) {

        $karyawan = $gaji->karyawan;
        $potongan = $gaji->potongan;
        $potongan->potongan = json_decode($potongan->potongan, true);


        $laporanpotongan[] = [
            'periode' => [
                'month' => $gaji->created_at->format('F'),
                'year' => $gaji->created_at->format('Y'),
            ],
            'laporanpotongan' => [
                'no_pegawai' => $karyawan->no_pegawai,
                'nama' => $karyawan->nama,
                'potongan' => $potongan->potongan,
            ],
        ];
        }

        // Group the data by unique month and year
        $groupedData = [];
        foreach ($laporanpotongan as $item) {
        $groupKey = $item['periode']['year'] . $item['periode']['month'];
        $groupedData[$groupKey]['periode'] = $item['periode'];
        $groupedData[$groupKey]['laporanpotongan'][] = $item['laporanpotongan'];
        }

        // Check if the rekapitulasipendapatan array is empty
        if (empty($groupedData)) {
        return ResponseFormatter::error(null, 'No data found for the specified month and year', 404);
        }

        return ResponseFormatter::success(array_values($groupedData), 'Data Laporan Potongan Karyawan Found');
        }

    public function rekapitulasibank(){
            // Get ALL Transaksi
            $transaksigaji = Karyawan_Master_Transaksi::all();
             // Initialize the array to store the results
            $rekapitulasibank = [];

            foreach ($transaksigaji as $gaji) {

                $karyawan = $gaji->karyawan;
                $bank= $gaji->bank;
                $gajiuniv = $gaji->gaji_universitas;
                $gajifakultas = $gaji->gaji_fakultas;
                $gajifakultas->gaji_fakultas = json_decode($gajifakultas->gaji_fakultas, true);
                $potongan = $gaji->potongan;
                $potongan->potongan = json_decode($potongan->potongan, true);
                $pajak = $gaji->pajak;

                $totalGajiUniv = $gajiuniv->gaji_pokok + $gajiuniv->tunjangan_fungsional + $gajiuniv->tunjangan_struktural + $gajiuniv->tunjangan_khusus_istimewa + $gajiuniv->tunjangan_presensi_kerja + $gajiuniv->tunjangan_tambahan + $gajiuniv->tunjangan_suami_istri + $gajiuniv->tunjangan_anak + $gajiuniv->uang_lembur_hk + $gajiuniv->uang_lembur_hl + $gajiuniv->transport_kehadiran + $gajiuniv->honor_universitas;
                $totalGajiFak = array_sum($gajifakultas->gaji_fakultas);
                $totalPotongan = array_sum($potongan->potongan);
                $totalPendapatan = $totalGajiUniv + $totalGajiFak;


    // menampilkan data dalam bentuk array
    $rekapitulasibankArray = [
        'periode' => [
            'month' => $gaji->created_at->format('F'),
            'year' => $gaji->created_at->format('Y'),
        ],
        'rekapitulasibank' => [
            'no_pegawai' => $karyawan->no_pegawai,
            'nama' => $karyawan->nama,
            'golongan' => $karyawan->golongan,
            'jumlah_pendapatan' => $totalPendapatan,
            'jumlah_potongan' => $totalPotongan,
            'pendapatan_bersih' => $pajak->pendapatan_bersih,
            'no_rekening' => $bank->no_rekening,
            'nama_bank' => $gaji->bank->nama_bank,
        ],
    ];

  // Add to the appropriate array based on the condition
  $bankType = ($gaji->bank->nama_bank === 'Bank Mandiri') ? 'payroll' : 'non_payroll';
  $rekapitulasibankKey = $rekapitulasibankArray['periode']['year'] . $rekapitulasibankArray['periode']['month'];

  if (!isset($rekapitulasibank[$rekapitulasibankKey])) {
      $rekapitulasibank[$rekapitulasibankKey] = [
          'periode' => $rekapitulasibankArray['periode'],
          'payroll' => [],
          'non_payroll' => [],
      ];
  }

  $rekapitulasibank[$rekapitulasibankKey][$bankType][] = $rekapitulasibankArray['rekapitulasibank'];
}

return ResponseFormatter::success(array_values($rekapitulasibank), 'Data Laporan Rekapitulasi Bank Karyawan Found');
}
}
