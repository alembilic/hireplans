<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ParameterController extends Controller
{
    public function handleParameters($parameter1, $parameter2 = null) {
        // ... (rest of the code)
        dd('Parameter 1: ' . $parameter1 . '<br>' . 'Parameter 2: ' . $parameter2);

        // Access the parameters:
        // $value1 = $parameter1;
        // $value2 = $parameter2; // Will be null if not provided

        // // Do something with the parameters (e.g., validation, database lookup, etc.)

        // // Redirect to a view (replace 'view-name' with your actual view):
        // return view('view-name', ['data' => $data]); // Pass any data you want to the view
    }
}
