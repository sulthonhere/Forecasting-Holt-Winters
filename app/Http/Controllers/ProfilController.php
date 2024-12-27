<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use RealRashid\SweetAlert\Facades\Alert;

class ProfilController extends Controller
{
    public function index() {
        return view('global.profil');
    }

    public function update(Request $request, $id) {
        $user = User::findOrFail($id);
        
        if ($request->has('current_password') && $request->has('new_password') && $request->has('confirm_new_password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                Alert::error('Gagal Tersimpan!', 'Password lama salah');
                return redirect()->back();
            }
            else {
                if ($request->new_password != $request->confirm_new_password) {
                    Alert::error('Gagal Tersimpan!', 'Password baru tidak sama');
                    return redirect()->back();
                }
                else {
                    $user->password = Hash::make($request->new_password);
                    $user->save();
    
                    Alert::success('Berhasil Tersimpan!', 'Password berhasil diperbarui');
                    return redirect()->back();
                }
            }
        }  

        $user->update($request->all());

        Alert::success('Berhasil', 'Data pribadi berhasil diubah!');
        return redirect()->back();
    }

}
