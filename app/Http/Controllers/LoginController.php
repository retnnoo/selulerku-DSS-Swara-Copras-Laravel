<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function index(){
        return view('admin.login_admin');
    }

    public function authenticate(Request $request)
    {    
        $credentials = $request->validate([
            'name' => 'required|string|max:16|min:8',
            'password' => 'required|string'
        ]);

         if (Auth::attempt($credentials)) {

            $request->session()->regenerate();

            return redirect()->route('dashboard');
           
        }
        return back()->with('error', 'Username atau Password salah!');
    }

}
