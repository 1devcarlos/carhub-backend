<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Car;
use App\Models\Rental;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Validator;

class RentalController extends Controller
{
  public function store(Request $request)
  {
    $authUser = Auth::guard('api')->user();
    $userId = $authUser->id;

    // Validando a data de início para não ser menor que a data atual
    $validator = Validator::make($request->all(), [
      'car_id' => 'required|exists:cars,id',
      'start_date' => 'required|date|after_or_equal:today',
      'end_date' => 'required|date|after_or_equal:start_date',
    ], [
      'start_date.after_or_equal' => 'The start date must be today or later.',
    ]);

    if ($validator->fails()) {
      return response()->json($validator->errors(), 422);
    }

    $car = Car::find($request->car_id);

    if (!$car) {
      return response()->json(['error' => 'Car not found'], 404);
    }

    if ($car->status !== 'available' && $car->reserved_by !== $userId) {
      return response()->json(['error' => 'Car is not available for rental'], 422);
    }

    // Verificando se o carro está disponível ou reservado pelo mesmo usuário
    if ($car->status !== 'available' && $car->reserved_by !== $userId) {
      return response()->json(['error' => 'Car is not available for rental'], 422);
    }

    // Cálculo da quantidade de dias de aluguel
    $startDate = new DateTime($request->start_date);
    $endDate = new DateTime($request->end_date);

    // Calcula a diferença em dias
    $interval = $endDate->diff($startDate);
    $daysDifference = $interval->days;

    // Cálculo do preço total
    $totalPrice = $daysDifference * $car->daily_price;

    $rental = Rental::create([
      'car_id' => $request->car_id,
      'user_id' => $userId,
      'start_date' => $request->start_date,
      'end_date' => $request->end_date,
      'total_price' => $totalPrice,
    ]);

    $car->status = 'rented';
    $car->reserved_by = $userId;
    $car->save();

    return response()->json(compact('rental'), 201);
  }

  public function destroy($id)
  {
    $rental = Rental::find($id);

    if (!$rental) {
      return response()->json(['error' => 'Rental not found'], 404);
    }

    $car = $rental->car;
    $rental->delete();

    // Checa se existem outras locações ativas para o carro
    $activeRental = Rental::where('car_id', $car->id)->first();

    if (!$activeRental) {
      $car->status = 'available';
      $car->reserved_by = null;
      $car->save();
    }

    return response()->json(['message' => 'Rental cancelled successfully']);
  }
}
