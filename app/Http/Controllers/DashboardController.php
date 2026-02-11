<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Show the application landing page.
     */
    public function index()
    {
        return view('inicio');
    }
}
