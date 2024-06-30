<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
  public function register(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'username' => 'required|string|max:255',
      'email' => 'required|string|email|max:255|unique:users',
      'password' => 'required|string|min:6|confirmed',
      'phone' => 'required|string|max:15',
      'address' => 'required|string|max:255',
    ]);

    if ($validator->fails()) {
      return response()->json($validator->errors(), 422);
    }

    $user = User::create([
      'username' => $request->username,
      'email' => $request->email,
      'password' => Hash::make($request->password),
      'phone' => $request->phone,
      'address' => $request->address,
      'role' => 'client',
    ]);

    $token = JWTAuth::fromUser($user);

    return response()->json(compact('user', 'token'), 201);
  }

  public function login(Request $request)
  {
    $credentials = $request->only('email', 'password');

    try {
      if (!$token = JWTAuth::attempt($credentials)) {
        return response()->json(['error' => 'Invalid credentials'], 401);
      }
    } catch (JWTException $e) {
      return response()->json(['error' => 'Could not create token'], 500);
    }

    return response()->json(compact('token'));
  }

  public function logout()
  {
    try {
      JWTAuth::invalidate(JWTAuth::getToken());
      return response()->json(['message' => 'Successfully logged out']);
    } catch (JWTException $e) {
      return response()->json(['error' => 'Failed to logout, please try again.'], 500);
    }
  }

  public function getUser()
  {
    try {
      $user = JWTAuth::parseToken()->authenticate();

      if (!$user) {
        return response()->json(['error' => 'Unauthorized'], 401);
      }

      return response()->json(compact('user'));
    } catch (\Throwable $th) {
      return response()->json(['error' => 'Unauthorized'], 401);
    }
  }
}
