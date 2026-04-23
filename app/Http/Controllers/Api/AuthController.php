<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Role;

class AuthController extends Controller
{
   public function register(Request $request)
{
    // 1. validate الأول
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users',
        'password' => 'required|min:6',
        'role' => 'required|string'
    ]);

    // 2. هات الـ role من الداتابيز
    $role = Role::where('name', $request->role)->first();

    if (!$role) {
        return response()->json([
            'message' => 'Invalid role'
        ], 400);
    }

    // 3. إنشاء المستخدم
    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => bcrypt($request->password),
        'role_id' => $role->id
    ]);

    // 4. token
    $token = $user->createToken('api-token')->plainTextToken;

    return response()->json([
        'message' => 'User registered successfully',
        'user' => $user,
        'token' => $token
    ]);
}
     

    public function login(Request $request){
        $request->validate([
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6'
        ]);

        if(!auth::attempt($request->only('email','password'))){

            return response()->json([ 'massage'=>'invaid'],401);
        }

        $user=Auth::User();
        $token=$user->createToken('api-token')->plainTextToken;

        return response()->json([
            'message' => 'Login successful',
            'user' => $user,
            'token' => $token
        ]);


    }

    public function logout(Request $request){{
        $request->user()->CurrentAccessToken()->delete();
        return response()->json([
            'massage'=>'user Logout succesfully'
        ]);
    }}


}
