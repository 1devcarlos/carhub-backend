<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CompanyController extends Controller
{
  public function index()
  {
    $empresas = Company::all();
    return response()->json(compact('empresas'));
  }

  public function store(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'company_name' => 'required|string|max:255',
      'address' => 'required|string|max:255',
      'phone' => 'required|string|max:15',
      'email' => 'required|string|email|max:255|unique:companies',
    ]);

    if ($validator->fails()) {
      return response()->json($validator->errors(), 422);
    }

    $company = Company::create([
      'company_name' => $request->company_name,
      'address' => $request->address,
      'phone' => $request->phone,
      'email' => $request->email,
    ]);

    return response()->json(compact('company'), 201);
  }

  public function update(Request $request, $id)
  {
    $company = Company::find($id);

    if (!$company) {
      return response()->json(['error' => 'Company not found'], 404);
    }

    $validator = Validator::make($request->all(), [
      'company' => 'sometimes|string|max:255',
      'address' => 'sometimes|string|max:255',
      'phone' => 'sometimes|string|max:15',
      'email' => 'sometimes|string|email|max:255|unique:companies,email,' . $id,
    ]);

    if ($validator->fails()) {
      return response()->json($validator->errors(), 422);
    }

    $company->fill($request->all());
    $company->save();

    return response()->json(compact('company'));
  }

  public function destroy(Request $request, $id)
  {
    $company = Company::find($id);

    if (!$company) {
      return response()->json(['error' => 'Company not found'], 404);
    }

    $company->delete();

    return response()->json(['message' => 'Company deleted sucessfully']);
  }
}
