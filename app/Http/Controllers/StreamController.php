<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StreamController extends Controller
{
    public function show($link)
    {
        $path = public_path("l/{$link}.php");

        if (file_exists($path)) {
            return require $path; // Load the correct PHP file
        }

        return abort(404); // Return 404 if not found
    }
}
