<?php

namespace App\Http\Controllers;

use App\Models\Stream;
use Illuminate\Http\Request;

class StreamController extends Controller
{
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'stream_name' => 'required|min:4',
            'pixel_id' => 'required|min:15|max:16',
            'landing_id' => 'required|exists:products,landing_id',
        ]);

        // Create the stream
        $stream = new Stream([
            'stream_name' => $validatedData['stream_name'],
            'source' => auth()->user()->source,
            'pixel_id' => $validatedData['pixel_id'],
            'landing_id' => $validatedData['landing_id'],
        ]);
        $stream->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Yangi Oqim muvaffaqiyatli yaratildi.',
            'stream' => $stream,
        ]);
    }
}
