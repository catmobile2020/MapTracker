<?php

namespace App\Http\Controllers;

use App\Check;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CheckController extends Controller
{
    public function gmaps()
    {
        $locations = Check::with('user')->get();
        return view('gmaps',compact('locations'));
    }
}
