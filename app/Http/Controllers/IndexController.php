<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class IndexController extends Controller
{

    public function showAvisoLegal()
    {
        return view('warning');
    }

    public function showPrivacidad()
    {
        return view('privacity');
    }

    public function showCookies()
    {
        return view('cookies');
    }
    public function showAboutus()
    {
        return view('aboutus');
    }
}
