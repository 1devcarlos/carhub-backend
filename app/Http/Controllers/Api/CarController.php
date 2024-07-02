<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Car;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class CarController extends Controller
{

  public function index()
  {
    $cars = Car::all();

    return response()->json(compact('cars'));
  }
  public function store(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'model' => 'required|string|max:255',
      'brand' => 'required|string|max:255',
      'year' => 'required|integer|min:1900|max:' . date('Y'),
      'color' => 'required|string|max:255',
      'daily_price' => 'required|numeric',
      'status' => 'required|string|max:50',
      'company_id' => 'required|exists:companies,id'
    ]);

    if ($validator->fails()) {
      return response()->json($validator->errors(), 422);
    }

    $car = Car::create($request->all());

    return response()->json(compact('car'), 201);
  }

  public function update(Request $request, $id)
  {
    $car = Car::find($id);

    if (!$car) {
      return response()->json(['error' => 'Car not found'], 404);
    }

    $validator = Validator::make($request->all(), [
      'model' => 'sometimes|string|max:255',
      'brand' => 'sometimes|string|max:255',
      'year' => 'sometimes|integer|min:1900|max:' . date('Y'),
      'color' => 'sometimes|string|max:255',
      'daily_price' => 'sometimes|numeric',
      'status' => 'sometimes|string|max:50|in:in revision,rented,reserved,available',
      'company_id' => 'sometimes|exists:companies,id'
    ]);

    if ($validator->fails()) {
      return response()->json($validator->errors(), 422);
    }

    $car->fill($request->all());
    $car->save();

    return response()->json(compact('car'));
  }

  public function uploadPhoto(Request $request, $id)
  {
    $car = Car::find($id);

    if (!$car) {
      return response()->json(['error' => 'Car not found'], 404);
    }

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

        $car->photo = base64_encode($binaryPhoto);
        $car->save();

        $message = "Photo uploaded successfully!";
        return response()->json(compact('message', 'car'));
      } catch (\Exception $e) {
        return response()->json(['error' => 'Failed to upload photo', 'details' => $e->getMessage()], 500);
      }
    }
  }

  public function destroy($id)
  {
    $car = Car::find($id);
    if (!$car) {
      return response()->json(['error' => 'Car not found'], 404);
    }

    $car->delete();

    return response()->json(['message' => 'Car deleted successfully']);
  }
}
