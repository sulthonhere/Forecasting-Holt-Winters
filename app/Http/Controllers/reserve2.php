<?php

namespace App\Http\Controllers;

use DateTime;
use Illuminate\Http\Request;
use App\Models\Penjualan;
use App\Models\Peramalan;

class PeramalanController extends Controller
{
    public function index()
    {
        $penjualan = Penjualan::orderBy('waktu_penjualan')->get();
        $riwayatPeramalan = Peramalan::orderBy('created_at', 'desc')->get();
        
        return view('admin.peramalan', compact('penjualan', 'riwayatPeramalan'));
    }

    public function forecast(Request $request)
    {
        $validated = $request->validate([
            'alpha' => 'required|numeric',
            'beta' => 'required|numeric',
            'gamma' => 'required|numeric',
        ]);

        // IT 1
        $penjualan = Penjualan::orderBy('waktu_penjualan', 'ASC')->get()->toArray();
        // dd($penjualan);
        
        // Inisialisasi parameter
        // $alpha = $request->input('alpha');
        // $beta = $request->input('beta');
        // $gamma = $request->input('gamma');
        $alpha = 0.05;
        $beta = 0;
        $gamma = 0.12;

        $season_length = 12; // Panjang musim (12 bulan)
        $n_forecast = 8; // Jumlah bulan peramalan ke depan
        $n_bulan = [];
        $n_data = sizeof($penjualan);
        $history = [];
        
        $level = [];
        $trend = [];
        $seasonal = [];
        $forecast = [];

        $absError = [];
        $squaredError = [];
        $absPercentError = [];

        $mad = 0;
        $mse = 0;
        $mape = 0;

        // Init level
        $levelTemp = 0;
        for ($i = 0; $i < $season_length; $i++) {
            $levelTemp += $penjualan[$i]['jumlah'];
        }
        $level[] = $levelTemp / $season_length;
        // dd($levelTemp, $level); // ===ACC JAWIR===
        
        // Init trend
        $trendTemp = [];
        for ($i = 0; $i < $season_length; $i++) {
            $trendTemp[] = ($penjualan[12 + $i]['jumlah'] - $penjualan[$i]['jumlah']) / $season_length;
        }
        $trend[] = array_sum($trendTemp) / $season_length;
        // dd($trendTemp, $trend); // ===ACC JAWIR===
        // $initTrend = round($initTrend, 2);
        
        // Init seasonal
        for ($i = 0; $i < $season_length; $i++) {
            // $initSeasonalTemp = $penjualan[$i]['jumlah'] / $initLevel;
            // $initSeasonal[] = round($initSeasonalTemp, 2);
            $seasonal[] = $penjualan[$i]['jumlah'] / $level[0];
        }
        // dd($initSeasonal); ===ACC JAWIR===


        // Calculate Level
        // dd($n_data);
        // for ($i = 0; $i < $n_data ; $i++) {
        // dd($n_data, $n_forecast, $n_data + $n_forecast);
        $asu = [];
        $cok = [];
        // dd($n_data - $season_length);

        // dd($n_data, $n_forecast, $n_data + $n_forecast);
        // 57, 8, 65

        // dd($n_data, $season_length, $n_data - $season_length);
        // 57, 12, 45

        // Assign 12 month before forecast
        // for ($a = 0; $a < 12; $a++) {
        //     $n_bulan[] = $penjualan[$a]['waktu_penjualan'];
        // }
        // dd($n_bulan);

        // Menyimpan waktu_penjualan dari setiap data
        // $getWaktuPenjualanData = array_map(function($data) {
        //     return $data['waktu_penjualan'];
        // }, $penjualan);
        // dd($getWaktuPenjualanData);
        // dd($bulanForecast);

        // Calculate fitted value
        // dd($n_data);
        // dd($n_data - 11, $n_data - 12);
        for ($f = 0; $f < 57; $f++) {    // 65
            $n_bulan[] = $penjualan[$f]['waktu_penjualan'];
            // dd($n_bulan);
            // dd($penjualan[12 + $f]['jumlah'], $level[$f], $seasonal[$f]);
            
            if ($f == 11) {
                // dd($f, $n_bulan, $level, $trend, $seasonal, $forecast);
                // dd(
                //     $penjualan[$f]['jumlah'], 
                //     $seasonal[$f], 
                //     $level[$f - 11], 
                //     $trend[$f - 11]
                // );
                // dd(
                //     $f,
                //     $penjualan[$f]['jumlah'], 
                //     $seasonal[$f], 
                //     $level, 
                //     $level[$f - 11], 
                //     $trend[$f - 11]
                // );
                $level[] = $alpha * ($penjualan[$f]['jumlah'] - $seasonal[$f]) + (1 - $alpha) * ($level[$f - 11] + $trend[$f - 11]);
                $trend[] = $beta * ($level[$f - 10] - 
                    $level[$f - 11]) 
                        + (1 - $beta) 
                    * $trend[$f - 11];
            }
            elseif ($f >= 12 || $f < 57) {
                // dd($seasonal);
                dd($f);
                $level[] = $alpha * ($penjualan[12 + $f]['jumlah'] 
                    - $seasonal[$f - 1]) 
                    + (1 - $alpha) 
                    * ($level[$f - 12] 
                    + $trend[$f - 12]);
                $trend[] = $beta * ($level[$f - 11] 
                    - $level[$f - 12]) 
                    + (1 - $beta) 
                    * $trend[$f - 12];
                $seasonal[] = $gamma * ($penjualan[12 + $f]['jumlah'] 
                    - $level[$f - 12]) 
                    + (1 - $gamma) 
                    * $seasonal[$f - 1];
                $forecast[] = $level[$f - 12] + 1 
                    * $trend[$f - 12] 
                    + $seasonal[$f - 1];

                $absError[] = abs($penjualan[12 + $f]['jumlah'] - $forecast[$f - 12]);
                $squaredError[] = pow($absError[$f - 12], 2);
                $absPercentError[] = ($absError[$f - 12] / $penjualan[12 + $f]['jumlah']) * 100;
            }
            
            // $mad = array_sum($absError) / count($absError);
            // $mse = array_sum($squaredError) / count($squaredError);
            // $mape = array_sum($absPercentError) / count($absPercentError);
            
            /*
            // dd($absPercentError, $mape);
            if ($f >= $season_length) {
                $history[] =[
                    'periode' => $n_bulan[$f],
                    'penjualan' => $penjualan[$f]['jumlah'],

                    'level' => $level[$f - $season_length + 1],
                    'trend' => $trend[$f - $season_length + 1],
                    'seasonal' => $seasonal[$f],
                    'forecast' => $forecast[$f - $season_length],
                    
                    'absError' => $absError[$f - $season_length],
                    'squaredError' => $squaredError[$f - $season_length],
                    'absPercentError' => $absPercentError[$f - $season_length],

                ];
                // dd($f - $season_length);

            }
            else {
                if ($f == $season_length - 1) {
                    $history[] = [
                        'periode' => $n_bulan[$f],
                        'penjualan' => $penjualan[$f]['jumlah'],

                        'level' => $level[$season_length - $f - 1],
                        'trend' => $trend[$season_length - $f - 1],
                        'seasonal' => $seasonal[$f],
                    ];
                    // dd($season_length - $f);
                } 
                else {
                    $history[] = [
                        'periode' => $n_bulan[$f],
                        'penjualan' => $penjualan[$f]['jumlah'],
    
                        'seasonal' => $seasonal[$f],
                    ];    
                    // dd($f, $n_bulan[$f], $penjualan[$f]['jumlah'], $seasonal[$f]);
                }
            }
            
            */
            // if ($f > 11) {
            // }
        }
        // dd($absPercentError, $mad, $mse, $mape);
        // dd($history);
        dd($n_bulan, $level, $trend, $seasonal, $forecast);
        // dd($f);
        if ($n_forecast != 0) {
            // $n_sesudah = new DateTime(end($getWaktuPenjualanData));
            $n_sesudah = new DateTime(end($n_bulan));
            // dd($n_sesudah);
            $n_sebelum = $n_bulan;
            dd(count($n_bulan), $n_bulan, $level, $trend, $seasonal, $forecast);
            dd($n_bulan);
            for ($i = 0; $i < $n_forecast; $i++) { 
                // Tambahkan 1 bulan ke dalam tanggalAwal
                $n_sesudah->modify('+1 month');
                
                // Masukkan tanggal ke dalam array dalam format 'yyyy-mm-dd'
                $bulanForecast[] = $n_sesudah->format('Y-m-d');
                $n_bulan[] = $bulanForecast[$i];
            }
            // dd($f, $i, $n_bulan[$f], $n_bulan[$f + $season_length], $n_bulan);
            foreach ($bulanForecast as $data) {
                $n_bulan[] = $data;
            }
            dd($n_bulan);
            // dd($n_sebelum, $n_bulan, $bulanForecast);
            for ($fore = 0; $fore < $n_forecast; $fore++) {
                // dd($f);
                $asd[] = $n_bulan[count($n_sebelum)];
                $getMonthDigit = substr($bulanForecast[$fore], 5, 2);
                // dd($bulanForecast, count($n_sebelum), $asd, $getMonthDigit);
                // dd($seasonal[$f], $fore);
                // dd(end($level), $getMonthDigit, end($trend), $seasonal[$f]);
                $forecast[] = end($level) + $getMonthDigit * end($trend) + $seasonal[$f];
                $f++;
                // dd($bulanForecast);
            }
        }
        // dd($f);
        // dd(end($level), end($trend), $seasonal, $f, $f - 8, $seasonal[$f - 8]);
        // dd($bulanForecast, $forecast);
        dd($n_bulan, $level, $trend, $seasonal, $forecast);
        dd(count($n_bulan), $n_bulan, $level, $trend, $seasonal, $forecast);
        dd(count($n_bulan), $n_bulan, $level, $trend, $seasonal, $forecast, $absPercentError);
        // dd($asu, $cok);
        // dd($level, $trend, $seasonal, $forecast);

        return redirect()->route('peramalan.index')->with('success', 'Peramalan berhasil dilakukan.');
    }
}
