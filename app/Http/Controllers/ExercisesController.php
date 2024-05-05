<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class ExercisesController extends Controller
{
    public function exercise(): \Illuminate\Http\JsonResponse
    {
        // Read the contents of the exercise.json file
        $exerciseData = Storage::get('public/exercise.json');

        // Convert the JSON string to an associative array
        $exerciseArray = json_decode($exerciseData, true);

        // Return the exercise data as JSON response
        return response()->json($exerciseArray);
    }
}
