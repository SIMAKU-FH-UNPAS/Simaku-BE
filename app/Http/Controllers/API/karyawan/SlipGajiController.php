<?php

namespace App\Http\Controllers\API\karyawan;

use App\Helpers\Wa;
use App\Helpers\ResponseFormatter;
use Illuminate\Support\Facades\App;
use App\Http\Controllers\Controller;
use App\Models\karyawan\Karyawan_Master_Transaksi;
use DateTime;

class SlipGajiController extends Controller
{
    public function get($transaksiId){
        // Get Master Transaksi
        $transaksi = Karyawan_Master_Transaksi::find($transaksiId);

        if (!$transaksi) {
            return ResponseFormatter::error('Master Transaksi Not Found', 404);
        }

        // Get data bulan dan tahun dari gaji_date_end
        $bulanTahun = new DateTime($transaksi->gaji_date_end);

        // Get Karyawan
        $karyawan = $transaksi->karyawan;

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
                    'no_pegawai' => $karyawan->no_pegawai,
                    'nama' => $karyawan->nama,
                    'golongan' => $karyawan->golongan,
                    'jabatan' => $karyawan->jabatan,
                    'npwp' => $karyawan->npwp,
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

             return ResponseFormatter::success($slipgaji, 'Data Slip Gaji Karyawan Found');
    }

    public function generatePDF($transaksiId){
        // Get Master Transaksi
        $transaksi = Karyawan_Master_Transaksi::find($transaksiId);

        if (!$transaksi) {
            return ResponseFormatter::error('Master Transaksi Not Found', 404);
        }

        // Get data bulan dan tahun dari gaji_date_end
        $gaji_date_end = new DateTime($transaksi->gaji_date_end);
        $bulanTahun = $gaji_date_end->format('F Y');

        // Get Karyawan
        $karyawan = $transaksi->karyawan;

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
        $jumlahSetPotonganKenaPajak = $pajak->jumlah_set_potongan_kena_pajak;

        // Total Pendapatan
        $totalGajiUniv = $gajiUniv->gaji_pokok + $gajiUniv->tunjangan_fungsional + $gajiUniv->tunjangan_struktural + $gajiUniv->tunjangan_khusus_istimewa + $gajiUniv->tunjangan_presensi_kerja + $gajiUniv->tunjangan_tambahan + $gajiUniv->tunjangan_suami_istri + $gajiUniv->tunjangan_anak + $gajiUniv->uang_lembur_hk + $gajiUniv->uang_lembur_hl + $gajiUniv->transport_kehadiran + $gajiUniv->honor_universitas;
        $totalGajiFak = array_sum($gajiFak->gaji_fakultas);
        $totalPendapatan = $totalGajiUniv + $totalGajiFak;

        // Total Potongan
        $totalPotongan = array_sum($potongan->potongan);


        $pdf = App::make('dompdf.wrapper');
        $pdf->loadView('slipgaji.karyawan.index', compact('karyawan','gajiUniv', 'gajiFak', 'potongan', 'pendapatanBersih', 'bulanTahun', 'totalPendapatan', 'totalPotongan', 'jumlahSetPotonganKenaPajak'));

        return $pdf->stream('slipgaji.pdf');
        // Lihat Blade
        //  return view('slipgaji.dosentetap.index');
    }

    public function viewPDF($transaksiId){
        // Get Master Transaksi
        $transaksi = Karyawan_Master_Transaksi::find($transaksiId);

        if (!$transaksi) {
            return ResponseFormatter::error('Master Transaksi Not Found', 404);
        }

        $filePath = "karyawan/gaji/slip/pdf/$transaksiId";
        $fileUrl = url($filePath);

        return ResponseFormatter::success(['url'=>$fileUrl], 'PDF Slip Gaji Karyawan Found');
    }

    public function sendWA($transaksiId){
        // Get Master Transaksi
        $transaksi = Karyawan_Master_Transaksi::find($transaksiId);

        if (!$transaksi) {
            return ResponseFormatter::error('Master Transaksi Not Found', 404);
        }
            // Get data bulan dan tahun dari gaji_date_end , format (November 2023)
            $gaji_date_end = new DateTime($transaksi->gaji_date_end);
            $bulanTahun = $gaji_date_end->format('F Y');

           // Get Dosen Tetap
           $karyawan = $transaksi->karyawan;

           // Get Nama and Nomor HP Karyawan
           $namapegawai = $karyawan->nama;
           $nomorhp = $karyawan->nomor_hp;


           $filePath = "karyawan/gaji/slip/pdf/$transaksiId";
           $fileUrl = url($filePath);
       // Use the Wa helper to send WhatsApp message with the PDF
       $waHelper = new Wa();
       $nama = $namapegawai; // Set the recipient's name
       $hp = $nomorhp; // Set the recipient's phone number
       $pesan = 'Berikut merupakan rincian gaji pada periode '. $bulanTahun; // Set your custom message
       $responseStatus = $waHelper->waSend($nama, $hp, $pesan, $fileUrl);

       if ($responseStatus === 'success') {
           return ResponseFormatter::success('WhatsApp message sent with PDF', 200);
       } else {
           return ResponseFormatter::error('Failed to send WhatsApp message', 500);
       }

   }
}
