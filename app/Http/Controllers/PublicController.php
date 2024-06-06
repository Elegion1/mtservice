<?php

namespace App\Http\Controllers;

use App\Models\Route;
use App\Models\Review;
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
        return view('pages.noleggio-auto');
    }

    public function transfer() {
        return view('pages.transfer');
    }

    public function escursioni() {
        return view('pages.escursioni');
    }

    public function prezziDestinazioni() {
        $tratte = Route::all();
        
        return view('pages.prezzi-destinazioni', compact('tratte'));
    }

    public function diconoDiNoi() {
        $reviews = Review::all();
        return view('pages.dicono-di-noi', compact('reviews'));
    }
}
