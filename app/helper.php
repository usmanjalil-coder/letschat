<?php

use Illuminate\Support\Facades\Auth;

function authUserId()
{
    return Auth::check() ? Auth::user()->id : 0;
}