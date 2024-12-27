<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Penjualan;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $jumlahManager = User::where('role', 2)->count();
        $jumlahStaff = User::where('role', 3)->count();

        $penjualan = Penjualan::selectRaw('DATE_FORMAT(waktu_penjualan, "%M-%Y") as waktu_penjualan, SUM(jumlah) as jumlah')
                        ->groupBy('waktu_penjualan')
                        ->orderByRaw('MIN(waktu_penjualan) ASC')
                        ->get();

        // $penjualan = Penjualan::orderBy('waktu_penjualan', 'ASC')->get();
        
        return view('global.dashboard', compact('jumlahManager', 'jumlahStaff', 'penjualan'));
    }

}
