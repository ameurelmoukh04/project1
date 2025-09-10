<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
public function register(Request $request)
{
    $request->validate([
        'full_name' => 'required|string|min:3',
        'email' => 'required|string|email|unique:users',
        'phone_number' => 'nullable|string|min:10|max:15|unique:users',
        'address' => 'nullable|string|max:255',
        'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        'password' => 'required|string|min:4',
    ]);

    $path = null;

    // Save user first (without profile picture)
    $user = new User();
    $user->full_name = $request->full_name;
    $user->email = $request->email;
    $user->password = Hash::make($request->password);
    $user->phone_number = $request->phone_number;
    $user->address = $request->address;
    $user->save();

    // Handle profile picture upload
    if ($request->hasFile('profile_picture')) {
        $path = $request->file('profile_picture')
                        ->store("profiles/users/{$user->id}/profile", 'public');

        // Update user with picture path
        $user->profile_picture = $path;
        $user->save();
    }

    return response()->json([
        'message' => 'Registered successfully',
        'user' => $user,
    ], 201);
}


    public function login(Request $request){
        
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (!Auth::attempt($credentials)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Login not valid'
            ], 401);
        }

        $user = User::where('email', $request->email)->firstOrFail();
        if (! $token = auth('api')->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        return response()->json([
            'status' => 'success',
            'user' => $user,
            'Authorization' => [
                'token' => $token,
                'type' => 'Bearer',
            ]
        ], 200);
    }

 public function logout(Request $request)
    {
        try {
            JWTAuth::invalidate(JWTAuth::getToken());

            return response()->json([
                'success' => true,
                'message' => 'Successfully logged out'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to logout, token might be invalid'
            ], 400);
        }
    }

}
