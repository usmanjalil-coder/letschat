<?php

use App\Models\Message;
use Illuminate\Http\Request;
use App\Events\SeenAllMessageEvent;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\VideoController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\RecordingController;
use App\Http\Controllers\UserProfileController;

// Route::get('/', function () {
//     return view('welcome');
// });

// Route::group(['middleware' => ['web']], function () {
    Auth::routes();
// });

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::group(['middleware' => 'auth'], function(){
    Route::post('/send-message', [MessageController::class,'send_message'])->name('send.message');
    Route::get('/fetch-message', [MessageController::class,'fetch_conversation'])->name('fetch.message');
    Route::get('/search-friend',[MessageController::class, 'searchFriend'])->name('search.friend');
    Route::get('/send-friend-request', [MessageController::class,'sendFriendRequest'])->name("send.friend.request");
    Route::get('/fetch-friend-request', [MessageController::class,'getFriendRequestNotification'])->name('fetch.friend.request');
    Route::get('/request-accept-reject', [MessageController::class,'requestAcceptedOrRejected'])->name('request.accept.or.reject');
    Route::post('/is-typing', [MessageController::class,'isTyping'])->name('is.typing');

    Route::get('/user/profile', UserProfileController::class)->name('user.profile');
    Route::post('/change-password', [UserProfileController::class, 'changePassword'])->name('change.password');
    Route::post('/update-profile', [UserProfileController::class, 'updateProfilePic'])->name('update.profile.pic');

    Route::post('/mark-as-seen',function(Request $request) {
        // dd($request->all());
        $receiverImage = \App\Models\User::whereId((int)$request->receiver_id)->select('id','image')->first()->toArray();
        $SenderImage = \App\Models\User::whereId((int)$request->sender_id)->select('id','image')->first()->toArray();
        
        broadcast(new SeenAllMessageEvent(
            $request['receiver_id'], 
            authUserId(),
            !is_null($SenderImage['image']) ?  asset('storage/'.$SenderImage['image']) : asset('images/person.jpg')
        ));
        Message::markAsSeen(authUserId(), $request->receiver_id);
        return response()->json([
            'status' => 200, 
            'message' => 'Message seen'
        ]);
    })->name("mark.as.seen");

});

    Route::post('/update-last-seen', function () {
        try{
            $user = auth()->user();
            if ($user) {
                $user->update(['last_seen' => now()]);
                return response()->json(['success' => 'Last seen updated successfully']);
            }
    
        }catch(Throwable $th){
            dd($th);
        }
    });



Route::get('/videos', [VideoController::class, 'index']);
Route::post('/videos', [VideoController::class, 'store']);


Route::get('audio', function(){
    return view('Audio');
});


Route::get('/recordings', [RecordingController::class, 'index'])->name('recordings.index');
Route::post('/recordings-post', [RecordingController::class, 'store'])->name('recordings.store');



