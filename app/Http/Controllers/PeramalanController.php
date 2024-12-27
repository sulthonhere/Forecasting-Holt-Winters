<?php
// CATATAN 1, hanya nilai forecast masih salah
// akibat nilai seasonal yang diulang menggunakan variabel yang berbeda
namespace App\Http\Controllers;

use DateTime;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Penjualan;
use App\Models\Peramalan;
use RealRashid\SweetAlert\Facades\Alert;

class PeramalanController extends Controller
{
    public function index()
    {
        $penjualan = Penjualan::orderBy('waktu_penjualan')->get();
        $riwayatPeramalan = Peramalan::orderBy('created_at', 'desc')->get();
        $statusPenjualan = Penjualan::count();
        
        if ($statusPenjualan != 0) {

            $availableDates = Penjualan::select('waktu_penjualan')
                ->distinct()->orderBy('waktu_penjualan', 'ASC')->get()
                ->pluck('waktu_penjualan');
    
            $penjualanTerbaru = Penjualan::select('waktu_penjualan')->distinct()
                ->orderBy('waktu_penjualan', 'ASC')->first();
            // dd($penjualanTerbaru, $penjualanTerlama);
    
            $satuTahunSetelahTerbaru = Carbon::parse($penjualanTerbaru->waktu_penjualan)->addYears(1);
            $tigaTahunSetelahTerbaru = Carbon::parse($penjualanTerbaru->waktu_penjualan)->addYears(3);
            // dd($satuTahunSetelahTerbaru, $duaTahunSetelahTerlama);
    
            $periodePenjualanAwal = Penjualan::where('waktu_penjualan', '<', $satuTahunSetelahTerbaru)
                ->orderBy('waktu_penjualan', 'ASC')->get()
                ->pluck('waktu_penjualan');
    
            // $periodePenjualanAwal = Penjualan::where('waktu_penjualan', '>=', $satuTahunSetelahTerbaru)
            //     ->orderBy('waktu_penjualan', 'ASC')
            //     ->get()->toArray();
    
            $periodePenjualanAkhir = Penjualan::where('waktu_penjualan', '>=', $tigaTahunSetelahTerbaru)
                ->orderBy('waktu_penjualan', 'ASC')->get()
                ->pluck('waktu_penjualan');
            
            $banyak_data_penjualan = Penjualan::where('waktu_penjualan', '>=', $tigaTahunSetelahTerbaru)->count();
            $banyak_data_penjualan == 0 ? $status_data_penjualan = null : $status_data_penjualan = $banyak_data_penjualan;
            // dd($status_data_penjualan);
            // dd($availableDates, $periodePenjualanAwal, $periodePenjualanAkhir);

            confirmDelete();    // Include swal.fire for view

            return view('admin.peramalan', compact(
                'penjualan', 'riwayatPeramalan', 
                'periodePenjualanAwal', 'periodePenjualanAkhir',
                'status_data_penjualan'
            ));
        } 
        else {
            confirmDelete();
            return view('admin.peramalan');
        }
    }

    public function forecast(Request $request)
    {
        // dd($request->all());

        $tanggalPenjualanTertua = Penjualan::orderBy('waktu_penjualan', 'ASC')
            ->first()
            ->waktu_penjualan;

        // Convert waktu_penjualan tertua ke Carbon untuk perhitungan
        $tanggalPenjualanTertuaCarbon = Carbon::parse($tanggalPenjualanTertua);

        // Hitung batas 2 tahun dari tanggal penjualan tertua
        $batasDuaTahun = $tanggalPenjualanTertuaCarbon->addMonths(23);
        // dd($request->all());

        $this->validate($request, [
            'periode_awal' => 'required',
            'periode_akhir' => 'required',
            'bulan_peramalan' => 'required',
        ]);

        $periodeAwal = $request->input('periode_awal');
        $periodeAkhir = $request->input('periode_akhir');
        $n_forecast = $request->input('bulan_peramalan');

        // dd($periodeAwal, $periodeAkhir, $n_forecast);
        // dd('asu');
        // dd($periodeAwal, $periodeAkhir, $n_forecast);

        // Cek apakah periode_awal atau periode_akhir kurang dari 2 tahun dari waktu_penjualan tertua
        // if ($periodeAkhir < $batasDuaTahun) {
        //     Alert::error('Gagal!', 'Periode peramalan minimal 2 tahun setelah periode penjualan pertama');
        //     return redirect()->back()->withInput();
        // }

        $penjualan = Penjualan::whereBetween('waktu_penjualan', [$periodeAwal, $periodeAkhir])
            ->orderBy('waktu_penjualan', 'ASC')
            ->get()->toArray();

        // Inisialisasi parameter
        $alpha = $request->input('alpha');
        $beta = $request->input('beta');
        $gamma = $request->input('gamma');

        if ($alpha == null || $beta == null || $gamma == null) {
            $optimumParameters = $this->findOptimumParameters($penjualan);
            
            $alpha = $optimumParameters['alpha'];
            $beta = $optimumParameters['beta'];
            $gamma = $optimumParameters['gamma'];
        }

        $season_length = 12; // Panjang musim (12 bulan)
        $n_bulan = [];
        $n_data = sizeof($penjualan);
        
        $level = [];
        $trend = [];
        $seasonal = [];
        $forecast = [];

        $absError = [];
        $squaredError = [];
        $absPercentError = [];

        // Init level
        $levelTemp = 0;
        for ($i = 0; $i < $season_length; $i++) {
            $levelTemp += $penjualan[$i]['jumlah'];
        }
        $level[] = $levelTemp / $season_length;        

        // Init trend
        $trendTemp = [];
        for ($i = 0; $i < $season_length; $i++) {
            $trendTemp[] = ($penjualan[12 + $i]['jumlah'] - $penjualan[$i]['jumlah']) / $season_length;
        }
        $trend[] = array_sum($trendTemp) / $season_length;
        
        // Init seasonal
        for ($i = 0; $i < $season_length; $i++) {
            $seasonal[] = $penjualan[$i]['jumlah'] / $level[0];
        }

        // Assign 12 month before forecast
        for ($i = 0; $i < 12; $i++) {
            $n_bulan[] = $penjualan[$i]['waktu_penjualan'];
        }

        // Menyimpan waktu_penjualan dari setiap data
        $getWaktuPenjualan = array_map(function($data) {
            return $data['waktu_penjualan'];
        }, $penjualan);
                
        // dd($bulanForecast);

        // Calculate fitted value
        for ($a = 0; $a < $n_data - $season_length; $a++) {    // 45
            $n_bulan[] = $penjualan[$a + 12]['waktu_penjualan'];

            $level[] = $alpha * ($penjualan[$a + 12]['jumlah'] - $seasonal[$a]) + (1 - $alpha) * ($level[$a] + $trend[$a]);
            $trend[] = $beta * ($level[$a + 1] - $level[$a]) + (1 - $beta) * $trend[$a];
            $seasonal[] = $gamma * ($penjualan[$a + 12]['jumlah'] - $level[1 + $a]) + (1 - $gamma) * $seasonal[$a];
            
            $forecast[] = $level[$a] + 1 * $trend[$a] + $seasonal[$a];
            
            $absError[] = abs($penjualan[$a + 12]['jumlah'] - $forecast[$a]);
            $squaredError[] = pow($absError[$a], 2);
            $absPercentError[] = ($absError[$a] / $penjualan[$a + 12]['jumlah']) * 100;   
        }

        $mad = array_sum($absError) / count($absError);
        $mse = array_sum($squaredError) / count($squaredError);
        $mape = array_sum($absPercentError) / count($absPercentError);
    
        // dd($n_data, $n_bulan, $level, $trend, $seasonal, $forecast);

        if ($n_forecast != 0) {
            
            $n_sesudah = new DateTime(end($getWaktuPenjualan));
            for ($i = 0; $i < $n_forecast; $i++) { 
                $n_sesudah->modify('+1 month');
                $bulanForecast[] = $n_sesudah->format('Y-m-d');
            }
            
            foreach ($bulanForecast as $data) {     // Add to history -> waktu_penjualan
                $n_bulan[] = $data;
            }
            // dd($n_sebelum, $n_sesudah, $bulanForecast, $n_bulan);

            for ($f = 0; $f < $n_forecast; $f++) {
                $getMonthDigit[] = substr($bulanForecast[$f], 5, 2);
                
                $forecast[] = end($level) + $getMonthDigit[$f] * end($trend) + $seasonal[$a];
                $a++;
            }

        }
        // dd($n_data, $n_bulan, $level, $trend, $seasonal, $forecast);

        // return view('admin.peramalan', compact(
        //     'n_bulan', 
        //     'level', 'trend', 'seasonal', 'forecast',
        //     'mad', 'mse', 'mape'
        // ));
        // dd($combined);
        
        // Buat array asosiatif dari dua array
        $forecastData = [];
        $maxLength = max(
            count($n_bulan), 
            count($level), count($level), count($seasonal), count($forecast), 
        );
        // dd($n_forecast, $n_bulan, $level, $trend, $seasonal, $forecast);

        for ($i = 0; $i < $maxLength; $i++) {
            $forecastData[] = [
                'periode' => $n_bulan[$i] ?? null,
                'penjualan' => $penjualan[$i]['jumlah'] ?? 0,
                'level' => $level[$i - $season_length + 1] ?? null,
                'trend' => $level[$i - $season_length + 1] ?? null,
                'seasonal' => $seasonal[$i] ?? null,
                'forecast' => $forecast[$i - $season_length] ?? null,
            ];
        }
        // dd($maxLength, $forecastData, 'asu');
        $periodeAwalData = Carbon::parse($periodeAwal)->format('F Y');
        $periodeAkhirData = Carbon::parse($periodeAkhir)->format('F Y');
        // dd($periodeAwalData, $periodeAkhirData);

        $madData = number_format($mad, 2, ',', '.');
        $mseData = number_format($mse, 2, ',', '.');
        $mapeData = number_format($mape, 2, ',', '.');
        // dd($forecastData);
        return view('admin.peramalan', compact(
            'periodeAwalData', 'periodeAkhirData', 
            'forecastData',
            'madData', 'mseData', 'mapeData',
            'alpha', 'beta', 'gamma'
        ));
    }

    public function findOptimumParameters($dataIn) {
            $optimalAlpha = 0;
            $optimalBeta = 0;
            $optimalGamma = 0;
            $minMape = PHP_INT_MAX; // Nilai MAPE awal besar
        
            // Loop untuk mencoba berbagai nilai alpha, beta, gamma
            for ($alpha = 0; $alpha <= 1; $alpha += 0.1) {
                for ($beta = 0; $beta <= 1; $beta += 0.1) {
                    for ($gamma = 0; $gamma <= 1; $gamma += 0.1) {
                        
                        $season_length = 12; // Panjang musim (12 bulan)
                        $n_data = sizeof($dataIn);
                        
                        $level = [];
                        $trend = [];
                        $seasonal = [];
                        $forecast = [];
    
                        $absError = [];
                        $absPercentError = [];
    
                        // Init level
                        $levelTemp = 0;
                        for ($i = 0; $i < $season_length; $i++) {
                            $levelTemp += $dataIn[$i]['jumlah'];
                        }
                        $level[] = $levelTemp / $season_length;        
    
                        // Init trend
                        $trendTemp = [];
                        for ($i = 0; $i < $season_length; $i++) {
                            $trendTemp[] = ($dataIn[12 + $i]['jumlah'] - $dataIn[$i]['jumlah']) / $season_length;
                        }
                        $trend[] = array_sum($trendTemp) / $season_length;
                        
                        // Init seasonal
                        for ($i = 0; $i < $season_length; $i++) {
                            $seasonal[] = $dataIn[$i]['jumlah'] / $level[0];
                        }
    
                        // Assign 12 month before forecast
                        for ($i = 0; $i < 12; $i++) {
                            $n_bulan[] = $dataIn[$i]['waktu_penjualan'];
                        }
    
                        // Menyimpan waktu_penjualan dari setiap data
                        $getWaktuPenjualan = array_map(function($data) {
                            return $data['waktu_penjualan'];
                        }, $dataIn);
                                
                        // dd($bulanForecast);
    
                        // Calculate fitted value
                        for ($a = 0; $a < $n_data - $season_length; $a++) {    // 45
                            $n_bulan[] = $dataIn[$a + 12]['waktu_penjualan'];
    
                            $level[] = $alpha * ($dataIn[$a + 12]['jumlah'] - $seasonal[$a]) + (1 - $alpha) * ($level[$a] + $trend[$a]);
                            $trend[] = $beta * ($level[$a + 1] - $level[$a]) + (1 - $beta) * $trend[$a];
                            $seasonal[] = $gamma * ($dataIn[$a + 12]['jumlah'] - $level[1 + $a]) + (1 - $gamma) * $seasonal[$a];
                            
                            $forecast[] = $level[$a] + 1 * $trend[$a] + $seasonal[$a];
                            
                            $absError[] = abs($dataIn[$a + 12]['jumlah'] - $forecast[$a]);
                            $absPercentError[] = ($absError[$a] / $dataIn[$a + 12]['jumlah']) * 100;   
                        }
    
                        $mape = array_sum($absPercentError) / count($absPercentError);

                        // Simpan parameter jika MAPE lebih kecil
                        if ($mape < $minMape) {
                            $minMape = $mape;
                            $optimalAlpha = $alpha;
                            $optimalBeta = $beta;
                            $optimalGamma = $gamma;
                        }
                    }
                }
            }
            // dd($optimalAlpha, $optimalBeta, $optimalGamma, $minMape);
            return [
                'alpha' => $optimalAlpha,
                'beta' => $optimalBeta,
                'gamma' => $optimalGamma,
                'mape' => $minMape,
            ];
        
    }
}


// Masalah ada di n_data - 1 pada for $i
// waktu_penjualan hingga for $i selesai tidak ada
// Aman 4, base on Aman 3