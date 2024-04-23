<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Address;

class AddressController extends Controller
{
    public function get_user_addresses(Request $request)
    {
        if (!$request->user()) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }
    
        $user_addresses = $request->user()->addresses;
        return response()->json(['addresses' => $user_addresses], 200);
    }

    public function index()
    {
        $addresses = Address::all();
        return response()->json(['addresses' => $addresses], 200);
    }

    public function show($id)
    {
        $address = Address::find($id);
        if (!$address) {
            return response()->json(['message' => 'Address not found'], 404);
        }
        return response()->json(['address' => $address], 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'surname' => 'required|string',
            'address' => 'required|string',
            'more_info' => 'required|string',
            'district' => 'required|string',
            'locality' => 'required|string',
            'phone' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $user = $request->user(); 
        if (!$user) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }

        $address = Address::create([
            'name' => $request->input('name'),
            'surname' => $request->input('surname'),
            'address' => $request->input('address'),
            'more_info' => $request->input('more_info'),
            'district' => $request->input('district'),
            'locality' => $request->input('locality'),
            'phone' => $request->input('phone'),
            'user_id' => $user->id
        ]);

        return response()->json(['address' => $address], 201);
    }

    public function update(Request $request, $id)
    {
        $address = Address::find($id);

        if (!$address) {
            return response()->json(['message' => 'Address not found'], 404);
        }
        $validator = Validator::make($request->all(), [
            'name' => 'nullable|string',
            'surname' => 'nullable|string',
            'address' => 'nullable|string',
            'more_info' => 'nullable|string',
            'district' => 'nullable|string',
            'locality' => 'nullable|string',
            'phone' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $address->update($request->all());

        return response()->json(['address' => $address], 200);
    }

    public function destroy($id)
    {
        $address = Address::find($id);
        if (!$address) {
            return response()->json(['message' => 'Address not found'], 404);
        }

        $address->delete();
        return response()->json(['message' => 'Address deleted successfully'], 200);
    }

    public function deactivate_address($id)
    {
        $address = Address::find($id);
        if (!$address) {
            return response()->json(['message' => 'Address not found'], 404);
        }

        $address->update(['isDeleted' => true]);
        return response()->json(['message' => 'Address deactivated successfully'], 200);
    }
}
