<?php

namespace App\Http\Controllers;

use App\Models\Video;
use Illuminate\Http\Request;

class VideoController extends Controller
{
    public function index()
    {
        $videos = Video::all();
        return view('vedio', compact('videos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'video' => 'required|file|mimes:mp4,mov,avi,wmv|max:20000',
        ]);

        $path = $request->file('video')->store('videos', ['disk' =>'public']);

        Video::create(['video_path' => $path]);

        return redirect()->back();
    }
}

