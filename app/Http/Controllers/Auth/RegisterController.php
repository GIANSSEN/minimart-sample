<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        // VALIDATION RULES
        $validator = Validator::make($request->all(), [
            'username' => 'required|string|min:4|unique:users|regex:/^[a-zA-Z0-9_]+$/',
            'full_name' => 'required|string|max:100|regex:/^[a-zA-Z\s]+$/',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8|confirmed|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)/',
            'phone' => 'nullable|string|max:20|regex:/^[0-9+\-\s]+$/',
        ], [
            // Custom error messages
            'username.required' => 'Username is required!',
            'username.min' => 'Username must be at least 4 characters!',
            'username.unique' => 'This username is already taken!',
            'username.regex' => 'Username can only contain letters, numbers, and underscores!',
            
            'full_name.required' => 'Full name is required!',
            'full_name.regex' => 'Full name can only contain letters and spaces!',
            
            'email.required' => 'Email address is required!',
            'email.email' => 'Please enter a valid email address!',
            'email.unique' => 'This email is already registered!',
            
            'password.required' => 'Password is required!',
            'password.min' => 'Password must be at least 8 characters!',
            'password.confirmed' => 'Password confirmation does not match!',
            'password.regex' => 'Password must contain at least one uppercase letter, one lowercase letter, and one number!',
            
            'phone.regex' => 'Please enter a valid phone number!',
        ]);

        // KUNG MAY ERROR SA VALIDATION
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        // CREATE USER
        $user = User::create([
            'employee_id' => 'EMP' . rand(1000, 9999),
            'username' => $request->username,
            'full_name' => $request->full_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'role' => 'cashier',
            'status' => 'active',
        ]);

        // LOG ACTIVITY
        ActivityLog::create([
            'user_id' => $user->id,
            'action' => 'register',
            'description' => 'New user registered',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);

        // AUTO LOGIN AFTER REGISTRATION
        Auth::login($user);

        return response()->json([
            'success' => true,
            'message' => 'Registration successful! Welcome to Minimart POS.',
            'redirect' => route('cashier.dashboard'),
            'user' => [
                'name' => $user->full_name,
                'role' => $user->role
            ]
        ]);
    }
}
