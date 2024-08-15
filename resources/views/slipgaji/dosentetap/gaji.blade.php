@inject('carbon', 'Carbon\Carbon')
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <style>
        .page_break { 
            page-break-before: always; 
        }
    </style>
</head>
<body>

    @php
    $carbon::setLocale('id');
    setlocale(LC_ALL, 'id_ID');

    $daftar_hari = array(
            'Sunday' => 'Minggu',
            'Monday' => 'Senin',
            'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis',
            'Friday' => 'Jumat',
            'Saturday' => 'Sabtu',
        );
    @endphp

    <div style="text-align: center; font-size: 11px; margin-top: -20px">
        <p style="margin-bottom: -12px">PEMBAYARAN HONORARIUM MENGAJAR</p>
        <p style="margin-bottom: -12px">DAN PENDAPATAN LAIN</p>
        <p style="margin-bottom: -12px">DOSEN TETAP FH UNPAS</p>
        <p style="margin-bottom: 15px">=====================================</P>
    </div>

    <div style="width: 290px; margin: auto">
        <div style="font-size: 11px;">
            <table cellpadding="0" cellspacing="0.5" border="0" style="margin-bottom: 15px">
                <tr>
                    <td>No</td>
                    <td>:</td>
                    <td>{{ $pegawai->no_pegawai }}</td>
                </tr>
                <tr>
                    <td>Nama</td>
                    <td>:</td>
                    <td>{{ $pegawai->nama }}</td>
                </tr>
            </table>
        </div>
        <div style="font-size: 11px;">
            <table cellpadding="0" cellspacing="0.5" border="0" style="width: 100%;">
                <tr>
                    <td colspan="2">RINCIAN GAJI</td>
                </tr>
                @foreach($gajiUniv->getAttributes() as $column => $value)
                    {{-- Exclude column dibawah --}}
                    @if (!in_array($column, ['id', 'deleted_at', 'created_at', 'updated_at']))
                        @if($value != 0)
                            <tr>
                                <td>• {{ ucfirst(str_replace('_', ' ', $column)) }}</td>
                                <td></td>
                                <td style="text-align: right">{{ format_rupiah($value) }}</td>
                            </tr>
                        @endif
                    @endif
                @endforeach

                @foreach($gajiFak->gaji_fakultas as $column => $value)
                {{-- Exclude column dibawah --}}
                @if (!in_array($column, ['id', 'deleted_at', 'created_at', 'updated_at']))
                    @if($value != 0)
                        <tr>
                            <td>• {{ ucfirst(str_replace('_', ' ', $column)) }}</td>
                            <td></td>
                            <td style="text-align: right">{{ format_rupiah($value) }}</td>
                        </tr>
                    @endif
                @endif
                @endforeach
                <tr>
                    <td style="text-align: right">** JUMLAH I **</td>
                    <td></td>
                    <td style="text-align: right;">{{ format_rupiah($totalPendapatan) }}</td>
                </tr>
                <tr>
                    <td colspan="2" >POTONGAN</td>
                </tr>
                @foreach($potongan as $column => $value)
                {{-- Exclude column yang tidak ingin ditampilkan --}}
                @if (!in_array($column, ['id', 'deleted_at', 'created_at', 'updated_at']))
                    @if($value != 0)
                        <tr>
                            <td>• {{ ucfirst(str_replace('_', ' ', $column)) }}</td>
                            <td></td>
                            <td style="text-align: right">{{ format_rupiah($value) }}</td>
                        </tr>
                    @endif
                @endif
                @endforeach
                <tr>
                    <td style="text-align: right">** JUMLAH II **</td>
                    <td></td>
                    <td style="text-align: right">{{ format_rupiah($totalPotongan) }}</td>
                </tr>

                <tr>
                    <td style="text-align: right">** DITERIMA I-I **</td>
                    <td></td>
                    <td style="text-align: right">{{ format_rupiah($pendapatanBersih) }}</td>
                </tr>
            </table>
        </div>

        <div style="text-align: center; font-size: 11px;">
            <p style="margin-bottom: -12px">1 {{ $bulanTahun }}</p>
            <p>Tanda Tangan</p>
            <p style="margin-top: 40px">{{ $pegawai->nama }}</p>
        </div>
    </div>
    <br>
    <br>
    <div style="text-align: center; font-size: 11px; margin-top: -20px">
        <p style="margin-bottom: -12px">PEMBAYARAN HONORARIUM MENGAJAR</p>
        <p style="margin-bottom: -12px">DAN PENDAPATAN LAIN</p>
        <p style="margin-bottom: -12px">DOSEN TETAP FH UNPAS</p>
        <p style="margin-bottom: 0px">=====================================</P>
    </div>

    <div style="width: 290px; margin: auto">
        <div style="font-size: 11px;">
            <table cellpadding="0" cellspacing="0.5" border="0" style="margin-bottom: 15px">
                <tr>
                    <td>No</td>
                    <td>:</td>
                    <td>{{ $pegawai->no_pegawai }}</td>
                </tr>
                <tr>
                    <td>Nama</td>
                    <td>:</td>
                    <td>{{ $pegawai->nama }}</td>
                </tr>
            </table>
        </div>
        <div style="font-size: 11px;">
            <table cellpadding="0" cellspacing="0.5" border="0" style="width: 100%;">
                <tr>
                    <td colspan="2">RINCIAN GAJI</td>
                </tr>
                @foreach($gajiUniv->getAttributes() as $column => $value)
                    {{-- Exclude column dibawah --}}
                    @if (!in_array($column, ['id', 'deleted_at', 'created_at', 'updated_at']))
                        @if($value != 0)
                            <tr>
                                <td>• {{ ucfirst(str_replace('_', ' ', $column)) }}</td>
                                <td></td>
                                <td style="text-align: right">{{ format_rupiah($value) }}</td>
                            </tr>
                        @endif
                    @endif
                @endforeach

                @foreach($gajiFak->gaji_fakultas as $column => $value)
                    {{-- Exclude column dibawah --}}
                    @if (!in_array($column, ['id', 'deleted_at', 'created_at', 'updated_at']))
                        @if($value != 0)
                            <tr>
                                <td>• {{ ucfirst(str_replace('_', ' ', $column)) }}</td>
                                <td></td>
                                <td style="text-align: right">{{ format_rupiah($value) }}</td>
                            </tr>
                        @endif
                    @endif
                @endforeach
                <tr>
                    <td style="text-align: right">** JUMLAH **</td>
                    <td></td>
                    <td style="text-align: right;">{{ format_rupiah($totalPendapatan) }}</td>
                </tr>
            </table>
        </div>

        <div style="text-align: center; font-size: 11px;">
            <p style="margin-bottom: -12px">1 {{ $bulanTahun }}</p>
            <p>Tanda Tangan</p>
            <p style="margin-top: 40px">{{ $pegawai->nama }}</p>
        </div>
    </div>

{{-- Format Rupiah --}}
@php
function format_rupiah($number){
    return 'Rp ' . number_format($number, 0, ',', '.');
}
@endphp

{{-- Format terbilang rupiah --}}
@php
    function terbilang_rupiah($number) {
    $angka = ["", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas"];
    $temp = "";
    if ($number < 12) {
        return " " . $angka[$number];
    } else if ($number < 20) {
        return terbilang_rupiah($number - 10) . " belas";
    } else if ($number < 100) {
        $temp = terbilang_rupiah($number / 10);
        return $temp . " puluh" . terbilang_rupiah($number % 10);
    } else if ($number < 200) {
        return " seratus" . terbilang_rupiah($number - 100);
    } else if ($number < 1000) {
        $temp = terbilang_rupiah($number / 100);
        return $temp . " ratus" . terbilang_rupiah($number % 100);
    } else if ($number < 2000) {
        return " seribu" . terbilang_rupiah($number - 1000);
    } else if ($number < 1000000) {
        $temp = terbilang_rupiah($number / 1000);
        return $temp . " ribu" . terbilang_rupiah($number % 1000);
    } else if ($number < 1000000000) {
        $temp = terbilang_rupiah($number / 1000000);
        return $temp . " juta" . terbilang_rupiah($number % 1000000);
    }
    else if ($number < 1000000000000000) {
        $temp = terbilang_rupiah($number / 1000000000000);
        return $temp . " triliun" . terbilang_rupiah($number % 1000000000000);
    } else {
        return "Maaf, angka terlalu besar";
    }
}
@endphp
</body>
</html>