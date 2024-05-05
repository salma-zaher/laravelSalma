<?php

namespace App\Http\Controllers;

use App\Models\pregnant;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use PhpParser\Node\Stmt\TryCatch;

class PregnantRegistrationController extends Controller
{
    public function register(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $validateUser = Validator::make($request->all(), [
                'id' => 'required|integer',
                'first_day_of_last_period' => 'nullable|date',
                'estimated_due_date' => 'nullable|date',
                'date_of_conception' => 'nullable|date',
            ]);

            if ($validateUser->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validation error',
                    'errors' => $validateUser->errors()
                ], 401);
            }

            $user = User::create([
                'id' => $request->id,
                'first_day_of_last_period' => $request->first_day_of_last_period,
                'estimated_due_date' => $request->estimated_due_date,
                'date_of_conception' => $request->date_of_conception,
            ]);

            // Calculate age in weeks
            $ageInWeeks = null;
            $providedDate = null;

            if ($request->filled('first_day_of_last_period')) {
                $providedDate = Carbon::createFromFormat('Y-m-d', $request->first_day_of_last_period);
            } elseif ($request->filled('estimated_due_date')) {
                $providedDate = Carbon::createFromFormat('Y-m-d', $request->estimated_due_date);
            } elseif ($request->filled('date_of_conception')) {
                $providedDate = Carbon::createFromFormat('Y-m-d', $request->date_of_conception);
            }

            if ($providedDate) {
                $today = Carbon::today();
                $ageInWeeks = $providedDate->diffInWeeks($today);
            }

            return response()->json([
                'status' => 'success',
                'message' => 'User Successfully Registered',
                'user' => $user,
                'ageInWeeks' => $ageInWeeks,
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }


}















