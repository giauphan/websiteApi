<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserAPi extends Controller
{
    public function index()
    {
        return view('usertoapi');
    }

    // public function sumbitapi(Request $request)
    // {
    //     // Set the API endpoint URL
    //     $url = 'http://localhost:8000/api/process-file';

    //     // Get the uploaded file from the form data
    //     $data = ['file' => $request->file('file')];

    //     // Set any required headers
    //     $headers = ['Authorization: Bearer <token>'];

    //     // Initialize cURL and set options
    //     $ch = curl_init();
    //     curl_setopt($ch, CURLOPT_URL, $url);
    //     curl_setopt($ch, CURLOPT_POST, true);
    //     curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    //     curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    //     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    //     // Send the request and receive the response
    //     $response = curl_exec($ch);

    //     // Close cURL
    //     curl_close($ch);

    //     // Parse the JSON response
    //     $json_data = json_decode($response, true);

    //     // Use the JSON data in your application
    //     $status = $json_data['status'];
    //     $output = $json_data['output'];
    //     echo  $status ;
    // }
    
}
