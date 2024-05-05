<?php

namespace App\Models;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class UserRoleControllerModel
{
    /**
     * Display the user role selection interface.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(): JsonResponse
    {
        // Return a view or any other response as needed for the user role selection
        // For now, returning an empty response
        return response()->json([]);
    }

    /**
     * Update the specified user role in the session.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request): JsonResponse
    {
        $role = $request->input('role');
        session(['user_role' => $role]);

        // Return the updated user role as JSON response
        return response()->json(['user_role' => $role]);
    }
}

