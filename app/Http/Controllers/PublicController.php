<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PublicController extends Controller
{
    public function home() {
        return view('welcome');
    }

    public function dashboard() {
        return view('dashboard.index');
    }

    public function noleggio() {
        return view('noleggio-auto');
    }
}
