<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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
        \Log::info('Registration attempt started', ['email' => $request->email]);
        
        $validator = Validator::make($request->all(), [
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:login_details,email_address'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'phone_number' => ['nullable', 'string', 'regex:/^[0-9]{10,15}$/'],
        ], [
            'email.unique' => 'This email is already registered.',
            'email.email' => 'Please enter a valid email address.',
            'phone_number.regex' => 'Phone number must be 10-15 digits.',
        ]);

        if ($validator->fails()) {
            \Log::warning('Registration validation failed', ['errors' => $validator->errors()->all()]);
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        \Log::info('Validation passed, starting transaction');
        
        \DB::beginTransaction();

        try {
            $phoneNumber = $request->phone_number ? (int) preg_replace('/[^0-9]/', '', $request->phone_number) : 0;
            
            $userData = [
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'company_name' => $request->company_name ?? null,
                'country_phone_code' => $request->country_phone_code ?? 44,
                'phone_number' => $phoneNumber,
                'user_role' => 'customer',
                'is_active' => 1,
                'email_verified' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ];
            
            \Log::info('Creating user with data:', $userData);
            
            $userId = \DB::table('users')->insertGetId($userData);
            
            \Log::info('User created with ID: ' . $userId);
            
            if (!$userId) {
                throw new \Exception('Failed to get user ID after insertion');
            }
            $salt = bin2hex(random_bytes(16));
            
            $passwordHash = hash('sha256', $request->password . $salt);
            
            \Log::info('Password hashed', ['salt' => $salt, 'hash' => substr($passwordHash, 0, 20) . '...']);
            $loginData = [
                'user_id' => $userId,
                'email_address' => $request->email,
                'password_hash' => $passwordHash,
                'password_salt' => $salt,
            ];
            
            \Log::info('Creating login details:', ['email' => $request->email, 'user_id' => $userId]);
            
            \DB::table('login_details')->insert($loginData);
            
            \Log::info('Login details created successfully');

            \DB::commit();
            
            \Log::info('Transaction committed successfully');

            $user = User::find($userId);
            
            if (!$user) {
                throw new \Exception('Could not find user after creation');
            }
            Auth::login($user);
            
            \Log::info('User logged in successfully', ['user_id' => $userId, 'email' => $request->email]);

            return redirect('/')
                ->with('success', 'Welcome to ProFitness! Your account has been created.');

        } catch (\Exception $e) {
            \DB::rollBack();
            
            \Log::error('Registration failed: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'request' => $request->except('password', 'password_confirmation')
            ]);
            return redirect()->back()
                ->with('error', 'Registration Error: ' . $e->getMessage())
                ->withInput();
        }
    }
}