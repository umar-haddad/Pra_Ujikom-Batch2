<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class LoginController extends Controller
{
    public function login()
    {
        return view('login');
    }

    public function actionLogin(Request $request)
    {

        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);


        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            Alert::success('Login Success', 'alamak dah login');
            $request->session()->regenerate();
            return redirect()->intended('dashboard');
        } else {
            Alert::error('login Failed', 'you password & email is Wrong ');
            return back()->onlyInput('email', 'password');
        }
        //  return back()->withErrors([
        //         'email' => 'Invalid Credentials',
        //     ])->onlyInput('email');
    }

    static function logout()
    {
        Auth::logout();
        return redirect('/');
    }

    static function laundry()
    {
        Auth::laundry();
        return redirect('/laundry');
    }
}
