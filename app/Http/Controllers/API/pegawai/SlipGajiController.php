<?php

namespace App\Http\Controllers\API\pegawai;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\pegawai\PegawaiMasterTransaksi;
use Illuminate\Http\Request;
use DateTime;
use Illuminate\Support\Facades\App;

class SlipGajiController extends Controller
{
    public function get($id)
    {
        // Get Master Transaksi
        $transaksi = PegawaiMasterTransaksi::find($id);

        if (!$transaksi) {
            return ResponseFormatter::error('Master Transaksi Not Found', 404);
        }

        // Get data bulan dan tahun dari gaji_date_end
        $bulanTahun = new DateTime($transaksi->gaji_date_end);

        // Get Dosen Tetap
        $pegawai = $transaksi->pegawai;

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
            'no_pegawai' => $pegawai->no_pegawai,
            'nama' => $pegawai->nama,
            'golongan' => $pegawai->golongan,
            'jabatan' => $pegawai->jabatan,
            'npwp' => $pegawai->npwp,
            'periode' => [
                'month' => $bulanTahun->format('F'),
                'year' => $bulanTahun->format('Y'),
            ],
            'pendapatan' => [
                'gaji_universitas' => $gajiUniv,
                'gaji_fakultas' => $gajiFak,
                'total_pendapatan' => $totalPendapatan
            ],
            'potongan' => [
                'potongan' => $potongan,
                'total_potongan' => $totalPotongan
            ],
            'jumlah_diterima' => $pendapatanBersih
        ];

        return ResponseFormatter::success($slipgaji, 'Data Slip Gaji Dosen Tetap Found');
    }
}
