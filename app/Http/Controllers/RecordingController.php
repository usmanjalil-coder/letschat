<?php

namespace App\Http\Controllers;

use App\Models\Recording;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class RecordingController extends Controller
{
    public function index()
    {
        $recordings = Recording::all();
        return view('Audio', compact('recordings'));
    }

    public function store(Request $request)
    {

        // dd($request->all());
        try {
            $request->validate([
                'audio' => 'required|file|max:10240'
            ]);
    
            $audio = $request->file('audio');
            $filePath = $audio->store('recordings', 'public');
    
            $recording = new Recording();
            $recording->file_name = $audio->getClientOriginalName();
            $recording->file_path = $filePath;
            $recording->save();
    
            return response()->json(['success' => true, 'file_path' => $filePath], 200);
    
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    

}

