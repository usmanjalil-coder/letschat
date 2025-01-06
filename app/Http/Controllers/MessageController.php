<?php

namespace App\Http\Controllers;

use App\Events\ChatEvent;
use App\Events\IsTypingEvent;
use App\Models\Message;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Throwable;

class MessageController extends Controller
{
    public function send_message(Request $request){

        // dd($request->all());
        try{
            $images = [];

            $message_type = '';

            if(isset($request['message'])){
                $message_type = 'message';
            }
            if($request->has('images')){
                $message_type = 'media';
            }
            if(isset($request['message']) && $request['message'] !== null && $request->has('images')){
                $message_type = 'message_with_media';
            }

            if($request->has('images')){
    
                foreach($request->file('images') as $file){
                    $path = $file->store('media','public');
                    Log::info('images store path: ' . $path);

                    $images[] = $path;
                }
    
            }

            if($request->has('audio') && $request['audio'] !== null){
                $request->validate([
                    'audio' => 'required|file|max:10240'
                ]);

                $message_type = 'audio';
                $audio = $request->file('audio');
                $filePath = $audio->store('recordings', 'public');
                Log::info('Audio file stored at: ' . $filePath);
                
                $message = new Message();
                $message->sender_id =Auth::user()->id;
                $message->receiver_id = $request->receiver_id;
                $message->message = $request->message;
                $message->message_type = $message_type;
                $message->audio_file_name = $audio->getClientOriginalName();
                $message->audio_file_path = $filePath;
                $message->save();
            }

            if(!$request->has('audio') || ($request['audio'] === null)){

                Message::create([
                    'sender_id' => Auth::user()->id,
                    'receiver_id' => $request['receiver_id'],
                    'message' => $request['message'],
                    'images' => json_encode($images),
                    'message_type' => $message_type
                ]);

            }

          
            // dd(count($images));

            $name = ucfirst(Auth::user()->name);
            $renderImage = Auth::user()->image;
            $createdAt = now();
            $media = count($images) ? $images : null ;

            broadcast(new ChatEvent($request['receiver_id'] , $request['message'] , $name, $renderImage, $createdAt , $message_type , $media,$filePath ?? null));
            return response()->json([
                'status' => 200, 
                'message' => 'Message send successfully'
            ]);

        }catch(Throwable $th){
            dd($th);
        }
    }


    public function fetch_conversation(Request $request)
    {

        try
        {

            $request->validate([
                'receiver_id' => 'required|exists:users,id',
            ]);
            $userId = Auth::id();
            $receiverId = $request->input('receiver_id');
        
            $conversations = Message::where(function ($query) use ($userId, $receiverId) {
                $query->where('sender_id', $userId)
                      ->where('receiver_id', $receiverId);
            })->orWhere(function ($query) use ($userId, $receiverId) {
                $query->where('sender_id', $receiverId)
                      ->where('receiver_id', $userId);
            })
            ->with(['sender', 'receiver'])
            ->orderBy('created_at', 'asc')
            ->get();

            $receiver_lastseen = User::whereId($receiverId)->value('last_seen');
            $r['r_name'] = User::whereId($receiverId)->select('name', 'id')->first();
            if($receiver_lastseen){
                $time = Carbon::parse($receiver_lastseen)->timezone('Asia/Karachi');
    
                $r['last_seen'] = $time->diffInMinutes() < 60 ? $time->diffForHumans() : $time->format('g:i A');
            }
            // dd($r);
            $view = view('render.chating-box', compact('conversations','r'))->render();

            return response()->json([
                'status' => 200, 
                'message' => 'Message send successfully',
                'view' => $view
            ]);
        }catch(Throwable $th){
            dd($th);
        }
    }

    public function isTyping(Request $request)
    {
        broadcast(new IsTypingEvent(Auth::user()->name, $request['r_id']));

        return response()->json([
            'status' => 200, 
            'message' => 'Is typing event successfully fired'
        ]);
    }
}
