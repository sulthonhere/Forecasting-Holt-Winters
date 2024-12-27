<?php

namespace App\Imports;

use App\Models\Penjualan;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Validators\ValidationException;

// class PenjualanImport implements ToModel, WithStartRow
// {
//     private $successCount = 0;
//     private $failedCount = 0;

//     /**
//     * @param array $row
//     *
//     * @return \Illuminate\Database\Eloquent\Model|null
//     */
//     public function model(array $row)
//     {
//         // Memeriksa apakah ada nilai kosong di dalam baris data
//         if (in_array(null, $row, true)) {
//             $this->failedCount++;
//             // Anda dapat menambahkan log atau melakukan tindakan lain sesuai kebutuhan
//             Log::error("Baris data gagal diimpor karena terdapat cell kosong: " . implode(', ', $row));
//             return null; // Mengembalikan null untuk mengabaikan baris data yang tidak valid
//         }

//         $this->successCount++;
        
//         // Mengembalikan model Sertif baru dengan data dari baris excel
//         return new Penjualan([
//             'waktu_penjualan' => \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[0]),
//             'jumlah' => $row[1],
//         ]);
//     }

//     public function getSuccessCount()
//     {
//         return $this->successCount;
//     }

//     public function getFailedCount()
//     {
//         return $this->failedCount;
//     }

//     public function startRow(): int
//     {
//         return 2;
//     }
// }

class PenjualanImport implements ToModel, WithStartRow
{
    private $successCount = 0;
    private $failedCount = 0;

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        // Memeriksa apakah ada nilai kosong di dalam baris data
        if (in_array(null, $row, true)) {
            $this->failedCount++;
            // Anda dapat menambahkan log atau melakukan tindakan lain sesuai kebutuhan
            Log::error("Baris data gagal diimpor karena terdapat cell kosong: " . implode(', ', $row));
            return null; // Mengembalikan null untuk mengabaikan baris data yang tidak valid
        }

        // Memeriksa apakah tanggal sudah ada di database
        $existingData = Penjualan::where('waktu_penjualan', \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[0]))->first();
        if ($existingData) {
            $this->failedCount++;
            Log::error("Baris data gagal diimpor karena tanggal sudah ada di database: " . implode(', ', $row));
            return null; // Mengembalikan null untuk mengabaikan baris data yang tidak valid
        }

        // Memeriksa apakah jumlah adalah angka
        if (!is_numeric($row[1])) {
            $this->failedCount++;
            Log::error("Baris data gagal diimpor karena jumlah tidak berupa angka: " . implode(', ', $row));
            return null; // Mengembalikan null untuk mengabaikan baris data yang tidak valid
        }

        $this->successCount++;
        
        // Mengembalikan model Sertif baru dengan data dari baris excel
        return new Penjualan([
            'waktu_penjualan' => \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[0]),
            'jumlah' => $row[1],
        ]);
    }

    public function getSuccessCount()
    {
        return $this->successCount;
    }

    public function getFailedCount()
    {
        return $this->failedCount;
    }

    public function startRow(): int
    {
        return 2;
    }
}