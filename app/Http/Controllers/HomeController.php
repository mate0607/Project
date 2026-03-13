<?php

namespace App\Http\Controllers;

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
        // A Home endpoint csak bejelentkezett felhasznalonak ervenyes.
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function index()
    {
        // Szerepkor alapu atiranyitas: admin mas dashboardot kap, mint a normal user.
        if (auth()->user()->role === 'admin') {
            return redirect('/admin-dashboard');
        }

        return redirect('/dashboard');
    }
}
