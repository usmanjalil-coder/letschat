<?php

namespace App\Http\Controllers;

use App\Events\ChatEvent;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $friends = User::where(function ($query) {
            $query->whereHas('userFriends', function ($q) {
                $q->where('friend_id', auth()->user()->id);
            })
            ->orWhereHas('friendOf', function ($q) {
                $q->where('user_id', auth()->user()->id);
            });
        })->get();
        // $friends = User::where('id','!=' ,authUserId())->get();

        return view('home', compact('friends'));
    }
}
