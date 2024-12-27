<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

class LoginController extends Controller
{
    public function login() {
        if (Auth::check()) {
            return redirect('dashboard');
        } else {
            return view('global.login');
        }
    }
    function actionLogin(Request $request) {
        if(Auth::attempt($request->only('email', 'password'))) {
            Alert::toast('Anda berhasil masuk!', 'success');
            return redirect('dashboard');
        }
        Alert::error('Gagal', 'Kredensial yang Anda masukkan salah!');
        return redirect()->back();
    }

    function actionLogout() {
        Auth::logout();

        Alert::toast('Anda berhasil keluar!', 'success');
        return redirect('/');
    }
}
