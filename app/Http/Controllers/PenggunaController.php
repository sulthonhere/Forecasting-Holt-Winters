<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Auth;

class PenggunaController extends Controller
{
    public function index()
    {
        $pengguna = User::whereNot('role', 1)->get();
        confirmDelete('Hapus Data', "Apakah anda yakin?!");
        
        return view('admin.pengguna', compact('pengguna'));
    }

    public function store(Request $request)
    {
        $existingEmail = User::where('email', $request->email)->first();
        if ($existingEmail) {
                Alert::error('Gagal', 'Email sudah digunakan!');
                return redirect()->back()->withInput();
        }

        User::create($request->all());

        toast('Data berhasil ditambahkan', 'success');
        return redirect()->route('pengguna.index');
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        if ($user->id == Auth::user()->id) {
            Alert::error('Gagal', 'Ubah data pada menu profil');
            return redirect()->back();
        }

        $existingEmail = User::where('email', $request->email)
                        ->where('id', '!=', $user->id)
                        ->first();

        if ($existingEmail) {
            Alert::error('Gagal', 'Email pengganti sudah digunakan!');
            return redirect()->back()->withInput();
        }

        $user->update($request->all());

        toast('Data berhasil diperbarui', 'success');
        return redirect()->route('pengguna.index');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);

        if ($user->id == Auth::user()->id) {
            Alert::error('Gagal', 'Tidak dapat mengghapus data');
            return redirect()->back();
        }
        $user->delete();

        toast('Data berhasil dihapus', 'success');
        return redirect()->back();
    }
}
