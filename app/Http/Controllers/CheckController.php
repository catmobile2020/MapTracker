<?php

namespace App\Http\Controllers;

use App\Check;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CheckController extends Controller
{
    public function gmaps()
    {
        $locations = User::has('checks')->with('checks')->get();

       // return $locations;
        return view('gmaps',compact('locations'));
    }
}
