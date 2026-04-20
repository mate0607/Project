<?php

namespace App\Http\Controllers;

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
        if (auth()->user()->isAdmin()) {
            return redirect('/admin-dashboard');
        }

        return redirect('/dashboard');
    }
}
