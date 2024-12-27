<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class PenjualanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $penjualanData = [
            ['created_by' => 0, 'waktu_penjualan' => '2019-01-01', 'jumlah' => 2322],
            ['created_by' => 0, 'waktu_penjualan' => '2019-02-01', 'jumlah' => 113862],
            ['created_by' => 0, 'waktu_penjualan' => '2019-03-01', 'jumlah' => 74882],
            ['created_by' => 0, 'waktu_penjualan' => '2019-04-01', 'jumlah' => 37066],
            ['created_by' => 0, 'waktu_penjualan' => '2019-05-01', 'jumlah' => 41850],
            ['created_by' => 0, 'waktu_penjualan' => '2019-06-01', 'jumlah' => 14964],
            ['created_by' => 0, 'waktu_penjualan' => '2019-07-01', 'jumlah' => 164690],
            ['created_by' => 0, 'waktu_penjualan' => '2019-08-01', 'jumlah' => 78905],
            ['created_by' => 0, 'waktu_penjualan' => '2019-09-01', 'jumlah' => 59180],
            ['created_by' => 0, 'waktu_penjualan' => '2019-10-01', 'jumlah' => 197542],
            ['created_by' => 0, 'waktu_penjualan' => '2019-11-01', 'jumlah' => 212835],
            ['created_by' => 0, 'waktu_penjualan' => '2019-12-01', 'jumlah' => 143921],

            ['created_by' => 0, 'waktu_penjualan' => '2020-01-01', 'jumlah' => 124358],
            ['created_by' => 0, 'waktu_penjualan' => '2020-02-01', 'jumlah' => 65145],
            ['created_by' => 0, 'waktu_penjualan' => '2020-03-01', 'jumlah' => 115799],
            ['created_by' => 0, 'waktu_penjualan' => '2020-04-01', 'jumlah' => 80281],
            ['created_by' => 0, 'waktu_penjualan' => '2020-05-01', 'jumlah' => 54266],
            ['created_by' => 0, 'waktu_penjualan' => '2020-06-01', 'jumlah' => 64371],
            ['created_by' => 0, 'waktu_penjualan' => '2020-07-01', 'jumlah' => 39345],
            ['created_by' => 0, 'waktu_penjualan' => '2020-08-01', 'jumlah' => 24791],
            ['created_by' => 0, 'waktu_penjualan' => '2020-09-01', 'jumlah' => 108231],
            ['created_by' => 0, 'waktu_penjualan' => '2020-10-01', 'jumlah' => 89096],
            ['created_by' => 0, 'waktu_penjualan' => '2020-11-01', 'jumlah' => 75981],
            ['created_by' => 0, 'waktu_penjualan' => '2020-12-01', 'jumlah' => 90844],

            ['created_by' => 0, 'waktu_penjualan' => '2021-01-01', 'jumlah' => 124873],
            ['created_by' => 0, 'waktu_penjualan' => '2021-02-01', 'jumlah' => 72198],
            ['created_by' => 0, 'waktu_penjualan' => '2021-03-01', 'jumlah' => 118895],
            ['created_by' => 0, 'waktu_penjualan' => '2021-04-01', 'jumlah' => 98427],
            ['created_by' => 0, 'waktu_penjualan' => '2021-05-01', 'jumlah' => 9976],
            ['created_by' => 0, 'waktu_penjualan' => '2021-06-01', 'jumlah' => 77615],
            ['created_by' => 0, 'waktu_penjualan' => '2021-07-01', 'jumlah' => 101480],
            ['created_by' => 0, 'waktu_penjualan' => '2021-08-01', 'jumlah' => 145039],
            ['created_by' => 0, 'waktu_penjualan' => '2021-09-01', 'jumlah' => 129301],
            ['created_by' => 0, 'waktu_penjualan' => '2021-10-01', 'jumlah' => 128570],
            ['created_by' => 0, 'waktu_penjualan' => '2021-11-01', 'jumlah' => 88064],
            ['created_by' => 0, 'waktu_penjualan' => '2021-12-01', 'jumlah' => 80926],

            ['created_by' => 0, 'waktu_penjualan' => '2022-01-01', 'jumlah' => 102727],
            ['created_by' => 0, 'waktu_penjualan' => '2022-02-01', 'jumlah' => 68800],
            ['created_by' => 0, 'waktu_penjualan' => '2022-03-01', 'jumlah' => 168087],
            ['created_by' => 0, 'waktu_penjualan' => '2022-04-01', 'jumlah' => 192167],
            ['created_by' => 0, 'waktu_penjualan' => '2022-05-01', 'jumlah' => 78647],
            ['created_by' => 0, 'waktu_penjualan' => '2022-06-01', 'jumlah' => 115670],
            ['created_by' => 0, 'waktu_penjualan' => '2022-07-01', 'jumlah' => 34929],
            ['created_by' => 0, 'waktu_penjualan' => '2022-08-01', 'jumlah' => 135665],
            ['created_by' => 0, 'waktu_penjualan' => '2022-09-01', 'jumlah' => 83850],
            ['created_by' => 0, 'waktu_penjualan' => '2022-10-01', 'jumlah' => 83248],
            ['created_by' => 0, 'waktu_penjualan' => '2022-11-01', 'jumlah' => 73745],
            ['created_by' => 0, 'waktu_penjualan' => '2022-12-01', 'jumlah' => 78854],

            ['created_by' => 0, 'waktu_penjualan' => '2023-01-01', 'jumlah' => 25026],
            ['created_by' => 0, 'waktu_penjualan' => '2023-02-01', 'jumlah' => 58108],
            ['created_by' => 0, 'waktu_penjualan' => '2023-03-01', 'jumlah' => 47816],
            ['created_by' => 0, 'waktu_penjualan' => '2023-04-01', 'jumlah' => 74906],
            ['created_by' => 0, 'waktu_penjualan' => '2023-05-01', 'jumlah' => 84280],
            ['created_by' => 0, 'waktu_penjualan' => '2023-06-01', 'jumlah' => 149253],
            ['created_by' => 0, 'waktu_penjualan' => '2023-07-01', 'jumlah' => 43774],
            ['created_by' => 0, 'waktu_penjualan' => '2023-08-01', 'jumlah' => 75040],
            ['created_by' => 0, 'waktu_penjualan' => '2023-09-01', 'jumlah' => 64414],
        ];

        DB::table('penjualans')->insert($penjualanData);
    }
}
