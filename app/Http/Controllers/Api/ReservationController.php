<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Car;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ReservationController extends Controller
{
  public function store(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'car_id' => 'required|exists:cars,id',
      'user_id' => 'required|exists:users,id',
    ]);

    if ($validator->fails()) {
      return response()->json($validator->errors(), 422);
    }

    $car = Car::find($request->car_id);

    if ($car->status !== 'available') {
      return response()->json(['error' => 'Car is not available for reservation'], 422);
    }

    $reservation = Reservation::create([
      'car_id' => $request->car_id,
      'user_id' => $request->user_id,
    ]);

    $car->status = 'reserved';
    $car->reserved_by = $request->user_id;
    $car->save();

    return response()->json(compact('reservation'), 201);
  }

  public function destroy($id)
  {
    $reservation = Reservation::find($id);

    if (!$reservation) {
      return response()->json(['error' => 'Reservation not found'], 404);
    }

    $reservation->delete();

    // Atualiza o estado do carro para 'disponível' caso não hajam reservas ativas.
    $car = $reservation->car;
    if ($car->reservations()->count() === 0) {
      $car->status = 'available';
      $car->reserved_by = null;
      $car->save();
    }

    return response()->json(['message' => 'Reservation cancelled successfully']);
  }
}
