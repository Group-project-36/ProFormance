<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\LoginDetail;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);
        $loginDetail = LoginDetail::where('email_address', $credentials['email'])->first();

        if (!$loginDetail) {
            return back()->withErrors([
                'email' => 'Invalid email or password.',
            ])->onlyInput('email');
        }
        $passwordHash = hash('sha256', $credentials['password'] . $loginDetail->password_salt);
        
        if ($passwordHash !== $loginDetail->password_hash) {
            return back()->withErrors([
                'email' => 'Invalid email or password.',
            ])->onlyInput('email');
        }
        $user = User::find($loginDetail->user_id);

        if (!$user) {
            return back()->withErrors([
                'email' => 'User not found.',
            ])->onlyInput('email');
        }
        if (!$user->is_active) {
            return back()->withErrors([
                'email' => 'Your account is inactive.',
            ])->onlyInput('email');
        }
        Auth::login($user);

        $request->session()->regenerate();

        return redirect()->intended('/')
            ->with('success', 'Welcome back, ' . $user->first_name . '!');
    }
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'You have been logged out.');
    }
}