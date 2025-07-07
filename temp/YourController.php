<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class YourController extends Controller
{
    public function handleIndex(Request $request)
       {
           $product = $request->input('product');
           $color = $request->input('color');

           // Do something with the parameters (e.g., fetch data from the database)

           return view('your-view', compact('product', 'color'));
       }
}
