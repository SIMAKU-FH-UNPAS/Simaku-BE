<?php

namespace App\Http\Controllers\API\dosenlb;

use Exception;
use Carbon\Carbon;
use App\Helpers\ResponseFormatter;
use Illuminate\Support\Facades\DB;
use App\Models\dosenlb\Doslb_Pajak;
use App\Http\Controllers\Controller;
use App\Http\Requests\dosenlb\CreateKomponenPendapatanRequest;
use App\Http\Requests\dosenlb\CreatePajakRequest;
use App\Http\Requests\dosenlb\CreatePotonganRequest;
use App\Http\Requests\dosenlb\CreateMasterTransaksiRequest;
use App\Http\Requests\dosenlb\UpdateKomponenPendapatanRequest;
use App\Http\Requests\dosenlb\UpdateMasterTransaksiRequest;
use App\Http\Requests\dosenlb\UpdatePajakRequest;
use App\Http\Requests\dosenlb\UpdatePotonganRequest;
use App\Models\dosenlb\Doslb_Potongan;
use App\Models\dosenlb\Dosen_Luar_Biasa;
use App\Models\dosenlb\Doslb_Komponen_Pendapatan;
use App\Models\dosenlb\Doslb_Master_Transaksi;

class TransaksiGajiController extends Controller
{
      // Ambil Data Transaksi Gaji sesuai id dosenluarbiasa
   public function fetch($dosenlbId)
   {
       // Get dosen luar biasa
       $dosenluarbiasa = Dosen_Luar_Biasa::find($dosenlbId);

       if (!$dosenluarbiasa) {
           return ResponseFormatter::error(null, 'Dosen Luar Biasa Not Found', 404);
       }

       // Inisialisasi data Dosen Luar Biasa
       $transaksi = [
           'dosen_luar_biasa_id' => $dosenluarbiasa->id,
           'no_pegawai' => $dosenluarbiasa->no_pegawai,
           'nama' => $dosenluarbiasa->nama,
           'npwp' => $dosenluarbiasa->npwp,
           'golongan' => $dosenluarbiasa->golongan,
           'jabatan' => $dosenluarbiasa->jabatan,
           'nomor_hp' => $dosenluarbiasa->nomor_hp,
           'transaksi' => [],
       ];

       // Get ALL DATA Master Transaksi
       $transaksigaji = Doslb_Master_Transaksi::where('dosen_luar_biasa_id', $dosenluarbiasa->id)->get();

       foreach ($transaksigaji as $gaji) {
            // Get Periode
            $gaji_date_start = Carbon::createFromFormat('Y-m-d', $gaji->gaji_date_start);
            $gaji_date_end = Carbon::createFromFormat('Y-m-d', $gaji->gaji_date_end);


           $bankMasterTransaksi = Doslb_Master_Transaksi::with(['bank'])
               ->where('dosen_luar_biasa_id', $dosenluarbiasa->id)
               ->where('gaji_date_start', $gaji_date_start->format('Y-m-d'))
               ->where('gaji_date_end', $gaji_date_end->format('Y-m-d'))
               ->get();

           $komponenpendapatanMasterTransaksi = Doslb_Master_Transaksi::with('komponen_pendapatan')
               ->where('dosen_luar_biasa_id', $dosenluarbiasa->id)
               ->where('gaji_date_start', $gaji_date_start->format('Y-m-d'))
               ->where('gaji_date_end', $gaji_date_end->format('Y-m-d'))
               ->get();
               $komponenpendapatanMasterTransaksi->transform(function ($item) {
               $item->komponen_pendapatan->komponen_pendapatan = json_decode($item->komponen_pendapatan->komponen_pendapatan);
               return $item;
           });

           $potonganMasterTransaksi = Doslb_Master_Transaksi::with('potongan')
               ->where('dosen_luar_biasa_id', $dosenluarbiasa->id)
               ->where('gaji_date_start', $gaji_date_start->format('Y-m-d'))
               ->where('gaji_date_end', $gaji_date_end->format('Y-m-d'))
               ->get();
           $potonganMasterTransaksi->transform(function ($item) {
               $item->potongan->potongan = json_decode($item->potongan->potongan);
               return $item;
           });

           $pajakMasterTransaksi = Doslb_Master_Transaksi::with(['pajak'])
               ->where('dosen_luar_biasa_id', $dosenluarbiasa->id)
               ->where('gaji_date_start', $gaji_date_start->format('Y-m-d'))
               ->where('gaji_date_end', $gaji_date_end->format('Y-m-d'))
               ->get();

           // Transformasi data transaksi
           $transaksiData = [
               'id'=>$gaji->id,
               'periode' => [
                'month' => $gaji_date_end->format('F'),
                'year' => $gaji_date_end->format('Y'),
                ],
               'bank' => $bankMasterTransaksi->pluck('bank')->toArray(),
               'status_bank'=> $gaji->status_bank,
               'komponen_pendapatan' => $komponenpendapatanMasterTransaksi->pluck('komponen_pendapatan')->toArray(),
               'potongan' => $potonganMasterTransaksi->pluck('potongan')->toArray(),
               'pajak' => $pajakMasterTransaksi->pluck('pajak')->toArray()
           ];

           // Menambahkan data transaksi ke dalam array transaksi utama
           $transaksi['transaksi'][] = $transaksiData;
       }
       return ResponseFormatter::success($transaksi, 'Data Transaksi Gaji Dosen Luar Biasa Found');

   }

   // Ambil Data Transaksi Gaji sesuai id transaksi
public function fetchById($transaksiId)
{
    // Get Master Transaksi
    $masterTransaksi = Doslb_Master_Transaksi::find($transaksiId);

    if (!$masterTransaksi) {
        return ResponseFormatter::error('Master Transaksi Not Found', 404);
    }

      // Get Periode
      $gaji_date_start = Carbon::createFromFormat('Y-m-d', $masterTransaksi->gaji_date_start);
      $gaji_date_end = Carbon::createFromFormat('Y-m-d', $masterTransaksi->gaji_date_end);

    // Get dosen luar biasa
    $dosenluarbiasa = Dosen_Luar_Biasa::with('banks')->find($masterTransaksi->dosen_luar_biasa_id);
    if (!$dosenluarbiasa) {
        return ResponseFormatter::error(null, 'Dosen Luar Biasa Not Found', 404);
    }

    // Inisialisasi data dosen luar biasa
        $transaksi = [
            'dosen_luar_biasa_id' => $dosenluarbiasa->id,
            'no_pegawai' => $dosenluarbiasa->no_pegawai,
            'nama' => $dosenluarbiasa->nama,
            'npwp' => $dosenluarbiasa->npwp,
            'golongan' => $dosenluarbiasa->golongan,
            'jabatan' => $dosenluarbiasa->jabatan,
            'nomor_hp' => $dosenluarbiasa->nomor_hp,
            'banks' => $dosenluarbiasa->banks,
            'transaksi' => [],
        ];

    $bankMasterTransaksi = Doslb_Master_Transaksi::with(['bank'])
        ->where('dosen_luar_biasa_id', $dosenluarbiasa->id)
        ->where('gaji_date_start', $gaji_date_start->format('Y-m-d'))
        ->where('gaji_date_end', $gaji_date_end->format('Y-m-d'))
        ->get();

    $komponenpendapatanMasterTransaksi = Doslb_Master_Transaksi::with('komponen_pendapatan')
        ->where('dosen_luar_biasa_id', $dosenluarbiasa->id)
        ->where('gaji_date_start', $gaji_date_start->format('Y-m-d'))
        ->where('gaji_date_end', $gaji_date_end->format('Y-m-d'))
        ->get();
        $komponenpendapatanMasterTransaksi->transform(function ($item) {
        $item->komponen_pendapatan->komponen_pendapatan = json_decode($item->komponen_pendapatan->komponen_pendapatan);
        return $item;
    });

    $potonganMasterTransaksi = Doslb_Master_Transaksi::with('potongan')
        ->where('dosen_luar_biasa_id', $dosenluarbiasa->id)
        ->where('gaji_date_start', $gaji_date_start->format('Y-m-d'))
        ->where('gaji_date_end', $gaji_date_end->format('Y-m-d'))
        ->get();
    $potonganMasterTransaksi->transform(function ($item) {
        $item->potongan->potongan = json_decode($item->potongan->potongan);
        return $item;
    });

    $pajakMasterTransaksi = Doslb_Master_Transaksi::with(['pajak'])
        ->where('dosen_luar_biasa_id', $dosenluarbiasa->id)
        ->where('gaji_date_start', $gaji_date_start->format('Y-m-d'))
        ->where('gaji_date_end', $gaji_date_end->format('Y-m-d'))
        ->get();

    // Transformasi data transaksi
    $transaksiData = [
        'id' => $masterTransaksi->id,
        'gaji_date_start' => $gaji_date_start->format('d F Y'),
        'gaji_date_end' => $gaji_date_end->format('d F Y'),
        'bank' => $bankMasterTransaksi->pluck('bank')->toArray(),
        'status_bank'=> $masterTransaksi->status_bank,
        'komponen_pendapatan' => $komponenpendapatanMasterTransaksi->pluck('komponen_pendapatan')->toArray(),
        'potongan' => $potonganMasterTransaksi->pluck('potongan')->toArray(),
        'pajak' => $pajakMasterTransaksi->pluck('pajak')->toArray()
    ];


        // Menambahkan data transaksi ke dalam array transaksi utama
        $transaksi['transaksi'][] = $transaksiData;

    return ResponseFormatter::success($transaksi, 'Data Transaksi Gaji Dosen Luar Biasa Found');
}

public function create(CreateKomponenPendapatanRequest $komponenpendapatanRequest, CreatePotonganRequest $potonganRequest, CreatePajakRequest $pajakRequest, CreateMasterTransaksiRequest $transaksiRequest){
    // Memulai transaksi database
    DB::beginTransaction();
   try{
        // Ambil periode dari request
        $gaji_date_end = Carbon::createFromFormat('Y-m-d', $transaksiRequest->gaji_date_end);

        // Cek apakah transaksi untuk periode tersebut sudah ada (bulan dan tahun terakhir sama)
        $existingTransaction = Doslb_Master_Transaksi::whereYear('gaji_date_end', $gaji_date_end->year)
        ->whereMonth('gaji_date_end', $gaji_date_end->month)
        ->where('dosen_luar_biasa_id', $transaksiRequest->dosen_luar_biasa_id)
        ->exists();

        if ($existingTransaction) {
            throw new Exception('Transaksi gaji untuk periode bulan tersebut sudah ada.');
        }

       //Create Gaji Fakultas
       $komponenpendapatan = Doslb_Komponen_Pendapatan::create([
           'komponen_pendapatan' => json_encode($komponenpendapatanRequest->komponen_pendapatan)
       ]);
        // Decode "gaji_fakultas" data before sending the response
        $komponenpendapatan->komponen_pendapatan = json_decode($komponenpendapatan->komponen_pendapatan);

       //Create Potongan
       $potongan = Doslb_Potongan::create([
           'potongan' => json_encode($potonganRequest->potongan)
       ]);
        // Decode "potongan" data before sending the response
        $potongan->potongan = json_decode($potongan->potongan);

       //Create Pajak
       $pajak = Doslb_Pajak::create([
        'pajak_pph25' =>  $pajakRequest->pajak_pph25,
        'pendapatan_bersih' =>  $pajakRequest->pendapatan_bersih,
       ]);

       // Master Transaksi
       $mastertransaksi = Doslb_Master_Transaksi::create([
       'dosen_luar_biasa_id'=> $transaksiRequest->dosen_luar_biasa_id,
       'doslb_bank_id'=>  $transaksiRequest->doslb_bank_id,
       'status_bank' => $transaksiRequest-> status_bank,
       'gaji_date_start' => $transaksiRequest-> gaji_date_start,
       'gaji_date_end' => $transaksiRequest-> gaji_date_end,
       'doslb_komponen_pendapatan_id'=> $komponenpendapatan->id,
       'doslb_potongan_id'=>$potongan->id,
       'doslb_pajak_id'=>$pajak->id
       ]);

   // Commit transaksi jika berhasil
    DB::commit();


   if(!$komponenpendapatan && !$potongan && !$pajak){
       throw new Exception('Data Transaksi Gaji Dosen Luar Biasa not created');
   }

    return ResponseFormatter::success([
        'komponen_pendapatan' => $komponenpendapatan,
        'potongan' => $potongan,
        'pajak' => $pajak,
        'transaksi' => $mastertransaksi,
    ],'Data Transaksi Gaji Dosen Luar Biasa created');
   }
   catch(Exception $e){
       DB::rollback();
       return ResponseFormatter::error($e->getMessage(), 500);
   }
}

// Update Transaksi Gaji Karyawan
public function update(UpdateKomponenPendapatanRequest $komponenpendapatanRequest, UpdatePotonganRequest $potonganRequest, UpdatePajakRequest $pajakRequest, UpdateMasterTransaksiRequest $transaksiRequest, $transaksiId){
    // Memulai transaksi database
    DB::beginTransaction();
   try{
    // Get Master Transaksi
    $mastertransaksi = Doslb_Master_Transaksi::find($transaksiId);
    if ($mastertransaksi) {
         // Update Gaji Fakultas
         $komponenpendapatan = $mastertransaksi->komponen_pendapatan;
         if ($komponenpendapatan) {
            $komponenpendapatan->update([
                'komponen_pendapatan' => json_encode($komponenpendapatanRequest->komponen_pendapatan)
            ]);
        // Decode "komponen_pendapatan" data before sending the response
        $komponenpendapatan->komponen_pendapatan = json_decode($komponenpendapatan->komponen_pendapatan);
        }

        // Update Potongan
        $potongan = $mastertransaksi->potongan;
        if ($potongan) {
            $potongan->update([
                'potongan' => json_encode($potonganRequest->potongan)
            ]);
        // Decode "potongan" data before sending the response
        $potongan->potongan = json_decode($potongan->potongan);
        }

         // Update Pajak
         $pajak = $mastertransaksi->pajak;
         if ($pajak) {
             $pajak->update([
                'pajak_pph25' =>  $pajakRequest->pajak_pph25,
                'pendapatan_bersih' =>  $pajakRequest->pendapatan_bersih,
               ]);
         }

       // Update Master Transaksi
       $mastertransaksi->update([
       'dosen_luar_biasa_id'=> $mastertransaksi->dosen_luar_biasa_id, //id yg sama
       'doslb_bank_id'=>  $transaksiRequest->doslb_bank_id,
       'status_bank' => $transaksiRequest-> status_bank,
       'gaji_date_start' => $mastertransaksi->gaji_date_start, //data yg sama
       'gaji_date_end' => $mastertransaksi->gaji_date_end, //data yg sama
       'doslb_komponen_pendapatan_id'=> $mastertransaksi->doslb_komponen_pendapatan_id, //id yg sama
       'doslb_potongan_id'=>$mastertransaksi->doslb_potongan_id, //id yg sama
       'doslb_pajak_id'=>$mastertransaksi->doslb_pajak_id //id yg sama
       ]);

   // Commit transaksi jika berhasil
    DB::commit();
} else {
    // Handle jika master transaksi dengan ID $id tidak ditemukan
    return ResponseFormatter::error('Master Transaksi not found', 404);
}
    return ResponseFormatter::success([
        'transaksi' => $mastertransaksi,
    ],'Data Transaksi Gaji Dosen Luar Biasa updated');
   }
   catch(Exception $e){
       DB::rollback();
       return ResponseFormatter::error($e->getMessage(), 500);
   }
}

 //  Hapus Transaksi Gaji Dosen Luar Biasa
  public function destroy($transaksiId){
    try{
       // Get Master Transaksi
    $masterTransaksi = Doslb_Master_Transaksi::find($transaksiId);
    // Check if Dosen Luar Biasa exists
        if(!$masterTransaksi){
            return ResponseFormatter::error('Data Transaksi Gaji Dosen Luar Biasa not found',404);
        }

    // Hapus gaji komponen pendapatan, potongan, dan pajak berdasarkan kriteria
    $masterTransaksi->komponen_pendapatan()->delete();
    $masterTransaksi->potongan()->delete();
    $masterTransaksi->pajak()->delete();

    // Hapus Master Transaksi
    $masterTransaksi->delete();

        return ResponseFormatter::success('Data Transaksi Gaji Dosen Luar Biasa deleted');

    }catch(Exception $e){
        return ResponseFormatter::error($e->getMessage(), 500);
    }
     }
}
