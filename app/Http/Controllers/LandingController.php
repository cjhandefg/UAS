<?php

namespace App\Http\Controllers;
use App\Models\Marker;

class LandingController extends Controller
{
    public function index()
    {
        $restaurants = Marker::all();
        return view('landing', ['restaurants' => $restaurants]);
    }
}