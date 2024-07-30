<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class userController extends Controller
{
    public function fetchUser()
    {
        // Fetch user data from the authenticated user or any other source
        $user = Auth::user(); // Or replace with your method of fetching user data

        return response()->json([
            'success' => true,
            'user' => $user
        ]);
    }
}
