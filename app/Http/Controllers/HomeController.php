<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    public function home(Request $request)
    {
        return response()->view('home');
    }
}
