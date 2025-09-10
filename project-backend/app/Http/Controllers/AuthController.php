<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
//use App\Notifications\WelcomeUser;

class AuthController extends Controller
{
    public function register(Request $request){
        $request->validate( [
            'full_name' => 'required|string|min:3',
            'email' => 'required|string|email|unique:users',
            'phone_number' => 'nullable|string|min:10|max:15|unique:users',
            'address' => 'nullable|string|max:255',
            'profile_picture' => 'nullable|url',
            'password' => 'required|string|min:4',
        ]);

        $user = new User();
        $user->full_name = $request->full_name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->phone_number = $request->phone_number;
        $user->address = $request->address;
        $user->profile_picture = $request->profile_picture;
        
        
        $user->save();

        // Send welcome email
        //$user->notify(new WelcomeUser());
         
        return response()->json([
            'message' => 'registered Succesfully',
            'user' => $user,
            // 'Authorization'=> [
            //     'type'=> 'Bearer',
            // ]
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

    // public function logout(Request $request){
    //     $request->user()->currentAccessToken()->delete();
    //     return response()->json([
    //         'status'=>'success',
    //         'message'=>'successfully logged out'
    //     ],200);
    // }

}
