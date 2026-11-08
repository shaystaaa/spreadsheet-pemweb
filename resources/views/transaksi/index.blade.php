
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1">

    <title>Manajemen Transaksi</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">

</head>

<body class="bg-light">

<div class="container py-5">

    <!-- Judul -->
    <div class="mb-4">

        <h2>Manajemen Data Transaksi</h2>

        <p class="text-muted">
            Laravel 13 - MySQL - PhpSpreadsheet
        </p>

    </div>


    <!-- Pesan sukses -->
    @if(session('success'))

        <div class="alert alert-success">

            {{ session('success') }}

            <hr>

            <strong>Hasil Import:</strong>

            <ul class="mb-0">

                <li>
                    Data berhasil:
                    {{ session('berhasil') }}
                </li>

                <li>
                    Data ditolak (duplikat):
                    {{ session('duplikat') }}
                </li>

                <li>
                    Data tidak valid:
                    {{ session('tidakValid') }}
                </li>

            </ul>

        </div>

    @endif


    <!-- Pesan error -->
    @if(session('error'))

        <div class="alert alert-danger">
            {{ session('error') }}
        </div>

    @endif


    <!-- Error validasi -->
    @if($errors->any())

        <div class="alert alert-danger">

            <strong>Terjadi kesalahan:</strong>

            <ul class="mb-0">

                @foreach($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>

        </div>

    @endif


    <!-- Tombol Export -->
    <div class="card mb-4">

        <div class="card-body">

            <h5>1. Export Data Excel</h5>

            <p>
                Mengunduh seluruh data transaksi
                dari database menjadi file XLSX.
            </p>

            <a href="{{ route('transaksi.export') }}"
               class="btn btn-success">

                Export ke Excel

            </a>

        </div>

    </div>


    <!-- Form Import -->
    <div class="card mb-4">

        <div class="card-body">

            <h5>2. Import Data Excel</h5>

            <p>
                Pilih file Excel untuk dimasukkan
                ke database.
            </p>

            <form action="{{ route('transaksi.import') }}"
                  method="POST"
                  enctype="multipart/form-data">

                @csrf

                <div class="mb-3">

                    <label class="form-label">
                        Pilih File Excel (.xlsx)
                    </label>

                    <input type="file"
                           name="file"
                           class="form-control"
                           accept=".xlsx"
                           required>

                </div>

                <button type="submit"
                        class="btn btn-primary">

                    Import Excel

                </button>

            </form>

        </div>

    </div>


    <!-- Tabel Transaksi -->
    <div class="card">

        <div class="card-body">

            <h5>3. Data Transaksi</h5>

            <div class="table-responsive">

                <table class="table table-bordered
                              table-striped table-hover">

                    <thead class="table-dark text-center align-middle">

                        <tr>

                            <th>No</th>

                            <th>ID Transaksi</th>

                            <th>Waktu</th>

                            <th>Nama Barang</th>

                            <th>Harga Barang</th>

                            <th>Kuantitas</th>

                        </tr>

                    </thead>

                    <tbody>

                        @forelse($transaksis as $index => $item)

                            <tr>

                                <td>
                                    {{ $transaksis->firstItem() + $index }}
                                </td>

                                <td>
                                    {{ $item->id_transaksi }}
                                </td>

                                <td>
                                    {{ $item->time }}
                                </td>

                                <td>
                                    {{ $item->namaBarang }}
                                </td>

                                <td class="text-end">
                                    Rp {{ number_format(
                                        $item->hargaBarang,
                                        0,
                                        ',',
                                        '.'
                                    ) }}
                                </td>

                                <td >
                                    {{ $item->kuantitas }}
                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="6"
                                    class="text-center">

                                    Belum ada data transaksi.

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

            <!-- Pagination -->
            {{ $transaksis->links('pagination::bootstrap-5') }}

        </div>

    </div>

</div>

</body>

</html>
