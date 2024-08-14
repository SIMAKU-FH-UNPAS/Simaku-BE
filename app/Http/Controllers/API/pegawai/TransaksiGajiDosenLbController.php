<?php

namespace App\Http\Controllers\API\pegawai;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\master\Pegawai;
use App\Models\pegawai\PegawaiKomponenPendapatan;
use App\Models\pegawai\PegawaiMasterTransaksi;
use App\Models\pegawai\PegawaiPajak;
use App\Models\pegawai\PegawaiPotongan;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class TransaksiGajiDosenLbController extends Controller
{
    public function fetch($id)
    {
        // Get dosen luar biasa
        $pegawai = Pegawai::find($id);

        if (!$pegawai) {
            return ResponseFormatter::error(null, 'Dosen Luar Biasa Not Found', 404);
        }

        // Inisialisasi data Dosen Luar Biasa
        $transaksi = [
            'pegawais_id' => $pegawai->id,
            'no_pegawai' => $pegawai->no_pegawai,
            'nama' => $pegawai->nama,
            'npwp' => $pegawai->npwp,
            'golongan' => $pegawai->golongan,
            'jabatan' => $pegawai->jabatan,
            'nomor_hp' => $pegawai->nomor_hp,
        ];

        return ResponseFormatter::success($transaksi, 'Data Transaksi Gaji Dosen Luar Biasa Found');
    }

    public function fetchByFilter($id, $bulan, $tahun)
    {
        $masterTransaksi = PegawaiMasterTransaksi::where('pegawais_id', $id)
            ->whereMonth('gaji_date_end', '=', $bulan)
            ->whereYear('gaji_date_end', '=', $tahun)
            ->first();

        if (!$masterTransaksi) {
            return ResponseFormatter::error('Master Transaksi Not Found', 404);
        }

        $gaji_date_start = Carbon::createFromFormat('Y-m-d', $masterTransaksi->gaji_date_start);
        $gaji_date_end = Carbon::createFromFormat('Y-m-d', $masterTransaksi->gaji_date_end);

        $pegawai = Pegawai::with('banks')->find($masterTransaksi->pegawais_id);
        if (!$pegawai) {
            return ResponseFormatter::error(null, 'Dosen Tetap Not Found', 404);
        }

        // Inisialisasi data Dosen Luar Biasa
        $transaksi = [
            'pegawais_id' => $pegawai->id,
            'no_pegawai' => $pegawai->no_pegawai,
            'nama' => $pegawai->nama,
            'npwp' => $pegawai->npwp,
            'golongan' => $pegawai->golongan,
            'jabatan' => $pegawai->jabatan,
            'nomor_hp' => $pegawai->nomor_hp,
            'transaksi' => [],
        ];

        // Get Periode
        $gaji_date_start = Carbon::createFromFormat('Y-m-d', $masterTransaksi->gaji_date_start);
        $gaji_date_end = Carbon::createFromFormat('Y-m-d', $masterTransaksi->gaji_date_end);

        $bankMasterTransaksi = PegawaiMasterTransaksi::with(['bank'])
            ->where('pegawais_id', $pegawai->id)
            ->where('gaji_date_start', $gaji_date_start->format('Y-m-d'))
            ->where('gaji_date_end', $gaji_date_end->format('Y-m-d'))
            ->get();

        $komponenpendapatanMasterTransaksi = PegawaiMasterTransaksi::with('komponen_pendapatan')
            ->where('pegawais_id', $pegawai->id)
            ->where('gaji_date_start', $gaji_date_start->format('Y-m-d'))
            ->where('gaji_date_end', $gaji_date_end->format('Y-m-d'))
            ->get();
        $komponenpendapatanMasterTransaksi->transform(function ($item) {
            $item->komponen_pendapatan->komponen_pendapatan = json_decode($item->komponen_pendapatan->komponen_pendapatan);
            return $item;
        });

        $potonganMasterTransaksi = PegawaiMasterTransaksi::with('potongan')
            ->where('pegawais_id', $pegawai->id)
            ->where('gaji_date_start', $gaji_date_start->format('Y-m-d'))
            ->where('gaji_date_end', $gaji_date_end->format('Y-m-d'))
            ->get();
        $potonganMasterTransaksi->transform(function ($item) {
            $item->potongan->potongan = json_decode($item->potongan->potongan);
            return $item;
        });

        $pajakMasterTransaksi = PegawaiMasterTransaksi::with(['pajak'])
            ->where('pegawais_id', $pegawai->id)
            ->where('gaji_date_start', $gaji_date_start->format('Y-m-d'))
            ->where('gaji_date_end', $gaji_date_end->format('Y-m-d'))
            ->get();

        // Transformasi data transaksi
        $transaksiData = [
            'id' => $masterTransaksi->id,
            'periode' => [
                'month' => $gaji_date_end->format('F'),
                'year' => $gaji_date_end->format('Y'),
            ],
            'bank' => $bankMasterTransaksi->pluck('bank')->toArray(),
            'status_bank' => $masterTransaksi->status_bank,
            'komponen_pendapatan' => $komponenpendapatanMasterTransaksi->pluck('komponen_pendapatan')->toArray(),
            'potongan' => $potonganMasterTransaksi->pluck('potongan')->toArray(),
            'pajak' => $pajakMasterTransaksi->pluck('pajak')->toArray()
        ];

        // Menambahkan data transaksi ke dalam array transaksi utama
        $transaksi['transaksi'][] = $transaksiData;
        return ResponseFormatter::success($transaksi, 'Data Transaksi Gaji Dosen Luar Biasa Found');
    }

    public function fetchById($id)
    {
        // Get Master Transaksi
        $masterTransaksi = PegawaiMasterTransaksi::find($id);

        if (!$masterTransaksi) {
            return ResponseFormatter::error('Master Transaksi Not Found', 404);
        }

        // Get Periode
        $gaji_date_start = Carbon::createFromFormat('Y-m-d', $masterTransaksi->gaji_date_start);
        $gaji_date_end = Carbon::createFromFormat('Y-m-d', $masterTransaksi->gaji_date_end);

        // Get dosen luar biasa
        $pegawai = Pegawai::with('banks')->find($masterTransaksi->pegawais_id);
        if (!$pegawai) {
            return ResponseFormatter::error(null, 'Dosen Luar Biasa Not Found', 404);
        }

        // Inisialisasi data dosen luar biasa
        $transaksi = [
            'pegawais_id' => $pegawai->id,
            'no_pegawai' => $pegawai->no_pegawai,
            'nama' => $pegawai->nama,
            'npwp' => $pegawai->npwp,
            'golongan' => $pegawai->golongan,
            'jabatan' => $pegawai->jabatan,
            'nomor_hp' => $pegawai->nomor_hp,
            'banks' => $pegawai->banks,
            'status_bank' => $pegawai->status_bank,
            'transaksi' => [],
        ];

        $bankMasterTransaksi = PegawaiMasterTransaksi::with(['bank'])
            ->where('pegawais_id', $pegawai->id)
            ->where('gaji_date_start', $gaji_date_start->format('Y-m-d'))
            ->where('gaji_date_end', $gaji_date_end->format('Y-m-d'))
            ->get();

        $komponenpendapatanMasterTransaksi = PegawaiMasterTransaksi::with('komponen_pendapatan')
            ->where('pegawais_id', $pegawai->id)
            ->where('gaji_date_start', $gaji_date_start->format('Y-m-d'))
            ->where('gaji_date_end', $gaji_date_end->format('Y-m-d'))
            ->get();

        $komponenpendapatanMasterTransaksi->transform(function ($item) {
            $item->komponen_pendapatan->komponen_pendapatan = json_decode($item->komponen_pendapatan->komponen_pendapatan);
            return $item;
        });

        $potonganMasterTransaksi = PegawaiMasterTransaksi::with('potongan')
            ->where('pegawais_id', $pegawai->id)
            ->where('gaji_date_start', $gaji_date_start->format('Y-m-d'))
            ->where('gaji_date_end', $gaji_date_end->format('Y-m-d'))
            ->get();
        $potonganMasterTransaksi->transform(function ($item) {
            $item->potongan->potongan = json_decode($item->potongan->potongan);
            return $item;
        });

        $pajakMasterTransaksi = PegawaiMasterTransaksi::with(['pajak'])
            ->where('pegawais_id', $pegawai->id)
            ->where('gaji_date_start', $gaji_date_start->format('Y-m-d'))
            ->where('gaji_date_end', $gaji_date_end->format('Y-m-d'))
            ->get();

        // Transformasi data transaksi
        $transaksiData = [
            'id' => $masterTransaksi->id,
            'gaji_date_start' => $gaji_date_start->format('d F Y'),
            'gaji_date_end' => $gaji_date_end->format('d F Y'),
            'bank' => $bankMasterTransaksi->pluck('bank')->toArray(),
            'status_bank' => $masterTransaksi->status_bank,
            'komponen_pendapatan' => $komponenpendapatanMasterTransaksi->pluck('komponen_pendapatan')->toArray(),
            'potongan' => $potonganMasterTransaksi->pluck('potongan')->toArray(),
            'pajak' => $pajakMasterTransaksi->pluck('pajak')->toArray()
        ];


        // Menambahkan data transaksi ke dalam array transaksi utama
        $transaksi['transaksi'][] = $transaksiData;

        return ResponseFormatter::success($transaksi, 'Data Transaksi Gaji Dosen Luar Biasa Found');
    }

    public function create(Request $request)
    {
        $this->_validate($request);
        try {
            DB::beginTransaction();
            $gajiEndDate = Carbon::createFromFormat('Y-m-d', $request->input('gaji_date_end'));

            $existingTransaction = PegawaiMasterTransaksi::whereYear('gaji_date_end', $gajiEndDate->year)
                ->whereMonth('gaji_date_end', $gajiEndDate->month)
                ->where('pegawais_id', $request->input('pegawais_id'))
                ->exists();

            if ($existingTransaction) {
                throw new Exception('Transaksi gaji untuk periode bulan tersebut sudah ada.');
            }

            //Create Gaji Fakultas
            $komponenpendapatan = PegawaiKomponenPendapatan::create([
                'komponen_pendapatan' => json_encode($request->input('komponen_pendapatan'))
            ]);
            // Decode "gaji_fakultas" data before sending the response
            $komponenpendapatan->komponen_pendapatan = json_decode($komponenpendapatan->komponen_pendapatan);

            //Create Potongan
            $potongan = PegawaiPotongan::create([
                'potongan' => json_encode($request->input('potongan'))
            ]);
            // Decode "potongan" data before sending the response
            $potongan->potongan = json_decode($potongan->potongan);

            //Create Pajak
            $pajak = PegawaiPajak::create([
                'pajak_pph25' =>  $request->input('pajak_pph25'),
                'pendapatan_bersih' =>  $request->input('pendapatan_bersih'),
            ]);

            // Master Transaksi
            $mastertransaksi = PegawaiMasterTransaksi::create([
                'pegawais_id' => $request->input('pegawais_id'),
                'pegawais_bank_id' =>  $request->input('pegawais_bank_id'),
                'status_bank' => $request->input('status_bank'),
                'gaji_date_start' => $request->input('gaji_date_start'),
                'gaji_date_end' => $request->input('gaji_date_end'),
                'pegawais_komponen_pendapatan_id' => $komponenpendapatan->id,
                'pegawais_potongan_id' => $potongan->id,
                'pegawais_pajak_id' => $pajak->id
            ]);

            DB::commit();

            if (!$komponenpendapatan && !$potongan && !$pajak) {
                throw new Exception('Data Transaksi Gaji Dosen Luar Biasa not created');
            }

            return ResponseFormatter::success([
                'komponen_pendapatan' => $komponenpendapatan,
                'potongan' => $potongan,
                'pajak' => $pajak,
                'transaksi' => $mastertransaksi,
            ], 'Data Transaksi Gaji Dosen Luar Biasa created');
        } catch (Exception $e) {
            DB::rollback();
            return ResponseFormatter::error($e->getMessage(), 500);
        }
    }

    public function destroy($transaksiId)
    {
        try {
            // Get Master Transaksi
            $masterTransaksi = PegawaiMasterTransaksi::find($transaksiId);
            // Check if Dosen Luar Biasa exists
            if (!$masterTransaksi) {
                return ResponseFormatter::error('Data Transaksi Gaji Dosen Luar Biasa not found', 404);
            }

            // Hapus gaji komponen pendapatan, potongan, dan pajak berdasarkan kriteria
            $masterTransaksi->komponen_pendapatan()->delete();
            $masterTransaksi->potongan()->delete();
            $masterTransaksi->pajak()->delete();

            // Hapus Master Transaksi
            $masterTransaksi->delete();

            return ResponseFormatter::success('Data Transaksi Gaji Dosen Luar Biasa deleted');
        } catch (Exception $e) {
            return ResponseFormatter::error($e->getMessage(), 500);
        }
    }

    public function update(Request $request, $id)
    {
        $this->_validateUpdate($request);
        try {
            DB::beginTransaction();
            $mastertransaksi = PegawaiMasterTransaksi::find($id);
            if ($mastertransaksi) {
                // Update Gaji Fakultas
                $komponenpendapatan = $mastertransaksi->komponen_pendapatan;
                if ($komponenpendapatan) {
                    $komponenpendapatan->update([
                        'komponen_pendapatan' => json_encode($request->input('komponen_pendapatan'))
                    ]);
                    // Decode "komponen_pendapatan" data before sending the response
                    $komponenpendapatan->komponen_pendapatan = json_decode($komponenpendapatan->komponen_pendapatan);
                }

                // Update Potongan
                $potongan = $mastertransaksi->potongan;
                if ($potongan) {
                    $potongan->update([
                        'potongan' => json_encode($request->input('potongan'))
                    ]);
                    // Decode "potongan" data before sending the response
                    $potongan->potongan = json_decode($potongan->potongan);
                }

                $pajak = $mastertransaksi->pajak;
                if ($pajak) {
                    $pajak->update([
                        'pajak_pph25' =>  $request->input('pajak_pph25'),
                        'pendapatan_bersih' =>  $request->input('pendapatan_bersih'),
                    ]);
                }

                $mastertransaksi->update([
                    'pegawais_id' => $mastertransaksi->pegawais_id, //id yg sama
                    'pegawais_bank_id' =>  $request->input('pegawais_bank_id'),
                    'status_bank' => $request->input('status_bank'),
                    'gaji_date_start' => $mastertransaksi->gaji_date_start, //data yg sama
                    'gaji_date_end' => $mastertransaksi->gaji_date_end, //data yg sama
                    'pegawais_komponen_pendapatan_id' => $mastertransaksi->pegawais_komponen_pendapatan_id, //id yg sama
                    'pegawais_potongan_id' => $mastertransaksi->pegawais_potongan_id, //id yg sama
                    'pegawais_pajak_id' => $mastertransaksi->pegawais_pajak_id //id yg sama
                ]);

                DB::commit();
            } else {
                return ResponseFormatter::error('Master Transaksi not found', 404);
            }
            return ResponseFormatter::success([
                'transaksi' => $mastertransaksi,
            ], 'Data Transaksi Gaji Dosen Luar Biasa updated');
        } catch (Exception $e) {
            DB::rollback();
            return ResponseFormatter::error($e->getMessage(), 500);
        }
    }

    private function _validate($request)
    {
        $validator = $request->validate([
            'komponen_pendapatan' => 'required|array',
            'komponen_pendapatan.*' => 'integer',
            'potongan' => 'required|array',
            'potongan.*' => 'integer',
            'pajak_pph25' => 'nullable|integer',
            'pendapatan_bersih' => 'nullable|integer',
            'pegawais_id' => 'required|integer|exists:pegawais,id,deleted_at,NULL',
            'pegawais_bank_id' => [
                'required',
                'integer',
                Rule::exists('pegawai_banks', 'id')
                    ->where('pegawais_id', $request->pegawais_id)
                    ->whereNull('deleted_at'),
            ],
            'status_bank' => 'required|string|in:Payroll,Non Payroll',
            'gaji_date_start' => 'required|date', //YYYY-MM-DD
            'gaji_date_end' => 'required|date' //YYYY-MM-DD
        ]);

        return $validator;
    }

    private function _validateUpdate($request)
    {
        $validator = $request->validate([
            'komponen_pendapatan' => 'required|array',
            'komponen_pendapatan.*' => 'integer',
            'potongan' => 'required|array',
            'potongan.*' => 'integer',
            'pajak_pph25' => 'nullable|integer',
            'pendapatan_bersih' => 'nullable|integer',
            'pegawais_bank_id' => [
                'required',
                'integer',
                Rule::exists('pegawai_banks', 'id')
                    ->where('pegawais_id', PegawaiMasterTransaksi::find($request->id)->pegawais_id)
                    ->whereNull('deleted_at'),
            ],
            'status_bank' => 'required|string|in:Payroll,Non Payroll'
        ]);

        return $validator;
    }
}
