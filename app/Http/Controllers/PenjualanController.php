<?php

namespace App\Http\Controllers;

use App\Models\Penjualan;
use App\Imports\PenjualanImport;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon; 
use Illuminate\Support\Facades\Storage;

use RealRashid\SweetAlert\Facades\Alert;
use Maatwebsite\Excel\Facades\Excel;


class PenjualanController extends Controller
{
    public function index()
    {
        $penjualan = Penjualan::all();
        confirmDelete('Hapus Data', "Apakah anda yakin?!");

        return view('global.penjualan', compact('penjualan'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'waktu_penjualan' => 'required|date_format:Y-m', 
            'jumlah' => 'required|integer',
        ]);

        $bulanTahun = $request->waktu_penjualan . '-01';
        $bulanExist = Carbon::parse($request->waktu_penjualan)->translatedFormat('F Y');
        
        $penjualan_exist = Penjualan::where('waktu_penjualan', $bulanTahun)->first();
        if ($penjualan_exist) {
            session()->flash('message', "Penjualan pada $bulanExist sudah ada!");
            return redirect()->back();
        }

        Penjualan::create([
            'waktu_penjualan' => $bulanTahun,
            'jumlah' => $request->jumlah,
            'created_by' => $request->created_by,
        ]);

        toast('Data berhasil ditambahkan', 'success');
        return redirect()->route('penjualan.index');
    }

    public function update(Request $request, Penjualan $penjualan)
    {
        $request->validate([
            'waktu_penjualan' => 'required|date_format:Y-m',
            'jumlah' => 'required|integer',
        ]);

        $bulanTahun = $request->waktu_penjualan . '-01';
        
        $bulanExist = Carbon::parse($request->waktu_penjualan)->translatedFormat('F Y');

        $existingPenjualan = Penjualan::where('waktu_penjualan', $bulanTahun)
                                        ->where('id', '!=', $penjualan->id)
                                        ->first();

        if ($existingPenjualan) {
            session()->flash('message', "Penjualan pada $bulanExist sudah ada!");
            return redirect()->back();
        }

        $penjualan->update([
            'waktu_penjualan' => $bulanTahun,
            'jumlah' => $request->jumlah,
            'updated_by' => $request->updated_by,
        ]);

        toast('Data berhasil diperbarui', 'success');
        return redirect()->route('penjualan.index');
    }

    public function destroy(Penjualan $penjualan)
    {
        $penjualan->delete();

        toast('Data berhasil dihapus', 'success');
        return redirect()->back();
    }

    public function import_excel(Request $request)
    {
        $this->validate($request, [
            'file' => 'required|mimes:csv,xls,xlsx'
        ]);

        $file = $request->file('file');

        // membuat nama file unik
        $nama_file = rand() . $file->getClientOriginalName();

        //temporary file
        $file->move('import_raw_data', $nama_file);

        $import = new PenjualanImport();
        
        // import data
        Excel::import($import, public_path('/import_raw_data/' . $nama_file));

        // Menghapus file dari server
        Storage::delete($file);

        //redirect dengan pesan
        return redirect()->route('penjualan.index')->with([
            'success' => 'Import Data Berhasil -- Berhasil [' . $import->getSuccessCount() . '] -- Gagal [' . $import->getFailedCount() . ']'
        ]);
    }

    // public function import_excel(Request $request)
    // {
    //     $file = $request->file('file');
    //     $extension = $file->getClientOriginalExtension();
    //     // dd($file);
    //     if ($extension == 'xlsx' || $extension == 'xls' || $extension == 'csv') {
    //         $data = Excel::load($file)->get();

    //         $errors = [];
    //         $success = 0;

    //         foreach ($data as $row) {
    //             try {
    //                 $penjualan = new Penjualan();
    //                 $penjualan->tanggal = $row['tanggal'];
    //                 $penjualan->produk = $row['produk'];
    //                 $penjualan->jumlah = $row['jumlah'];
    //                 $penjualan->harga = $row['harga'];
    //                 $penjualan->save();

    //                 $success++;
    //             } catch (\Exception $e) {
    //                 $errors[] = $e->getMessage();
    //             }
    //         }

    //         if ($success > 0) {
    //             return redirect()->back()->with('success', 'Data penjualan berhasil diimport');
    //         } else {
    //             return redirect()->back()->with('error', 'Gagal mengimport data penjualan');
    //         }
    //     } else {
    //         return redirect()->back()->with('error', 'Format file tidak didukung');
    //     }
    // }

}
