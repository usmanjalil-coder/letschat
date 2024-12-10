<?php

namespace App\Http\Controllers;

use App\Events\ChatEvent;
use App\Models\User;
use Illuminate\Http\Request;

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
        // $userId = 1;
        // $message = 'final message';
        // event(new ChatEvent($userId, $message));
        $all_users = User::where('id','!=', auth()->user()->id)->get();
        return view('home', compact('all_users'));
    }
}
