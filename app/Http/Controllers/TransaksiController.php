<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaksi;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;
use Carbon\Carbon;
use Throwable;

class TransaksiController extends Controller
{
    public function index()
    {
        $transaksis = Transaksi::orderBy('id_transaksi')
            ->paginate(10);

        return view('transaksi.index', compact('transaksis'));
    }

    public function export()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setTitle('Data Transaksi');

        $header = [
            'id_transaksi',
            'time',
            'namaBarang',
            'hargaBarang',
            'kuantitas'
        ];

        $sheet->fromArray($header, null, 'A1');

        $data = Transaksi::orderBy('id_transaksi')->get();

        $baris = 2;

        foreach ($data as $item) {
            $sheet->fromArray([
                $item->id_transaksi,
                $item->time,
                $item->namaBarang,
                $item->hargaBarang,
                $item->kuantitas
            ], null, 'A' . $baris);

            $baris++;
        }

        $sheet->getStyle('A1:E1')->getFont()->setBold(true);

        foreach (range('A', 'E') as $kolom) {
            $sheet->getColumnDimension($kolom)
                ->setAutoSize(true);
        }

        $writer = new Xlsx($spreadsheet);
        $filename = 'data_transaksi.xlsx';

        return response()->streamDownload(
            function () use ($writer) {
                $writer->save('php://output');
            },
            $filename,
            [
                'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
            ]
        );
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx|max:5120'
        ]);

        $spreadsheet = IOFactory::load(
            $request->file('file')->getRealPath()
        );

        $sheet = $spreadsheet->getActiveSheet();

        // Menggunakan TRUE di parameter ke-4 agar index bertipe huruf ('A', 'B', 'C', dst.)
        $rows = $sheet->toArray(null, true, true, true);

        // Ambil baris pertama (Header)
        $firstRow = reset($rows); 
        $headerActual = [
            trim($firstRow['A'] ?? ''),
            trim($firstRow['B'] ?? ''),
            trim($firstRow['C'] ?? ''),
            trim($firstRow['D'] ?? ''),
            trim($firstRow['E'] ?? '')
        ];

        $headerExpected = ['id_transaksi', 'time', 'namaBarang', 'hargaBarang', 'kuantitas'];

        if ($headerActual !== $headerExpected) {
            return back()->with(
                'error',
                'Format header Excel tidak sesuai! Pastikan Kolom A-E adalah: id_transaksi, time, namaBarang, hargaBarang, kuantitas.'
            );
        }

        $berhasil = 0;
        $duplikat = 0;
        $tidakValid = 0;

        $idDitemukan = [];

        // Hapus baris header (baris 1)
        array_shift($rows);

        foreach ($rows as $row) {
            $id     = trim((string) ($row['A'] ?? ''));
            $waktu  = $row['B'] ?? null;
            $nama   = trim((string) ($row['C'] ?? ''));
            $harga  = $row['D'] ?? null;
            $jumlah = $row['E'] ?? null;

            // Abaikan jika seluruh kolom dalam satu baris kosong
            if ($id === '' && $waktu === null && $nama === '' && $harga === null && $jumlah === null) {
                continue;
            }

            // Validasi kelengkapan & tipe data
            if (
                $id === '' ||
                $waktu === null ||
                trim((string) $waktu) === '' ||
                $nama === '' ||
                !is_numeric($harga) ||
                !is_numeric($jumlah) ||
                (float) $harga < 0 ||
                (int) $jumlah <= 0
            ) {
                $tidakValid++;
                continue;
            }

            // Cek duplikasi di file yang sama atau di Database
            if (
                isset($idDitemukan[$id]) ||
                Transaksi::where('id_transaksi', $id)->exists()
            ) {
                $duplikat++;
                continue;
            }

            // Konversi Waktu Excel/String ke format Y-m-d H:i:s
            try {
                if (is_numeric($waktu)) {
                    $tanggal = ExcelDate::excelToDateTimeObject((float) $waktu);
                    $waktuDB = $tanggal->format('Y-m-d H:i:s');
                } else {
                    $waktuDB = Carbon::parse($waktu)->format('Y-m-d H:i:s');
                }
            } catch (Throwable $e) {
                $tidakValid++;
                continue;
            }

            // Simpan Data ke Database
            try {
                Transaksi::create([
                    'id_transaksi' => $id,
                    'time'         => $waktuDB,
                    'namaBarang'   => $nama,
                    'hargaBarang'  => $harga,
                    'kuantitas'    => (int) $jumlah
                ]);

                $idDitemukan[$id] = true;
                $berhasil++;
            } catch (\Illuminate\Database\QueryException $e) {
                if ($e->getCode() === '23000') {
                    $duplikat++;
                } else {
                    throw $e;
                }
            }
        }

        return back()->with([
            'success'    => 'Proses import selesai!',
            'berhasil'   => $berhasil,
            'duplikat'   => $duplikat,
            'tidakValid' => $tidakValid
        ]);
    }
}