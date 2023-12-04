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

.row-gaji{
    position: relative;
    top: 40px;
}
.row-gaji table{
    width: 300px;
}
.row-gaji table th{
    background-color: lightgray;
}
.jumlah{
    font-weight: bold;
}

footer{
  margin-top: 50px;
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

.row-gaji table{
    width: 300px;
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
        <div class="row">
          <div class="tabel-pegawai">
            <table>
            {{-- Data Dosen Luar Biasa --}}
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

        <div class="row-gaji">
            <div class="tabel">
            <table>
                {{-- Data Gaji Univ dan Gaji Fak --}}
                <tr>
                    <th colspan="3">Pendapatan</th>
                </tr>

            {{-- Data Gaji Fak --}}
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

            <tr>
                <td class="jumlah">Jumlah Pendapatan</td>
                <td class="jumlah">:</td>
                <td class="jumlah">{{ format_rupiah($totalPendapatan) }}</td>
            </tr>
            </table>
            </div>
            <div class="tabel">
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
                    <tr>
                        <td class="jumlah">Jumlah Potongan</td>
                        <td class="jumlah">:</td>
                        <td class="jumlah">{{ format_rupiah($totalPotongan) }}</td>
                    </tr>
                </table>
        </div>
        <div>

        </div>
        <div>
            <table>
                <tr>
                    {{-- Data Pendapatan Bersih --}}
                    <th colspan="3">
                        Jumlah Yang Diterima {{ format_rupiah($pendapatanBersih) }}
                    </th>
                </tr>
            </table>
        </div>

        </div>

    </div>
  </main>

  <footer>
    <div class="container">
        <div class="row">
          <div class="text-footer">
            <p>Mengetahui,</p>
            <p>Kepala Sub Bagian Keuangan</p>
            <p class="footer-nama">Ibu Uyul</p>
          </div>
        </div>
  </footer>


  {{-- Format Rupiah --}}
  @php
  function format_rupiah($number){
      return 'Rp ' . number_format($number, 2, ',', '.');
  }
  @endphp

</body>
</html>
