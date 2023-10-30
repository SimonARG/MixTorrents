<?php

namespace App\Http\Controllers;

use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller {
    public function login() {
        session(['link' => url()->previous()]);
        
        return view('login');
    }

    public function authenticate(Request $request) {
        $formFields = $request->validate([
            'name' => ['required'],
            'password' => ['required']
        ]);

        if (Auth::attempt($formFields)) {
            $request->session()->regenerate();

            return redirect(session('link'))->with('message', 'You are now logged in');
        }

        return back()->withErrors(['name' => 'Invalid credentials'])->onlyInput('name');
    }

    public function logout(Request $request) {
        auth()->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return back()->with('message', 'You are logged out!');
    }

    public function profile() {
        if (Auth::check()) {
            $date = new DateTime(auth()->user()->created_at);
            $strdate = $date->format('Y/m/d H:i');
            return view('profile', [
                'strdate' => $strdate
            ]);
        } else {
            return back()->with('message', 'You need to be logged in to view your profile');
        }
    }
}
