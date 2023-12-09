<?php

namespace App\Http\Controllers\API\dosenlb;

use App\Helpers\Wa;
use Illuminate\Http\Request;
use App\Helpers\ResponseFormatter;
use Illuminate\Support\Facades\App;
use App\Http\Controllers\Controller;
use App\Models\dosenlb\Doslb_Master_Transaksi;

class SlipGajiController extends Controller
{
    public function get($transaksiId){
        // Get Master Transaksi
        $transaksi = Doslb_Master_Transaksi::find($transaksiId);

        if (!$transaksi) {
            return ResponseFormatter::error('Master Transaksi Not Found', 404);
        }

         // Get data bulan dan tahun dari created_at
        $bulanTahun = $transaksi->created_at;

        // Get Dosen Luar Biasa
        $dosenlb = $transaksi->dosen_luar_biasa;

        // Get data Komponen Pendapatan
        $komponenPendapatan = $transaksi->komponen_pendapatan;
        $komponenPendapatan->komponen_pendapatan = json_decode($komponenPendapatan->komponen_pendapatan, true);

        // Get data Potongan
        $potongan = $transaksi->potongan;
        $potongan->potongan = json_decode($potongan->potongan, true);

        //Get data pendapatan bersih
        $pajak = $transaksi->pajak;
        $pendapatanBersih = $pajak->pendapatan_bersih;

        // Total Pendapatan
        $totalPendapatan= array_sum($komponenPendapatan->komponen_pendapatan);

        // Total Potongan
        $totalPotongan = array_sum($potongan->potongan);

        $slipgaji[] = [
                    'no_pegawai' => $dosenlb->no_pegawai,
                    'nama' => $dosenlb->nama,
                    'golongan' => $dosenlb->golongan,
                    'jabatan' => $dosenlb->jabatan,
                    'npwp' => $dosenlb->npwp,
                    'periode' => [
                    'month' => $bulanTahun->format('F'),
                    'year' => $bulanTahun->format('Y'),
                ],
                'pendapatan' => [
                    'komponen_pendapatan' => $komponenPendapatan,
                    'total_pendapatan'=> $totalPendapatan
                ],
                'potongan' => [
                    'potongan' => $potongan,
                    'total_potongan' => $totalPotongan
                ],
                'jumlah_diterima' => $pendapatanBersih
            ];

             return ResponseFormatter::success($slipgaji, 'Data Slip Gaji Dosen Luar Biasa Found');
    }

    public function generatePDF($transaksiId){
        // Get Master Transaksi
        $transaksi = Doslb_Master_Transaksi::find($transaksiId);

        if (!$transaksi) {
            return ResponseFormatter::error('Master Transaksi Not Found', 404);
        }

        // Get data bulan dan tahun dari created_at
        $bulanTahun = $transaksi->created_at->format('F Y');

        // Get Dosen Luar Biasa
        $dosenlb = $transaksi->dosen_luar_biasa;

        // Get data Komponen Pendapatan
        $komponenPendapatan = $transaksi->komponen_pendapatan;
        $komponenPendapatan->komponen_pendapatan = json_decode($komponenPendapatan->komponen_pendapatan, true);

        // Get data Potongan
        $potongan = $transaksi->potongan;
        $potongan->potongan = json_decode($potongan->potongan, true);
        //Get data pendapatan bersih
        $pajak = $transaksi->pajak;
        $pendapatanBersih = $pajak->pendapatan_bersih;

      // Total Pendapatan
      $totalPendapatan= array_sum($komponenPendapatan->komponen_pendapatan);

        // Total Potongan
        $totalPotongan = array_sum($potongan->potongan);


        $pdf = App::make('dompdf.wrapper');
        $pdf->loadView('slipgaji.dosenlb.index', compact('dosenlb', 'komponenPendapatan', 'potongan', 'pendapatanBersih', 'bulanTahun', 'totalPendapatan', 'totalPotongan'));

        return $pdf->stream('slipgaji.pdf');
        // Lihat Blade
        //  return view('slipgaji.dosentetap.index');
    }
    public function viewPDF($transaksiId){
        // Get Master Transaksi
        $transaksi = Doslb_Master_Transaksi::find($transaksiId);

        if (!$transaksi) {
            return ResponseFormatter::error('Master Transaksi Not Found', 404);
        }

        $filePath = "dosenlb/gaji/slip/pdf/$transaksiId";
        $fileUrl = url($filePath);

        return ResponseFormatter::success(['url'=>$fileUrl], 'PDF Slip Gaji Dosen Luar Biasa Found');
    }

    public function sendWA($transaksiId){
        // Get Master Transaksi
        $transaksi = Doslb_Master_Transaksi::find($transaksiId);

        if (!$transaksi) {
            return ResponseFormatter::error('Master Transaksi Not Found', 404);
        }
           // Get data bulan dan tahun dari created_at
           $bulanTahun = $transaksi->created_at->format('F Y');

           // Get Dosen Tetap
           $dosenlb = $transaksi->dosen_luar_biasa;

           // Get Nama and Nomor HP Dosen Tetap
           $namapegawai = $dosenlb->nama;
           $nomorhp = $dosenlb->nomor_hp;

       $filePath = "dosenlb/gaji/slip/pdf/$transaksiId";
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
