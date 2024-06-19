<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mum;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
//use App\Http\Controllers\Controller;
use PhpParser\Node\Stmt\TryCatch;

class MumRegistrationController extends Controller
{
    public function register(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $validateUser= Validator::make($request->all(),
            [
                'id'=> 'required|integer',
                'baby_name' => 'required|string|max:255',
                'gender'=>'required|in:male,female,other',
                'date_of_birth' => 'required|date|date_format:Y-m-d',
            ]);

            if ($validateUser->fails()) {
                return response()->json([
                    'status'=>false,
                    'message'=>'Validation error',
                    'errors'=>$validateUser->errors()
                ],401);
            }


            $user = Mum::create([
                'id'=>$request->id,
                'gender'=>$request->gender,
                'baby_name'=>$request->baby_name,
                'date_of_birth'=>$request->date_of_birth,
            ]);

            return response()->json([
                'status'=>'success',
                'message'=>'User Successfully Registered',
                'user'=>$user,
                // 'token'=>$user->createToken("API TOKEN")->plainTextToken
            ],200);

        } catch (\Throwable $th) {
            return response()->json([
                'status'=>false,
                'message'=>$th->getMessage()
            ], 500);
        }
    }

//        public function showRegistrationForm()
//    {
//        // Return a view or any other response as needed for the registration form
//        // For now, returning an empty response
//        return response()->json([]);
//    }

}

/*
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mom;

class MumRegistrationController extends Controller
{
//    public function showRegistrationForm()
//    {
//        // Return a view or any other response as needed for the registration form
//        // For now, returning an empty response
//        return response()->json([]);
//    }

    public function processRegistration(Request $request)
    {
        // Validate form data
        $validatedData = $request->validate([
            'baby_name' => 'required|string|max:255',
            'date_of_birth' => 'required|date'
        ]);

        // Process the form data as needed
        // For demonstration purposes, you can simply return the validated data
        return response()->json(['validatedData' => $validatedData]);
    }
}
*/
