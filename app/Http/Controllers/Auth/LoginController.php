<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash; // ✅ IMPORTANTE ITO!
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        // VALIDATION RULES
        $validator = Validator::make($request->all(), [
            'username' => 'required|string',
            'password' => 'required|string|min:6',
        ], [
            'username.required' => 'Username or email is required!',
            'password.required' => 'Password is required!',
            'password.min' => 'Password must be at least 6 characters!',
        ]);

        // KUNG MAY ERROR SA VALIDATION
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        // Check kung email or username ang ginamit
        $field = filter_var($request->username, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        
        // Check if user exists
        $user = User::where($field, $request->username)->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Account not found.'
            ], 401);
        }

        // CHECK APPROVAL STATUS
        if ($user->approval_status === 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'Your account is pending approval. Please wait for admin confirmation.'
            ], 403);
        }

        if ($user->approval_status === 'rejected') {
            return response()->json([
                'success' => false,
                'message' => 'Your registration was rejected. Reason: ' . ($user->rejection_reason ?? 'No reason provided.')
            ], 403);
        }

        // ✅ MANUAL PASSWORD CHECK KUNG AYAW NG ATTEMPT
        if (!Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid password.'
            ], 401);
        }

        // ✅ MANUAL LOGIN
        Auth::login($user, $request->filled('remember'));
        
        // Check kung active ang account
        if ($user->status !== 'active') {
            Auth::logout();
            return response()->json([
                'success' => false,
                'message' => 'Your account is not active. Please contact administrator.'
            ], 403);
        }

        // Update last login
        $user->update(['last_login_at' => now()]); // ✅ Tama ang column name

        // Log activity
        ActivityLog::create([
            'user_id' => $user->id,
            'action' => 'login',
            'description' => 'User logged in successfully',
            'ip_address' => $request->ip()
        ]);

        // Get redirect URL based on role
        $redirectUrl = $this->getRedirectUrl($user);

        return response()->json([
            'success' => true,
            'message' => 'Login successful! Redirecting...',
            'redirect' => $redirectUrl,
            'user' => [
                'name' => $user->full_name,
                'role' => $user->role
            ]
        ]);
    }

    private function getRedirectUrl($user)
    {
        switch ($user->role) {
            case 'admin':
                return route('admin.dashboard');
            case 'supervisor':
                return route('supervisor.dashboard');
            case 'cashier':
                return route('cashier.pos.index');
            default:
                return '/';
        }
    }

    public function logout(Request $request)
    {
        if (Auth::check()) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'action' => 'logout',
                'description' => 'User logged out',
                'ip_address' => $request->ip()
            ]);
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/')->with('success', 'You have been logged out successfully.');
    }
}
