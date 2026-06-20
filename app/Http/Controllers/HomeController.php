<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        $role = Auth::user()->role;

        if ($role === 'admin') {
            return redirect()->route('admin.dashboard');
            
        } elseif ($role === 'doctor') {
            return redirect()->route('doctor.dashboard');
            
        } elseif ($role === 'member')  {
            return redirect()->route('member.dashboard');
        } else {
            abort(403, 'Unauthorized action.');
        }
    }
}
