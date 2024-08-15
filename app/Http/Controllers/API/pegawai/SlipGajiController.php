<?php

namespace App\Http\Controllers\API\pegawai;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\pegawai\PegawaiMasterTransaksi;
use Illuminate\Http\Request;
use DateTime;
use Illuminate\Support\Facades\App;
use App\Helpers\Wa;

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

        $pajak = $transaksi->pajak;
        $pajak_pph21 = [
            'pajak_pph21' => $pajak->pajak_pph21
        ];

        $arraytest = [];

        $gabung1 = array_merge($arraytest, $potongan->potongan);
        $potonganDanPajak = array_merge($gabung1, $pajak_pph21);

        //Get data pendapatan bersih
        $pajak = $transaksi->pajak;
        $pendapatanBersih = $pajak->pendapatan_bersih;

        // Total Pendapatan
        $totalGajiUniv = $gajiUniv->gaji_pokok + $gajiUniv->tunjangan_fungsional + $gajiUniv->tunjangan_struktural + $gajiUniv->tunjangan_khusus_istimewa + $gajiUniv->tunjangan_presensi_kerja + $gajiUniv->tunjangan_tambahan + $gajiUniv->tunjangan_suami_istri + $gajiUniv->tunjangan_anak + $gajiUniv->uang_lembur_hk + $gajiUniv->uang_lembur_hl + $gajiUniv->transport_kehadiran + $gajiUniv->honor_universitas;
        $totalGajiFak = array_sum($gajiFak->gaji_fakultas);
        $totalPendapatan = $totalGajiUniv + $totalGajiFak;

        // Total Potongan
        $totalPotongan = array_sum($potonganDanPajak);

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
                'potongan' => $potonganDanPajak,
                'total_potongan' => $totalPotongan
            ],
            'jumlah_diterima' => $pendapatanBersih
        ];

        return ResponseFormatter::success($slipgaji, 'Data Slip Gaji Dosen Tetap Found');
    }

    public function viewPDF($id)
    {
        $transaksi = PegawaiMasterTransaksi::find($id);

        if (!$transaksi) {
            return ResponseFormatter::error('Master Transaksi Not Found', 404);
        }

        // Get data bulan dan tahun dari gaji_date_end
        $gaji_date_end = new DateTime($transaksi->gaji_date_end);
        $bulanTahun = $gaji_date_end->format('F Y');

        // Get Dosen Tetap
        $pegawai = $transaksi->pegawai;

        //Get Gaji Univ
        $gajiUniv = $transaksi->gaji_universitas;

        // Get data Gaji Fakultas
        $gajiFak = $transaksi->gaji_fakultas;
        $gajiFak->gaji_fakultas = json_decode($gajiFak->gaji_fakultas, true);
        // Get data Potongan
        $potonganPajak = $transaksi->potongan;
        $potonganPajak->potongan = json_decode($potonganPajak->potongan, true);

        $pajak = $transaksi->pajak;
        $pajak_pph21 = [
            'pajak_pph21' => $pajak->pajak_pph21
        ];

        $arraytest = [];

        $gabung1 = array_merge($arraytest, $potonganPajak->potongan);
        $potongan = array_merge($gabung1, $pajak_pph21);

        //Get data pendapatan bersih
        $pajak = $transaksi->pajak;
        // $pendapatanBersih = $pajak->pendapatan_bersih;
        $jumlahSetPotonganKenaPajak = $pajak->jumlah_set_potongan_kena_pajak;

        // Total Pendapatan
        $totalGajiUniv = $gajiUniv->gaji_pokok + $gajiUniv->tunjangan_fungsional + $gajiUniv->tunjangan_struktural + $gajiUniv->tunjangan_khusus_istimewa + $gajiUniv->tunjangan_presensi_kerja + $gajiUniv->tunjangan_tambahan + $gajiUniv->tunjangan_suami_istri + $gajiUniv->tunjangan_anak + $gajiUniv->uang_lembur_hk + $gajiUniv->uang_lembur_hl + $gajiUniv->transport_kehadiran + $gajiUniv->honor_universitas;
        $totalGajiFak = array_sum($gajiFak->gaji_fakultas);
        $totalPendapatan = $totalGajiUniv + $totalGajiFak;

        // Total Potongan
        $totalPotongan = array_sum($potongan);
        $pendapatanBersih = ($totalPendapatan - $totalPotongan);

        $pdf = App::make('dompdf.wrapper');
        $customPaper = array(0, 0, 12 / 2.54 * 72, 30 / 2.54 * 72);
        $pdf->setPaper($customPaper);
        $pdf->loadView('slipgaji.dosentetap.gaji', compact('pegawai', 'gajiUniv', 'gajiFak', 'potongan', 'pendapatanBersih', 'bulanTahun', 'totalPendapatan', 'totalPotongan', 'jumlahSetPotonganKenaPajak'));

        return $pdf->stream('slipgaji.pdf');
    }

    public function sample($id)
    {
        // Get Master Transaksi
        $transaksi = PegawaiMasterTransaksi::find($id);

        if (!$transaksi) {
            return ResponseFormatter::error('Master Transaksi Not Found', 404);
        }

        $filePath = "pegawai/gaji/slip/pdf/$id";
        $fileUrl = url($filePath);

        return ResponseFormatter::success(['url' => $fileUrl], 'PDF Slip Gaji Dosen Tetap Found');
    }

    public function sendWA($id)
    {
        // Get Master Transaksi
        $transaksi = PegawaiMasterTransaksi::find($id);

        if (!$transaksi) {
            return ResponseFormatter::error('Master Transaksi Not Found', 404);
        }
        // Get data bulan dan tahun dari gaji_date_end , format (November 2023)
        $gaji_date_end = new DateTime($transaksi->gaji_date_end);
        $bulanTahun = $gaji_date_end->format('F Y');

        // Get Dosen Tetap
        $pegawai = $transaksi->pegawai;

        // Get Nama and Nomor HP Dosen Tetap
        $namapegawai = $pegawai->nama;
        $nomorhp = $pegawai->nomor_hp;

        $filePath = "pegawai/gaji/slip/cetak/$id";
        $fileUrl = url($filePath);

        // Use the Wa helper to send WhatsApp message with the PDF
        $waHelper = new Wa();
        $nama = $namapegawai; // Set the recipient's name
        $hp = $nomorhp; // Set the recipient's phone number
        $pesan = 'Berikut merupakan rincian gaji pada periode ' . $bulanTahun; // Set your custom message
        $responseStatus = $waHelper->waSend($nama, $hp, $pesan, $fileUrl);

        if ($responseStatus === 'success') {
            return ResponseFormatter::success('WhatsApp message sent with PDF', 200);
        } else {
            return ResponseFormatter::error('Failed to send WhatsApp message', 500);
        }
    }
}
