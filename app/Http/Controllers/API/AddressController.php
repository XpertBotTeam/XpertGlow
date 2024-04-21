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
            'more_info' => 'nullable|string',
            'district' => 'nullable|string',
            'locality' => 'nullable|string',
            'phone' => 'required|string',
            'isDeleted' => 'nullable|boolean',
            'user_id' => 'required|exists:users,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $address = Address::create($request->all());

        return response()->json(['address' => $address], 201);
    }

    public function update(Request $request, $id)
    {
        $address = Address::find($id);

        if (!$address) {
            return response()->json(['message' => 'Address not found'], 404);
        }
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'surname' => 'required|string',
            'address' => 'required|string',
            'more_info' => 'nullable|string',
            'district' => 'nullable|string',
            'locality' => 'nullable|string',
            'phone' => 'required|string',
            'isDeleted' => 'nullable|boolean',
            'user_id' => 'required|exists:users,id',
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
