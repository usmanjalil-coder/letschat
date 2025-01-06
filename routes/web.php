<?php

use App\Http\Controllers\MessageController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VideoController;
use Illuminate\Support\Facades\Http;

// Route::get('/', function () {
//     return view('welcome');
// });

Auth::routes();

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::group(['middleware' => 'auth'], function(){
    Route::post('/send-message', [MessageController::class,'send_message'])->name('send.message');
    Route::get('/fetch-message', [MessageController::class,'fetch_conversation'])->name('fetch.message');
    Route::get('/search-friend',[MessageController::class, 'searchFriend'])->name('search.friend');
    Route::get('/send-friend-request', [MessageController::class,'sendFriendRequest'])->name("send.friend.request");
    Route::get('/fetch-friend-request', [MessageController::class,'getFriendRequestNotification'])->name('fetch.friend.request');
    Route::get('/request-accept-reject', [MessageController::class,'requestAcceptedOrRejected'])->name('request.accept.or.reject');
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
// Route::post('/is-typing', [MessageController::class,'isTyping'])->name('is.typing');



Route::get('/videos', [VideoController::class, 'index']);
Route::post('/videos', [VideoController::class, 'store']);


Route::get('/curl',function(){

    $url = 'https://catfact.ninja/fact';
    $ci = curl_init();

    curl_setopt($ci, CURLOPT_URL, $url);
    curl_setopt($ci, CURLOPT_RETURNTRANSFER, true); 
    curl_setopt($ci, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ci, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);

    $result = curl_exec($ci);

    if($result === FALSE)
    {
        return 'error is ' . curl_error($ci);
    }

    return dd(json_decode($result));

});

Route::get('/http',function(){

    $url = 'https://catfact.ninja/fact';

    return Http::get($url)['length'];
    

});

Route::get('audio', function(){
    return view('Audio');
});

use App\Http\Controllers\RecordingController;

Route::get('/recordings', [RecordingController::class, 'index'])->name('recordings.index');
Route::post('/recordings-post', [RecordingController::class, 'store'])->name('recordings.store');



