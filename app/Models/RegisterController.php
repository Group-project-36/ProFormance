<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\LoginDetail;

class RegisterController extends Controller
{
    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'company_name' => ['nullable', 'string', 'max:255'],
            'country_phone_code' => ['required', 'integer'],
            'phone_number' => ['required', 'string', 'regex:/^[0-9]{10,15}$/'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:login_details,email_address'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'terms' => ['accepted'],
        ], [
            'terms.accepted' => 'You must accept the Terms & Conditions.',
            'email.unique' => 'This email is already registered.',
            'phone_number.regex' => 'Phone number must be 10-15 digits.',
            'email.email' => 'Please enter a valid email address.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        \DB::beginTransaction();

        try {
            $user = User::create([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'company_name' => $request->company_name,
                'country_phone_code' => $request->country_phone_code,
                'phone_number' => $request->phone_number,
                'user_role' => 'customer',
                'is_active' => true,
                'email_verified' => false,
            ]);

            $salt = bin2hex(random_bytes(16));
            
            $passwordHash = hash('sha256', $request->password . $salt);
            
            LoginDetail::create([
                'user_id' => $user->user_id,
                'email_address' => $request->email,
                'password_hash' => $passwordHash,
                'password_salt' => $salt,
            ]);
            \DB::commit();
            Auth::login($user);

            return redirect('/')
                ->with('success', 'Welcome to ProFitness! Your account has been created successfully.');

        } catch (\Exception $e) {
            \DB::rollBack();
            
            return redirect()->back()
                ->with('error', 'Registration failed. Please try again.')
                ->withInput();
        }
    }
}