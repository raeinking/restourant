<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class DataController extends Controller
{
    public function fetchData(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'database' => 'required|string',
        ]);

        $loading = true; // Set loading to true initially
        $data = [];
        $error = '';

        try {
            $client = new Client();
            $baseUrl = env('SERVER_URL');
            $apiUrl = $baseUrl . '/loginwithtoken';

            // Log request data for debugging
            Log::info('Making API request', ['url' => $apiUrl, 'database' => $request->input('database')]);

            $response = $client->request('POST', $apiUrl, [
                'json' => [
                    'database' => $request->input('database'),
                ],
                'headers' => [
                    'Authorization' => 'Bearer ' . $request->header('Authorization'),
                ],
            ]);

            $data = json_decode($response->getBody()->getContents(), true);

            // Log response data for debugging
            Log::info('API response received', ['response' => $data]);

            $loading = false; // Set loading to false after data is fetched
        } catch (\Exception $e) {
            $error = $e->getMessage();
            Log::error('API request failed', ['error' => $error]);
            $loading = false; // Set loading to false in case of an error
        }

        // Return the 'home' view with data, loading status, and error information
        return view('home', [
            'data' => $data,
            'loading' => $loading,
            'error' => $error
        ]);
    }
}
