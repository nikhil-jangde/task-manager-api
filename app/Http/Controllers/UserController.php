<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        return User::all(['id', 'name', 'email']);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6'
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => \Illuminate\Support\Facades\Hash::make($data['password'])
        ]);

        return response()->json($user, 201);
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name' => 'string|max:255',
            'email' => 'email|unique:users,email,' . $user->id,
            'password' => 'nullable|min:6'
        ]);

        if (isset($data['password'])) {
            $data['password'] = \Illuminate\Support\Facades\Hash::make($data['password']);
        }

        $user->update($data);
        return response()->json($user);
    }

    public function destroy(User $user)
    {
        // Simple delete logic for members list
        $user->delete();
        return response()->json(['message' => 'User deleted successfully']);
    }
}
