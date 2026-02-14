<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\LoginDetail;

class AccountSecurityController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        return view('account.security', compact('user'));
    }

    /**
     * Change password using the existing SHA256 + salt system.
     */
    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password'     => 'required|string|min:8|confirmed',
        ]);

        $user = Auth::user();
        $loginDetail = LoginDetail::where('user_id', $user->user_id)->firstOrFail();

        // Verify current password
        $currentHash = hash('sha256', $request->current_password . $loginDetail->password_salt);

        if ($currentHash !== $loginDetail->password_hash) {
            return back()->withErrors([
                'current_password' => 'Current password is incorrect.',
            ]);
        }

        // Generate new salt and hash
        $newSalt = bin2hex(random_bytes(16));
        $newHash = hash('sha256', $request->new_password . $newSalt);

        $loginDetail->update([
            'password_hash' => $newHash,
            'password_salt' => $newSalt,
        ]);

        return redirect()->route('account.security')
            ->with('success', 'Password changed successfully.');
    }

    /**
     * Change email address (requires password confirmation).
     */
    public function changeEmail(Request $request)
    {
        $request->validate([
            'email'            => 'required|email|max:255',
            'current_password' => 'required',
        ]);

        $user = Auth::user();
        $loginDetail = LoginDetail::where('user_id', $user->user_id)->firstOrFail();

        // Verify password
        $currentHash = hash('sha256', $request->current_password . $loginDetail->password_salt);
        if ($currentHash !== $loginDetail->password_hash) {
            return back()->withErrors([
                'current_password' => 'Password is incorrect.',
            ]);
        }

        // Check email uniqueness
        $exists = LoginDetail::where('email_address', $request->email)
            ->where('user_id', '!=', $user->user_id)
            ->exists();

        if ($exists) {
            return back()->withErrors(['email' => 'This email is already in use.']);
        }

        $loginDetail->update(['email_address' => $request->email]);
        $user->update(['email_verified' => false]);

        return redirect()->route('account.security')
            ->with('success', 'Email updated successfully.');
    }

    /**
     * Deactivate account (soft delete — preserves order history).
     */
    public function deleteAccount(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'confirm_delete'   => 'required|in:DELETE',
        ]);

        $user = Auth::user();
        $loginDetail = LoginDetail::where('user_id', $user->user_id)->firstOrFail();

        $currentHash = hash('sha256', $request->current_password . $loginDetail->password_salt);
        if ($currentHash !== $loginDetail->password_hash) {
            return back()->withErrors(['current_password' => 'Password is incorrect.']);
        }

        $user->update(['is_active' => false]);

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')
            ->with('success', 'Your account has been deactivated.');
    }
}
