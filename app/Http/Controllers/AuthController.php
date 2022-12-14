<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register (Request $request) {
        $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|string|unique:users,email',
            'password' => 'required|string'
        ]);

        $user = new User([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => 'user'
        ]);

        $user->save();

        $token = $user->createToken('authToken')->plainTextToken;

        return response()->json([
            'succes' => true,
            'message' => 'Register berhasil',
            'data' => $user,
            'akses_token' => $token
        ], 201);
    }

    public function login (Request $request) {
        $request->validate([
            'email' => 'required|string',
            'password' => 'required|string'
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'succes' => false,
                'message' => 'Login gagal, email atau password salah',
                'data' => ''
            ], 401);
        }

        $token = $user->createToken('authToken')->plainTextToken;

        return response()->json([
            'succes' => true,
            'message' => 'Login berhasil',
            'data' => $user,
            'akses_token' => $token
        ], 201);
    }

    public function logout (Request $request) {
        auth()->user()->currentAccessToken()->delete();

        return response()->json([
            'succes' => true,
            'message' => 'Logout berhasil',
            'data' => ''
        ], 200);
    }
}



    //     $fields = $request->validate([
    //         'name' => 'required|string|max:100',
    //         'email' => 'required|string|unique:users,email',
    //         'password' => 'required|string|confirmed|min:6'
    //     ]);
    
    //     $user = User::create([
    //         'name' => $fields['name'],
    //         'email' => $fields['email'],
    //         'password' => bcrypt($fields['password'])
    //     ]);
    
    //     $token = $user->createToken('mytoken')->plainTextToken;
    
    //     $response = [
    //         'user' => $user,
    //         'token' => $token
    //     ];
    //     return response($response, 201);
    // }
    
    // public function login (Request $request) {
    //     $fields = $request->validate([
    //         'email' => 'required|string',
    //         'password' => 'required|string'
    //     ]);
    
    //     // check email
    //     $user = User::where('email', $fields['email'])->first();
    
    //     // check password
    //     if (!$user || !Hash::check($fields['password'], $user->password)) {
    //         return response([
    //             'message' => 'Unauthorized'
    //         ], 401);
    //     }
    
    //     $token = $user->createToken('mytoken')->plainTextToken;
    
    //     $response = [
    //         'user' => $user,
    //         'token' => $token,
    //         'message' => 'success login'
    //     ];
    
    //     return response($response, 201);
    // }
    
    // public function logout (Request $request) {
    //     $request->user()->currentAccessToken()->delete();
    //     return [
    //         'message' => 'Logged out'
    //     ];
    // }
