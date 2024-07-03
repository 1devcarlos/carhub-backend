<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Car;
use App\Models\Revision;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CarManagementController extends Controller
{
  public function index(Request $request)
  {
    // Se o parâmetro "status" estiver presente na requisição, filtra os carros por ele.
    if ($request->has('status')) {
      $status = $request->query('status');

      if ($status === 'in revision') {
        $cars = Car::where('status', 'in revision')->with('revisions')->get();
      } elseif ($status === 'rented') {
        $cars = Car::where('status', 'rented')->with('rental')->get();
      } elseif ($status === 'reserved') {
        $cars = Car::where('status', 'reserved')->with('reservation')->get();
      } elseif ($status === 'available') {
        $cars = Car::where('status', 'available')->get();
      } else {
        return response()->json(['error' => 'Invalid status parameter'], 400);
      }
    } else {
      // Retorna todos os carros se nenhum parâmetro de status for fornecido
      $cars = Car::all();
    }

    return response()->json(compact('cars'));
  }

  public function updateStatus(Request $request, $id)
  {
    $car = Car::find($id);

    if (!$car) {
      return response()->json(['error' => 'Car not found'], 404);
    }

    $validator = Validator::make($request->all(), [
      'status' => 'required|string|in:in revision,rented,reserved,available',
      'description' => 'required_if:status,in revision|string|max:255',
      'end_date' => 'nullable|date|after_or_equal:start_date',
    ]);

    if ($validator->fails()) {
      return response()->json($validator->errors(), 422);
    }

    // Se o status for "in revision", cria uma nova entrada de revisão
    if ($request->input('status') === 'in revision') {
      $startDate = Carbon::now(); // Obtém a data e hora atuais
      $endDate = $request->input('end_date');

      Revision::create([
        'car_id' => $car->id,
        'description' => $request->input('description'),
        'start_date' => $startDate,
        'end_date' => $endDate,
      ]);
    }

    $car->status = $request->input('status');
    $car->save();

    return response()->json(['message' => 'Car status updated successfully', 'car' => $car]);
  }
}
