<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AudioController extends Controller
{
    public function store(Request $request)
    {
        if ($request->hasFile('audio')) {
            $file = $request->file('audio');
            $path = $file->storeAs('public/rec', 'recording.webm');
            return response()->json(['path' => $path]);
        }
        
        return response()->json(['error' => 'No audio file received'], 400);
    }
}
