<?php

namespace App\Http\Controllers\API\dosentetap;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\PDF as PDF;
use App\Helpers\ResponseFormatter;
use Illuminate\Support\Facades\App;
use App\Http\Controllers\Controller;
use App\Models\dosentetap\Dostap_Master_Transaksi;

class SlipGajiController extends Controller
{
    public function get($transaksiId){
        // Get Master Transaksi
        $transaksi = Dostap_Master_Transaksi::find($transaksiId);

        if (!$transaksi) {
            return ResponseFormatter::error('Master Transaksi Not Found', 404);
        }

         // Get data bulan dan tahun dari created_at
        $bulanTahun = $transaksi->created_at;

        // Get Dosen Tetap
        $dosentetap = $transaksi->dosen_tetap;

        //Get Gaji Univ
        $gajiUniv = $transaksi->gaji_universitas;

        // Get data Gaji Fakultas
        $gajiFak = $transaksi->gaji_fakultas;
        $gajiFak->gaji_fakultas = json_decode($gajiFak->gaji_fakultas, true);
        // Get data Potongan
        $potongan = $transaksi->potongan;
        $potongan->potongan = json_decode($potongan->potongan, true);
        //Get data pendapatan bersih
        $pajak = $transaksi->pajak;
        $pendapatanBersih = $pajak->pendapatan_bersih;

        // Total Pendapatan
        $totalGajiUniv = $gajiUniv->gaji_pokok + $gajiUniv->tunjangan_fungsional + $gajiUniv->tunjangan_struktural + $gajiUniv->tunjangan_khusus_istimewa + $gajiUniv->tunjangan_presensi_kerja + $gajiUniv->tunjangan_tambahan + $gajiUniv->tunjangan_suami_istri + $gajiUniv->tunjangan_anak + $gajiUniv->uang_lembur_hk + $gajiUniv->uang_lembur_hl + $gajiUniv->transport_kehadiran + $gajiUniv->honor_universitas;
        $totalGajiFak = array_sum($gajiFak->gaji_fakultas);
        $totalPendapatan = $totalGajiUniv + $totalGajiFak;

        // Total Potongan
        $totalPotongan = array_sum($potongan->potongan);

        $slipgaji[] = [
                    'no_pegawai' => $dosentetap->no_pegawai,
                    'nama' => $dosentetap->nama,
                    'golongan' => $dosentetap->golongan,
                    'jabatan' => $dosentetap->jabatan,
                    'npwp' => $dosentetap->npwp,
                    'periode' => [
                    'month' => $bulanTahun->format('F'),
                    'year' => $bulanTahun->format('Y'),
                ],
                'pendapatan' => [
                    'gaji_universitas' => $gajiUniv,
                    'gaji_fakultas' => $gajiFak,
                    'total_pendapatan'=> $totalPendapatan
                ],
                'potongan' => [
                    'potongan' => $potongan,
                    'total_potongan' => $totalPotongan
                ],
                'jumlah_diterima' => $pendapatanBersih
            ];

             return ResponseFormatter::success($slipgaji, 'Data Slip Gaji Dosen Tetap Found');
    }


    public function generatePDF($transaksiId){
        // Get Master Transaksi
        $transaksi = Dostap_Master_Transaksi::find($transaksiId);

        if (!$transaksi) {
            return ResponseFormatter::error('Master Transaksi Not Found', 404);
        }

        // Get data bulan dan tahun dari created_at
        $bulanTahun = $transaksi->created_at->format('F Y');

        // Get Dosen Tetap
        $dosentetap = $transaksi->dosen_tetap;

        //Get Gaji Univ
        $gajiUniv = $transaksi->gaji_universitas;

        // Get data Gaji Fakultas
        $gajiFak = $transaksi->gaji_fakultas;
        $gajiFak->gaji_fakultas = json_decode($gajiFak->gaji_fakultas, true);
        // Get data Potongan
        $potongan = $transaksi->potongan;
        $potongan->potongan = json_decode($potongan->potongan, true);
        //Get data pendapatan bersih
        $pajak = $transaksi->pajak;
        $pendapatanBersih = $pajak->pendapatan_bersih;

        // Total Pendapatan
        $totalGajiUniv = $gajiUniv->gaji_pokok + $gajiUniv->tunjangan_fungsional + $gajiUniv->tunjangan_struktural + $gajiUniv->tunjangan_khusus_istimewa + $gajiUniv->tunjangan_presensi_kerja + $gajiUniv->tunjangan_tambahan + $gajiUniv->tunjangan_suami_istri + $gajiUniv->tunjangan_anak + $gajiUniv->uang_lembur_hk + $gajiUniv->uang_lembur_hl + $gajiUniv->transport_kehadiran + $gajiUniv->honor_universitas;
        $totalGajiFak = array_sum($gajiFak->gaji_fakultas);
        $totalPendapatan = $totalGajiUniv + $totalGajiFak;

        // Total Potongan
        $totalPotongan = array_sum($potongan->potongan);


        $pdf = App::make('dompdf.wrapper');
        $pdf->loadView('slipgaji.dosentetap.index', compact('dosentetap','gajiUniv', 'gajiFak', 'potongan', 'pendapatanBersih', 'bulanTahun', 'totalPendapatan', 'totalPotongan'));

        return $pdf->stream('slipgaji.pdf');
        // Lihat Blade
        //  return view('slipgaji.dosentetap.index');
    }
}
