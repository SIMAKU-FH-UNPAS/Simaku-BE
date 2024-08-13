<?php

namespace App\Http\Controllers\API\pegawai;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\master\Pegawai;
use App\Models\pegawai\PegawaiGajiFakultas;
use App\Models\pegawai\PegawaiGajiUniv;
use App\Models\pegawai\PegawaiMasterTransaksi;
use App\Models\pegawai\PegawaiPajak;
use App\Models\pegawai\PegawaiPotongan;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class TransaksiGajiController extends Controller
{
    public function fetch($id)
    {
        // Get Dosen Tetap
        $pegawai = Pegawai::find($id);

        if (!$pegawai) {
            return ResponseFormatter::error(null, 'Dosen Tetap Not Found', 404);
        }

        // Inisialisasi data dosen Tetap
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

        // Get ALL DATA Master Transaksi
        $transaksigaji = PegawaiMasterTransaksi::where('pegawais_id', $pegawai->id)->get();

        foreach ($transaksigaji as $gaji) {
            // Get Periode
            $gaji_date_start = Carbon::createFromFormat('Y-m-d', $gaji->gaji_date_start);
            $gaji_date_end = Carbon::createFromFormat('Y-m-d', $gaji->gaji_date_end);


            $bankMasterTransaksi = PegawaiMasterTransaksi::with(['bank'])
                ->where('pegawais_id', $pegawai->id)
                ->where('gaji_date_start', $gaji_date_start->format('Y-m-d'))
                ->where('gaji_date_end', $gaji_date_end->format('Y-m-d'))
                ->get();

            $gajiunivMasterTransaksi = PegawaiMasterTransaksi::with(['gaji_universitas'])
                ->where('pegawais_id', $pegawai->id)
                ->where('gaji_date_start', $gaji_date_start->format('Y-m-d'))
                ->where('gaji_date_end', $gaji_date_end->format('Y-m-d'))
                ->get();

            $gajifakMasterTransaksi = PegawaiMasterTransaksi::with('gaji_fakultas')
                ->where('pegawais_id', $pegawai->id)
                ->where('gaji_date_start', $gaji_date_start->format('Y-m-d'))
                ->where('gaji_date_end', $gaji_date_end->format('Y-m-d'))
                ->get();
            $gajifakMasterTransaksi->transform(function ($item) {
                $item->gaji_fakultas->gaji_fakultas = json_decode($item->gaji_fakultas->gaji_fakultas);
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
                'id' => $gaji->id,
                'periode' => [
                    'month' => $gaji_date_end->format('F'),
                    'year' => $gaji_date_end->format('Y'),
                ],
                'bank' => $bankMasterTransaksi->pluck('bank')->toArray(),
                'status_bank' => $gaji->status_bank,
                'gaji_universitas' => $gajiunivMasterTransaksi->pluck('gaji_universitas')->toArray(),
                'gaji_fakultas' => $gajifakMasterTransaksi->pluck('gaji_fakultas')->toArray(),
                'potongan' => $potonganMasterTransaksi->pluck('potongan')->toArray(),
                'pajak' => $pajakMasterTransaksi->pluck('pajak')->toArray()
            ];

            // Menambahkan data transaksi ke dalam array transaksi utama
            $transaksi['transaksi'][] = $transaksiData;
        }

        return ResponseFormatter::success($transaksi, 'Data Transaksi Gaji Dosen Tetap Found');
    }

    public function fetchById($id, $bulan, $tahun)
    {
        $masterTransaksi = PegawaiMasterTransaksi::whereMonth('gaji_date_end', $bulan)
            ->whereYear('gaji_date_end', '=', $tahun)
            ->where('pegawais_id', $id)
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

        // Inisialisasi data dosen Tetap
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

        $gajiunivMasterTransaksi = PegawaiMasterTransaksi::with(['gaji_universitas'])
            ->where('pegawais_id', $pegawai->id)
            ->where('gaji_date_start', $gaji_date_start->format('Y-m-d'))
            ->where('gaji_date_end', $gaji_date_end->format('Y-m-d'))
            ->get();

        $gajifakMasterTransaksi = PegawaiMasterTransaksi::with('gaji_fakultas')
            ->where('pegawais_id', $pegawai->id)
            ->where('gaji_date_start', $gaji_date_start->format('Y-m-d'))
            ->where('gaji_date_end', $gaji_date_end->format('Y-m-d'))
            ->get();
        $gajifakMasterTransaksi->transform(function ($item) {
            $item->gaji_fakultas->gaji_fakultas = json_decode($item->gaji_fakultas->gaji_fakultas);
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
            'gaji_universitas' => $gajiunivMasterTransaksi->pluck('gaji_universitas')->toArray(),
            'gaji_fakultas' => $gajifakMasterTransaksi->pluck('gaji_fakultas')->toArray(),
            'potongan' => $potonganMasterTransaksi->pluck('potongan')->toArray(),
            'pajak' => $pajakMasterTransaksi->pluck('pajak')->toArray()
        ];


        // Menambahkan data transaksi ke dalam array transaksi utama
        $transaksi['transaksi'][] = $transaksiData;

        return ResponseFormatter::success($transaksi, 'Data Transaksi Gaji Dosen Tetap Found');
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

            $gajiuniv = PegawaiGajiUniv::create([
                'gaji_pokok' => $request->input('gaji_pokok'),
                'tunjangan_fungsional' => $request->input('tunjangan_fungsional'),
                'tunjangan_struktural' => $request->input('tunjangan_struktural'),
                'tunjangan_khusus_istimewa' => $request->input('tunjangan_khusus_istimewa'),
                'tunjangan_presensi_kerja' => $request->input('tunjangan_presensi_kerja'),
                'tunjangan_tambahan' => $request->input('tunjangan_tambahan'),
                'tunjangan_suami_istri' => $request->input('tunjangan_suami_istri'),
                'tunjangan_anak' => $request->input('tunjangan_anak'),
                'uang_lembur_hk' => $request->input('uang_lembur_hk'),
                'uang_lembur_hl' => $request->input('uang_lembur_hl'),
                'transport_kehadiran' => $request->input('transport_kehadiran'),
                'honor_universitas' => $request->input('honor_universitas'),
            ]);

            $gajifak = PegawaiGajiFakultas::create([
                'gaji_fakultas' => json_encode($request->input('gaji_fakultas'))
            ]);
            $gajifak->gaji_fakultas = json_decode($gajifak->gaji_fakultas);

            $potongan = PegawaiPotongan::create([
                'potongan' => json_encode($request->input('potongan'))
            ]);
            $potongan->potongan = json_decode($potongan->potongan);

            $pajak = PegawaiPajak::create([
                'pensiun' => $request->input('pensiun'),
                'bruto_pajak' =>  $request->input('bruto_pajak'),
                'bruto_murni' =>  $request->input('bruto_murni'),
                'biaya_jabatan' =>  $request->input('biaya_jabatan'),
                'aksa_mandiri' => $request->input('aksa_mandiri'),
                'dplk_pensiun' => $request->input('dplk_pensiun'),
                'jumlah_potongan_kena_pajak' =>  $request->input('jumlah_potongan_kena_pajak'),
                'jumlah_set_potongan_kena_pajak' =>  $request->input('jumlah_set_potongan_kena_pajak'),
                'ptkp' => $request->input('ptkp'),
                'pkp' =>  $request->input('pkp'),
                'pajak_pph21' =>  $request->input('pajak_pph21'),
                'jumlah_set_pajak' =>  $request->input('jumlah_set_pajak'),
                'potongan_tak_kena_pajak' =>  $request->input('potongan_tak_kena_pajak'),
                'pendapatan_bersih' =>  $request->input('pendapatan_bersih'),
            ]);

            $mastertransaksi = PegawaiMasterTransaksi::create([
                'pegawais_id' => $request->input('pegawais_id'),
                'pegawais_bank_id' =>  $request->input('pegawais_bank_id'),
                'status_bank' => $request->input('status_bank'),
                'gaji_date_start' => $request->input('gaji_date_start'),
                'gaji_date_end' => $request->input('gaji_date_end'),
                'pegawais_gaji_univ_id' => $gajiuniv->id,
                'pegawais_gaji_fakultas_id' => $gajifak->id,
                'pegawais_potongan_id' => $potongan->id,
                'pegawais_pajak_id' => $pajak->id
            ]);

            DB::commit();

            if (!$gajiuniv && !$gajifak && !$potongan && !$pajak) {
                throw new Exception('Data Transaksi Gaji Dosen Tetap not created');
            }

            return ResponseFormatter::success(
                [
                    'gaji_universitas' => $gajiuniv,
                    'gaji_fakultas' => $gajifak,
                    'potongan' => $potongan,
                    'pajak' => $pajak,
                    'transaksi' => $mastertransaksi,
                ],
                'Data Transaksi Gaji Dosen Tetap created'
            );
        } catch (Exception $e) {
            DB::rollback();
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
                // Update Gaji Universitas
                $gajiuniv = $mastertransaksi->gaji_universitas;
                if ($gajiuniv) {
                    // Update Gaji Universitas
                    $gajiuniv->update([
                        'gaji_pokok' => $request->input('gaji_pokok'),
                        'tunjangan_fungsional' => $request->input('tunjangan_fungsional'),
                        'tunjangan_struktural' => $request->input('tunjangan_struktural'),
                        'tunjangan_khusus_istimewa' => $request->input('tunjangan_khusus_istimewa'),
                        'tunjangan_presensi_kerja' => $request->input('tunjangan_presensi_kerja'),
                        'tunjangan_tambahan' => $request->input('tunjangan_tambahan'),
                        'tunjangan_suami_istri' => $request->input('tunjangan_suami_istri'),
                        'tunjangan_anak' => $request->input('tunjangan_anak'),
                        'uang_lembur_hk' => $request->input('uang_lembur_hk'),
                        'uang_lembur_hl' => $request->input('uang_lembur_hl'),
                        'transport_kehadiran' => $request->input('transport_kehadiran'),
                        'honor_universitas' => $request->input('honor_universitas'),
                    ]);
                }

                // Update Gaji Fakultas
                $gajifak = $mastertransaksi->gaji_fakultas;
                if ($gajifak) {
                    $gajifak->update([
                        'gaji_fakultas' => json_encode($request->input('gaji_fakultas'))
                    ]);
                    // Decode "gaji_fakultas" data before sending the response
                    $gajifak->gaji_fakultas = json_decode($gajifak->gaji_fakultas);
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

                // Update Pajak
                $pajak = $mastertransaksi->pajak;
                if ($pajak) {
                    $pajak->update([
                        'pensiun' => $request->input('pensiun'),
                        'bruto_pajak' =>  $request->input('bruto_pajak'),
                        'bruto_murni' =>  $request->input('bruto_murni'),
                        'biaya_jabatan' =>  $request->input('biaya_jabatan'),
                        'aksa_mandiri' => $request->input('aksa_mandiri'),
                        'dplk_pensiun' => $request->input('dplk_pensiun'),
                        'jumlah_potongan_kena_pajak' =>  $request->input('jumlah_potongan_kena_pajak'),
                        'jumlah_set_potongan_kena_pajak' =>  $request->input('jumlah_set_potongan_kena_pajak'),
                        'ptkp' => $request->input('ptkp'),
                        'pkp' =>  $request->input('pkp'),
                        'pajak_pph21' =>  $request->input('pajak_pph21'),
                        'jumlah_set_pajak' =>  $request->input('jumlah_set_pajak'),
                        'potongan_tak_kena_pajak' =>  $request->input('potongan_tak_kena_pajak'),
                        'pendapatan_bersih' =>  $request->input('pendapatan_bersih'),
                    ]);
                }

                // Update Master Transaksi
                $mastertransaksi->update([
                    'pegawais_id' => $mastertransaksi->pegawais_id, //id yg sama
                    'pegawais_bank_id' =>  $request->input('pegawais_bank_id'),
                    'status_bank' => $request->input('status_bank'),
                    'gaji_date_start' => $mastertransaksi->gaji_date_start, //data yg sama
                    'gaji_date_end' => $mastertransaksi->gaji_date_end, //data yg sama
                    'pegawais_gaji_univ_id' => $mastertransaksi->pegawais_gaji_univ_id, //id yg sama
                    'pegawais_gaji_fakultas_id' => $mastertransaksi->pegawais_gaji_fakultas_id, //id yg sama
                    'pegawais_potongan_id' => $mastertransaksi->pegawais_potongan_id, //id yg sama
                    'pegawais_pajak_id' => $mastertransaksi->pegawais_pajak_id //id yg sama
                ]);

                // Commit transaksi jika berhasil
                DB::commit();
            } else {
                // Handle jika master transaksi dengan ID $id tidak ditemukan
                return ResponseFormatter::error('Master Transaksi not found', 404);
            }
            return ResponseFormatter::success(
                [
                    'transaksi' => $mastertransaksi,
                ],
                'Data Transaksi Gaji Dosen Tetap updated'
            );
        } catch (Exception $e) {
            DB::rollback();
            return ResponseFormatter::error($e->getMessage(), 500);
        }
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            // Get Master Transaksi
            $masterTransaksi = PegawaiMasterTransaksi::find($id);
            // Check if Dosen Luar Biasa exists
            if (!$masterTransaksi) {
                return ResponseFormatter::error('Data Transaksi Gaji Dosen Tetap not found', 404);
            }

            // Hapus gaji universitas, gaji fakultas, potongan, dan pajak berdasarkan kriteria
            $masterTransaksi->gaji_universitas()->delete();
            $masterTransaksi->gaji_fakultas()->delete();
            $masterTransaksi->potongan()->delete();
            $masterTransaksi->pajak()->delete();

            // Hapus Master Transaksi
            $masterTransaksi->delete();
            DB::commit();

            return ResponseFormatter::success('Data Transaksi Gaji Dosen Tetap deleted');
        } catch (Exception $e) {
            DB::rollback();
            return ResponseFormatter::error($e->getMessage(), 500);
        }
    }

    private function _validate($request)
    {
        $validator = $request->validate([
            // gaji univ
            'gaji_pokok' => 'required|integer',
            'tunjangan_fungsional' => 'required|integer',
            'tunjangan_struktural' => 'required|integer',
            'tunjangan_khusus_istimewa' => 'required|integer',
            'tunjangan_presensi_kerja' => 'required|integer',
            'tunjangan_tambahan' => 'required|integer',
            'tunjangan_suami_istri' => 'required|integer',
            'tunjangan_anak' => 'required|integer',
            'uang_lembur_hk' => 'required|integer',
            'uang_lembur_hl' => 'required|integer',
            'transport_kehadiran' => 'required|integer',
            'honor_universitas' => 'required|integer',
            // gaji fakultas
            'gaji_fakultas' => 'required|array',
            'gaji_fakultas.*' => 'integer',
            // potongan
            'potongan' => 'required|array',
            'potongan.*' => 'integer',
            // pajak
            'pensiun' => 'nullable|integer',
            'bruto_pajak' => 'nullable|integer',
            'bruto_murni' => 'nullable|integer',
            'biaya_jabatan' => 'nullable|integer',
            'aksa_mandiri' => 'nullable|integer',
            'dplk_pensiun' => 'nullable|integer',
            'jumlah_potongan_kena_pajak' => 'nullable|integer',
            'jumlah_set_potongan_kena_pajak' => 'nullable|integer',
            'ptkp' => 'nullable|integer',
            'pkp' => 'nullable|integer',
            'pajak_pph21' => 'nullable|integer',
            'jumlah_set_pajak' => 'nullable|integer',
            'potongan_tak_kena_pajak' => 'nullable|integer',
            'pendapatan_bersih' => 'nullable|integer',
            // master transaksi
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
            'gaji_pokok' => 'required|integer',
            'tunjangan_fungsional' => 'required|integer',
            'tunjangan_struktural' => 'required|integer',
            'tunjangan_khusus_istimewa' => 'required|integer',
            'tunjangan_presensi_kerja' => 'required|integer',
            'tunjangan_tambahan' => 'required|integer',
            'tunjangan_suami_istri' => 'required|integer',
            'tunjangan_anak' => 'required|integer',
            'uang_lembur_hk' => 'required|integer',
            'uang_lembur_hl' => 'required|integer',
            'transport_kehadiran' => 'required|integer',
            'honor_universitas' => 'required|integer',
            'gaji_fakultas' => 'required|array',
            // Validate each key in "gaji_fakultas" to be integer
            'gaji_fakultas.*' => 'integer',
            'potongan' => 'required|array',
            // Validate each key in "gaji_fakultas" to be integer
            'potongan.*' => 'integer',
            'pensiun' => 'required|integer',
            'bruto_pajak' => 'required|integer',
            'bruto_murni' => 'required|integer',
            'biaya_jabatan' => 'required|integer',
            'aksa_mandiri' => 'required|integer',
            'dplk_pensiun' => 'required|integer',
            'jumlah_potongan_kena_pajak' => 'required|integer',
            'jumlah_set_potongan_kena_pajak' => 'required|integer',
            'ptkp' => 'required|integer',
            'pkp' => 'required|integer',
            'pajak_pph21' => 'required|integer',
            'jumlah_set_pajak' => 'required|integer',
            'potongan_tak_kena_pajak' => 'required|integer',
            'pendapatan_bersih' => 'required|integer',
            'pegawais_bank_id' => [
                'required',
                'integer',
                Rule::exists('pegawai_banks', 'id')
                    ->where('id', $request->pegawais_bank_id)
                    ->where('pegawais_id', PegawaiMasterTransaksi::find($request->id)->pegawais_id)
                    ->whereNull('deleted_at')
            ],
            'status_bank' => 'required|string|in:Payroll,Non Payroll'
        ]);

        return $validator;
    }
}
