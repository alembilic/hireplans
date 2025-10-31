<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use App\Models\User;
use Illuminate\Validation\Rules\Password as PasswordRule;

class PasswordSetupController extends Controller
{
    /**
     * Show the password setup form.
     */
    public function show(Request $request, string $token)
    {
        $email = $request->query('email');
        
        if (!$email) {
            return redirect()->route('login')->withErrors(['email' => 'Invalid password setup link.']);
        }
        
        // Check if the token is valid
        $user = User::where('email', $email)->first();
        
        if (!$user) {
            return redirect()->route('login')->withErrors(['email' => 'User not found.']);
        }
        
        // Check if token exists and is valid
        $tokenRecord = DB::table('password_reset_tokens')
            ->where('email', $email)
            ->where('token', hash('sha256', $token))
            ->first();
            
        if (!$tokenRecord) {
            return redirect()->route('login')->withErrors(['email' => 'This password setup link is invalid or has expired.']);
        }
        
        // Check if token is not expired (60 minutes)
        if (now()->diffInMinutes($tokenRecord->created_at) > 60) {
            return redirect()->route('login')->withErrors(['email' => 'This password setup link has expired.']);
        }
        
        return view('auth.password-setup', compact('token', 'email', 'user'));
    }
    
    /**
     * Handle the password setup form submission.
     */
    public function store(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => ['required', 'confirmed', PasswordRule::defaults()],
        ]);

        // Check if the token is valid
        $user = User::where('email', $request->email)->first();
        
        if (!$user) {
            return back()->withErrors(['email' => 'User not found.']);
        }
        
        // Check if token exists and is valid
        $tokenRecord = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->where('token', hash('sha256', $request->token))
            ->first();
            
        if (!$tokenRecord) {
            return back()->withErrors(['email' => 'This password setup link is invalid or has expired.']);
        }
        
        // Check if token is not expired (60 minutes)
        if (now()->diffInMinutes($tokenRecord->created_at) > 60) {
            return back()->withErrors(['email' => 'This password setup link has expired.']);
        }
        
        // Update user password
        $user->forceFill([
            'password' => Hash::make($request->password),
            'email_verified_at' => now(), // Mark email as verified
        ])->save();
        
        // Delete the used token
        DB::table('password_reset_tokens')->where('email', $request->email)->delete();
        
        return redirect()->route('login')->with('status', 'Your password has been set successfully! You can now log in.');
    }
}