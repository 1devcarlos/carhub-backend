<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
  public function show()
  {
    $authUser = Auth::guard('api')->user();
    $userId = $authUser->id;

    $user = User::find($userId);

    if (!$user) {
      return response()->json(['error' => 'User not found'], 404);
    }

    return response()->json(compact('user'));
  }

  public function update(Request $request)
  {
    $authUser = Auth::guard('api')->user();
    $userId = $authUser->id;

    $user = User::find($userId);

    $validator = Validator::make($request->all(), [
      'password' => 'sometimes|string|min:6|confirmed',
      'email' => 'sometimes|string|email|max:255|unique:users,email,' . $user->id,
      'phone' => 'sometimes|string|max:15',
      'address' => 'sometimes|string|max:255',
    ]);

    if ($validator->fails()) {
      return response()->json($validator->errors(), 422);
    }

    if ($request->filled('password')) {
      $oldPassword = $request->input('old_password');

      if (!$oldPassword || !Hash::check($oldPassword, $user->password)) {
        return response()->json(['error' => 'Old password is incorrect'], 400);
      }

      $user->password = Hash::make($request->input('password'));
    }

    if ($request->filled('username')) {
      $user->username = $request->username;
    }

    if ($request->filled('email')) {
      $user->email = $request->email;
    }

    if ($request->filled('phone')) {
      $user->phone = $request->phone;
    }

    if ($request->filled('address')) {
      $user->address = $request->address;
    }

    try {
      $user->save();
      $message = 'Successfully updated user profile!';
      return response()->json(compact('message', 'user'));
    } catch (\Exception $e) {
      return response()->json(['error' => 'Failed to update user profile'], 500);
    }
  }

  public function uploadPhoto(Request $request)
  {
    $authUser = Auth::guard('api')->user();
    $userId = $authUser->id;

    $user = User::find($userId);

    $validator = Validator::make($request->all(), [
      'photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // Max 2MB
    ]);

    if ($validator->fails()) {
      return response()->json($validator->errors(), 422);
    }

    if ($request->hasFile('photo')) {
      $file = $request->file('photo');

      if (!$file->isValid()) {
        return response()->json(['error' => 'Invalid file upload'], 400);
      }

      try {
        $fileName = uniqid() . '.' . $file->getClientOriginalExtension();

        $filePath = $file->storeAs('photos', $fileName, 'public');

        $fullPath = storage_path('app/public/' . $filePath);

        $binaryPhoto = file_get_contents($fullPath);

        Storage::delete($filePath);

        $user->photo = base64_encode($binaryPhoto);
        $user->save();

        $message = 'Photo uploaded successfully!';
        return response()->json(compact('message', 'user'));
      } catch (\Exception $e) {
        return response()->json(['error' => 'Failed to upload photo', 'details' => $e->getMessage()], 500);
      }
    }

    return response()->json(['error' => 'No file uploaded'], 400);
  }
}
