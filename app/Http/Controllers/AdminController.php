<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class AdminController extends Controller
{
    public function loginForm()
    {
        return view('admin.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('/admin/dashboard');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function dashboard()
    {
        $user = Auth::user();
        
        // Available API routes to display in tabs
        $apiRoutes = [
            ['name' => 'Tasks', 'route' => 'tasks', 'description' => 'Get all user tasks'],
            ['name' => 'User Info', 'route' => 'user', 'description' => 'Get logged in user info'],
            ['name' => 'Task Stats', 'route' => 'stats', 'description' => 'Get task statistics'],
        ];

        return view('admin.dashboard', compact('user', 'apiRoutes'));
    }

    public function getApiData($route)
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . Auth::user()->createToken('api')->plainTextToken,
            ])->get(url("/api/{$route}"));

            if ($response->successful()) {
                return response()->json($response->json());
            }

            return response()->json(['error' => 'Failed to fetch data'], $response->status());
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/admin');
    }
}
