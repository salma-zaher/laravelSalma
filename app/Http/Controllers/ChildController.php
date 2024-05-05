<?php

namespace App\Http\Controllers;

use App\Models\Child;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use PhpParser\Node\Stmt\TryCatch;

class ChildController extends Controller
{public function showRegistrationForm(Request $request): \Illuminate\Http\JsonResponse
{
    $validatedData = Validator::make($request->all(), [
        'baby_name' => 'required|string',
        'gender' => 'required|in:male,female,other',
        'date_of_birth' => 'required|date',
    ]);

    if ($validatedData->fails()) {
        return response()->json([
            'status' => false,
            'message' => 'Validation error',
            'errors' => $validatedData->errors()
        ], 401);
    }

    try {
        $user = Child::create([
            'baby_name' => $request->baby_name,
            'gender' => $request->gender,
            'date_of_birth' => $request->date_of_birth
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'User Successfully Registered',
            'user' => $user,
            // 'token'=>$user->createToken("API TOKEN")->plainTextToken
        ], 200);
    } catch (\Throwable $th) {
        return response()->json([
            'status' => false,
            'message' => $th->getMessage()
        ], 500);
    }
}

}











