<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use DB;

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
        // return DB::table('schoolinfo')->first()->cashierversion;
        // Auth::logout();
        if(DB::table('schoolinfo')->first()->cashierversion == 1)
        {
            return redirect('/v1');
        }
        elseif(DB::table('schoolinfo')->first()->cashierversion == 2)
        {
            return redirect('/v2');
        }
    }
}
