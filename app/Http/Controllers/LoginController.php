<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function index()
    {
        return view('pages.authentication.login', [
            'title' => 'Login'
        ]);
    }

    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'username' => ['required'],
            'password' => ['required']
        ]);

        if (Auth::attempt($credentials)) {

            activity()
                ->causedBy(Auth::user())
                ->log('<strong>login</strong> pada sistem');

            $request->session()->regenerate();
            return redirect()->intended('dashboard');
        }

        return back()->with('loginError', 'Login failed');
    }

    public function logout(Request $request)
    {
        activity()
            ->causedBy(Auth::user())
            ->log('<strong>logout</strong> dari sistem');

        Auth::logout();

        request()->session()->invalidate();

        request()->session()->regenerateToken();

        return redirect()->route('login');
    }
}
