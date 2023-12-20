<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">


  <style>
.container-header p{
text-align: right;
position: relative;
top: 10px;
}

.container-header hr{
    position: relative;
    top: 10px;
    border: 0;
    border-top: 3px double black;
}

.image img{
    position: absolute;
    top: 1px;
    left: 50px;
}

.fakultas{
    font-weight: bolder;
}

.container-periode {
text-align: center;
font-weight: bolder;
}

.tabel-pegawai{
    position: relative;
    top: 20px;
}
.tabel-pegawai table{
    width: 600px;
}

.tabel-gaji, .tabel-potongan{
    position: relative;
    top: 40px;
}
.tabel-gaji table, .tabel-potongan table{
    width: 300px;
}
.tabel-gaji table th, .tabel-potongan table th{
    background-color: lightgray;
}
.jumlah{
    font-weight: bold;
}
.tabel-gaji table{
    float: left;
}
.tabel-potongan table{
    float: right;
}

.tabel-jumlah{
    clear: both;
    position: relative;
    top: 10px;
}
.tabel-jumlah table{
    width: 300px;
    background-color: lightgray;
}
.terbilang{
    font-style: italic;
    text-align: center;
}


footer{
  margin-top: 30px;
}

.text-footer p{
  text-align: right;
}

.footer-nama{
    margin-top: 80px;
    text-decoration: underline;

}

@media (max-width: 720px){
body{
    font-size: 10px;
}
.image img{
    height: 60px;
    left: 10px;
}
.container-header hr{
    top: 10px;
}
.container-periode {
position: relative;
top: 10px;
}

.tabel-gaji table, .tabel-potongan table{
    width: 300px;
}
.tabel-jumlah table{
    width: 700px;
}
}


  </style>
</head>
<body>
	<header>
        <div class="container-header">
          <div class="image">
            <img height="100px" src="{{ public_path('/img/unpas.png') }}" alt="Logo">
          </div>
                    <p class="fakultas">Fakultas Hukum Universitas Pasundan</p>
                    <p>Jl. Lengkong Besar No.68, Cikawao, Kec. Lengkong, Kota Bandung, Jawa Barat, Indonesia 40261</p>
                    <hr class="garis">
                </div>
  </header>

  <div class="container-periode">
    <div class="row">
        <p>Slip Gaji</p>
        <p>Periode {{ $bulanTahun }}</p>
    </div>
  </div>

  <main>
    <div class="container">
        <div class="tabel-pegawai">
            <table>
            {{-- Data Karyawan --}}
                <tr>
                    <td>No Pegawai</td>
                    <td>:</td>
                    <td>{{ $dosenlb->no_pegawai }}</td>
                </tr>
                <tr>
                    <td>Nama Lengkap</td>
                    <td>:</td>
                    <td>{{ $dosenlb->nama }}</td>
                </tr>
                <tr>
                    <td>Golongan</td>
                    <td>:</td>
                    <td>{{ $dosenlb->golongan }}</td>
                </tr>
                <tr>
                    <td>Jabatan</td>
                    <td>:</td>
                    <td>{{ $dosenlb->jabatan }}</td>
                </tr>
                <tr>
                    <td>NPWP</td>
                    <td>:</td>
                    <td>{{ $dosenlb->npwp }}</td>
                </tr>
            </table>
          </div>
        </div>

        <div class="container">
            <div class="tabel-gaji">
                <table>
                    {{-- Data Komponen Pendapatan--}}
                    <tr>
                        <th colspan="3">Pendapatan</th>
                    </tr>


                @foreach($komponenPendapatan->komponen_pendapatan as $column => $value)
                {{-- Exclude column yang tidak ingin ditampilkan --}}
                @if (!in_array($column, ['id', 'deleted_at', 'created_at', 'updated_at']))
                    <tr>
                        <td>{{ ucfirst(str_replace('_', ' ', $column)) }}</td>
                        <td>:</td>
                        <td>{{ format_rupiah($value) }}</td>
                    </tr>
                @endif
                @endforeach

                </table>
                </div>
        </div>

            <div class="container">
                <div class="tabel-potongan">
                    {{-- Data Potongan --}}
                    <table>
                        <tr>
                            <th colspan="3">Potongan</th>
                        </tr>
                        {{-- Data Potongan --}}
                        @foreach($potongan->potongan as $column => $value)
                        {{-- Exclude column yang tidak ingin ditampilkan --}}
                        @if (!in_array($column, ['id', 'deleted_at', 'created_at', 'updated_at']))
                            <tr>
                                <td>{{ ucfirst(str_replace('_', ' ', $column)) }}</td>
                                <td>:</td>
                                <td>{{ format_rupiah($value) }}</td>
                            </tr>
                        @endif
                        @endforeach
                    </table>
            </div>
            </div>

        <div class="container">
            <div class="tabel-jumlah">
                <table>
                    <tr>
                        <td class="jumlah" width="165px">Total Pendapatan</td>
                        <td  width="1px">:</td>
                        <td class="jumlah" width="230px">{{ format_rupiah($totalPendapatan) }}</td>

                        <td class="jumlah" width="100px">Total Potongan</td>
                        <td width="1px">:</td>
                        <td class="jumlah">{{ format_rupiah($totalPotongan) }}</td>
                    </tr>
                    <tr>
                        <td colspan="6"></td>
                    </tr>
                    <tr>
                        {{-- Data Pendapatan Bersih --}}
                        <td colspan="3"></td>

                        <th>
                            Jumlah Yang Diterima
                        </th>
                        <th>
                            :
                        </th>
                        <th style="text-align: left">
                            {{ format_rupiah($pendapatanBersih) }}
                        </th>
                    </tr>
                    <tr class="terbilang">
                        <td colspan="3"></td>
                        <td colspan="3" style="text-align: left">
                             ( Terbilang : {{ terbilang_rupiah($pendapatanBersih) }} rupiah )
                        </td>
                    </tr>
                </table>
            </div>
        </div>
  </main>


  <footer>
    <div class="container">
        <div class="row">
          <div class="text-footer">
            <p>Mengetahui,</p>
            <p>Kepala Sub Bagian Keuangan</p>
            <p class="footer-nama">Ruruh Ruhayati, S.Pd</p>
          </div>
        </div>
  </footer>


  {{-- Format Rupiah --}}
  @php
  function format_rupiah($number){
      return 'Rp. ' . number_format($number, 2, ',', '.');
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
