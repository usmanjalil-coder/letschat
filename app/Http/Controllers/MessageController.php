<?php

namespace App\Http\Controllers;

use App\Events\ChatEvent;
use App\Events\IsTypingEvent;
use App\Models\Message;
use App\Models\Notification;
use App\Models\User;
use App\Models\UserFriend;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use App\Traits\NotificationTrait;
use Exception;
use Throwable;

class MessageController extends Controller
{
    use NotificationTrait;

    public function send_message(Request $request)
    {

        // dd($request->all());
        try {
            $images = [];

            $message_type = '';

            if (isset($request['message'])) {
                $message_type = 'message';
            }
            if ($request->has('images')) {
                $message_type = 'media';
            }
            if (isset($request['message']) && $request['message'] !== null && $request->has('images')) {
                $message_type = 'message_with_media';
            }

            if ($request->has('images')) {

                foreach ($request->file('images') as $file) {
                    $path = $file->store('media', 'public');
                    Log::info('images store path: ' . $path);

                    $images[] = $path;
                }
            }

            if ($request->has('audio') && $request['audio'] !== null) {
                $request->validate([
                    'audio' => 'required|file|max:10240'
                ]);

                $message_type = 'audio';
                $audio = $request->file('audio');
                $filePath = $audio->store('recordings', 'public');
                Log::info('Audio file stored at: ' . $filePath);

                $message = new Message();
                $message->sender_id = Auth::user()->id;
                $message->receiver_id = $request->receiver_id;
                $message->message = $request->message;
                $message->message_type = $message_type;
                $message->audio_file_name = $audio->getClientOriginalName();
                $message->audio_file_path = $filePath;
                $message->save();
            }

            if (!$request->has('audio') || ($request['audio'] === null)) {

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
            $media = count($images) ? $images : null;

            broadcast(new ChatEvent($request['receiver_id'], $request['message'], $name, $renderImage, $createdAt, $message_type, $media, $filePath ?? null));
            return response()->json([
                'status' => 200,
                'message' => 'Message send successfully'
            ]);
        } catch (Throwable $th) {
            dd($th);
        }
    }


    public function fetch_conversation(Request $request)
    {

        try {

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
            if ($receiver_lastseen) {
                $time = Carbon::parse($receiver_lastseen)->timezone('Asia/Karachi');

                $r['last_seen'] = $time->diffInMinutes() < 60 ? $time->diffForHumans() : $time->format('g:i A');
            } else {
                $r['last_seen']  = null;
            }
            // dd($r);
            $view = view('render.chating-box', compact('conversations', 'r'))->render();

            return response()->json([
                'status' => 200,
                'message' => 'Message send successfully',
                'view' => $view
            ]);
        } catch (Throwable $th) {
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

    public function searchFriend(Request $request): JsonResponse
    {
        try {
            $searchValue = trim($request->search_value) !== '' ? $request->search_value : '';
            $searchValue = '%' . $searchValue . '%';
            $friends['data'] = User::where('name', 'LIKE', $searchValue)
                                    ->where('id', '!=', auth()->user()->id)
                                    ->get()->map(function($friend) {
                                        $friend->request_send = Notification::query()->friendRequest()
                                                        ->where('to_user_id', $friend->id)
                                                        ->exists();
                                        $friend->already_friend = UserFriend::query()->isUserFriend($friend)->exists();
                                        return $friend;
                                    });

            $view = view('render.search_friend_list', compact('friends'))->render();
            return response()->json([
                'status' => 'success',
                'view' => $view
            ]);
        } catch (\Exception $e) {
            dd($e);
        }
    }

    public function sendFriendRequest(Request $request): JsonResponse
    {
        try {
            $to_user_id = (int)$request->id;
            if ($to_user_id) {
                $friendRequest = Notification::where('to_user_id', '=', $to_user_id)->friendRequest();

                if ($friendRequest->exists()) {
                    $friendRequest->delete();

                    $this->post_notification([
                        'to_user_id' => $to_user_id,
                        'from_user_id' => authUserId(),
                        'action' => 'request_cancel',
                        'message' => 'friend request cancelled'
                    ]);

                    return response()->json([
                        'status' => 'success',
                        'message' => 'Friend request Cancelled successfully'
                    ]);
                } else {
                    $this->post_notification([
                        'to_user_id' => $to_user_id,
                        'from_user_id' => authUserId(),
                        'action' => 'friend_request',
                        'message' => 'send you friend request'
                    ]);
    
                    return response()->json([
                        'status' => 'success',
                        'message' => 'Friend request send successfully'
                    ]);
                }
            }
            return response()->json([
                'status' => 'error',
                'message' => 'id require'
            ]);
        } catch (Exception $e) {
            dd($e);
        }
    }

    public function getFriendRequestNotification(Request $request)
    {
        try{

            $friendRequest = Notification::join('users', function($query) {
                $query->on('notifications.from_user_id', '=', 'users.id');
            })->where('to_user_id', authUserId())->where('action', 'friend_request')
            ->select('users.id','users.name', 'users.image', 'notifications.*')
            ->latest()
            ->get();
            // dd($friendRequest->toArray());
            $view = view('render.get_notification', compact('friendRequest'))->render();
            return response()->json([
                'status' => 'success',
                'view' => $view
            ]);
        }catch(Exception $e){
            dd($e);
        }
    }

    public function requestAcceptedOrRejected(Request $request)
    {
        $id = $request->id;
        // dd($id);
        $user = User::whereId($id)->first();
        $type = $request->type;
        if(!is_numeric($id)){
            return response()->json([
                'status' => 'error',
                'message' => 'User or id not found'
            ]);
        }
        switch ($type) {
            case 'rejected':
                $noti = Notification::where('to_user_id', authUserId())
                                    ->where('from_user_id', $id)
                                    ->where('action', 'friend_request');
    
                if ($noti->exists()) {
                    $noti->delete();
                    return response()->json([
                        'status' => 'success',
                        'message' => 'Request rejected successfully'
                    ]);
                } else {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Friend request not found'
                    ]);
                }
                break;
            case 'accepted':
                $noti = Notification::where('to_user_id', authUserId())
                                    ->where('from_user_id', $id)
                                    ->where('action', 'friend_request');
                if ($noti->exists()) {
                    $noti->delete();

                    if(! UserFriend::query()->isUserFriend($user)->exists()){
                        UserFriend::create([
                            'user_id' => authUserId(),
                            'friend_id' => $id
                        ]);
                        return response()->json([
                            'status' => 'success',
                            'message' => 'Friend request accepted'
                        ]);

                    }else{

                        return response()->json([
                            'status' => 'error',
                            'message' => 'You are already friend'
                        ]);
                    }

                }
                break;
            case 'unfriend': 
                if(UserFriend::query()->isUserFriend($user)->exists()){
                    UserFriend::query()->isUserFriend($user)->delete();
                    return response()->json([
                        'status' => 'success',
                        'message' => 'Unfreind successfully'
                    ]);
                }
                return response()->json([
                    'status' => 'error',
                    'message' => 'this user is not friend right now'
                ]);
                break;
            default:
                return response()->json([
                    'status' => 'error',
                    'message' => 'Invalid action type'
                ]);
        }
    }
}
