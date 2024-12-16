<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller {
    public function showLoginForm() {
        return view('admin.login');
    }

    public function login(Request $request) {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials) && Auth::user()->is_admin) {
            $request->session()->regenerate();
            return redirect()->route('categories.index');
        }

        return back()->withErrors([
            'email' => 'Invalid credentials or not an admin.',
        ])->onlyInput('email');
    }
    public function makeLogout()
	{
		auth()->guard('web')->logout();
	}
    public function logout()
	{
		$this->makeLogout();
		return redirect(route('login'))->with('success', 'Logout successfully!');
	}
}
