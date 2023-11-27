<?php

namespace App\Http\Controllers\API\dosenlb;

use App\Http\Controllers\Controller;
use App\Helpers\ResponseFormatter;
use App\Models\dosenlb\Doslb_Master_Transaksi;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function rekapitulasipendapatan() {
        // Get ALL Transaksi
        $transaksigaji = Doslb_Master_Transaksi::all();

        // Initialize the array to store the results
        $rekapitulasipendapatan = [];

        foreach ($transaksigaji as $gaji) {

            $dosenluarbiasa = $gaji->dosen_luar_biasa;
            $komponenpendapatan = $gaji->komponen_pendapatan;
            $komponenpendapatan->komponen_pendapatan = json_decode($komponenpendapatan->komponen_pendapatan, true);

            $totalPendapatan= array_sum($komponenpendapatan->komponen_pendapatan);

            $rekapitulasipendapatan[] = [
                'periode' => [
                    'month' => $gaji->created_at->format('F'),
                    'year' => $gaji->created_at->format('Y'),
                ],
                'rekapitulasipendapatan' => [
                    'no_pegawai' => $dosenluarbiasa->no_pegawai,
                    'nama' => $dosenluarbiasa->nama,
                    'golongan' => $dosenluarbiasa->golongan,
                    'komponen_pendapatan' => $komponenpendapatan->komponen_pendapatan,
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

        return ResponseFormatter::success(array_values($groupedData), 'Data Laporan Rekapitulasi Pendapatan Dosen Luar Biasa Found');
    }


    public function pendapatanbersih(Request $request)
    {
        // Get ALL Transaksi
        $transaksigaji = Doslb_Master_Transaksi::all();

        // Initialize the array to store the results
        $laporanpendapatanbersih = [];

        foreach ($transaksigaji as $gaji) {
            $dosenluarbiasa = $gaji->dosen_luar_biasa;
            $komponenpendapatan = $gaji->komponen_pendapatan;
            $komponenpendapatan->komponen_pendapatan = json_decode($komponenpendapatan->komponen_pendapatan, true);
            $potongan = $gaji->potongan;
            $potongan->potongan = json_decode($potongan->potongan, true);
            $pajak = $gaji->pajak;

            $totalPotongan = array_sum($potongan->potongan);
            $totalPendapatan= array_sum($komponenpendapatan->komponen_pendapatan);

            $laporanpendapatanbersih[] = [
                'periode' => [
                    'month' => $gaji->created_at->format('F'),
                    'year' => $gaji->created_at->format('Y'),
                ],
                'pendapatanbersih' => [
                    'no_pegawai' => $dosenluarbiasa->no_pegawai,
                    'nama' => $dosenluarbiasa->nama,
                    'golongan' => $dosenluarbiasa->golongan,
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

        return ResponseFormatter::success(array_values($groupedData), 'Data Laporan Pendapatan Bersih Dosen Luar Biasa Found');
    }


    public function laporanpajak(Request $request){
        // Get ALL Transaksi
        $transaksigaji = Doslb_Master_Transaksi::all();

        // Initialize the array to store the results
        $laporanpajak = [];

      foreach ($transaksigaji as $gaji) {

        $dosenluarbiasa = $gaji->dosen_luar_biasa;
        $komponenpendapatan = $gaji->komponen_pendapatan;
        $komponenpendapatan->komponen_pendapatan = json_decode($komponenpendapatan->komponen_pendapatan, true);
        $potongan = $gaji->potongan;
        $potongan->potongan = json_decode($potongan->potongan, true);
        $pajak = $gaji->pajak;

        $totalPotongan = array_sum($potongan->potongan);
        $totalPendapatan= array_sum($komponenpendapatan->komponen_pendapatan);

        $laporanpajak[] = [
            'periode' => [
                'month' => $gaji->created_at->format('F'),
                'year' => $gaji->created_at->format('Y'),
            ],
            'laporanpajak' => [
                'no_pegawai' => $dosenluarbiasa->no_pegawai,
                'nama' => $dosenluarbiasa->nama,
                'jumlah_pendapatan' => $totalPendapatan,
                'jumlah_potongan' => $totalPotongan,
                'pendapatan_bruto' => $totalPendapatan,
                'pajak' => [
                    'pajak_pph25' => $pajak->pajak_pph25,

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

    return ResponseFormatter::success(array_values($groupedData), 'Data Laporan Pajak Dosen Luar Biasa Found');
}

        public function laporanpotongan(Request $request){
        // Get ALL Transaksi
        $transaksigaji = Doslb_Master_Transaksi::all();

        // Initialize the array to store the results
        $laporanpotongan = [];


        foreach ($transaksigaji as $gaji) {

        $dosenluarbiasa = $gaji->dosen_luar_biasa;
        $potongan = $gaji->potongan;
        $potongan->potongan = json_decode($potongan->potongan, true);


        $laporanpotongan[] = [
            'periode' => [
                'month' => $gaji->created_at->format('F'),
                'year' => $gaji->created_at->format('Y'),
            ],
            'laporanpotongan' => [
                'no_pegawai' => $dosenluarbiasa->no_pegawai,
                'nama' => $dosenluarbiasa->nama,
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

        return ResponseFormatter::success(array_values($groupedData), 'Data Laporan Potongan Dosen Luar Biasa Found');
        }


    public function rekapitulasibank(){
            // Get ALL Transaksi
            $transaksigaji = Doslb_Master_Transaksi::all();
             // Initialize the array to store the results
            $rekapitulasibank = [];

            foreach ($transaksigaji as $gaji) {

                $dosenluarbiasa = $gaji->dosen_luar_biasa;
                $bank= $gaji->bank;
                $komponenpendapatan = $gaji->komponen_pendapatan;
                $komponenpendapatan->komponen_pendapatan = json_decode($komponenpendapatan->komponen_pendapatan, true);
                $potongan = $gaji->potongan;
                $potongan->potongan = json_decode($potongan->potongan, true);
                $pajak = $gaji->pajak;

                $totalPotongan = array_sum($potongan->potongan);
                $totalPendapatan= array_sum($komponenpendapatan->komponen_pendapatan);

    // menampilkan data dalam bentuk array
    $rekapitulasibankArray = [
        'periode' => [
            'month' => $gaji->created_at->format('F'),
            'year' => $gaji->created_at->format('Y'),
        ],
        'rekapitulasibank' => [
            'no_pegawai' => $dosenluarbiasa->no_pegawai,
            'nama' => $dosenluarbiasa->nama,
            'golongan' => $dosenluarbiasa->golongan,
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

return ResponseFormatter::success(array_values($rekapitulasibank), 'Data Laporan Rekapitulasi Bank Dosen Luar Biasa Found');
}
 }
