<?php

namespace App\Http\Controllers;

use App\Check;
use App\Events\MapEvent;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
   public function store(Request $request)
   {
        $this->validate($request, [
            'name'=>'required',
            'email'=>'required|unique:users,email',
            'password'=>'required|min:6|confirmed'
        ]);

       return User::create([
           'name' => $request['name'],
           'email' => $request['email'],
           'password' => Hash::make($request['password']),
       ]);

   }


   public function checkIn(Request $request, User $user)
   {
       if ($user->checks === null)
       {
           $checks = new Check([
               'city'       => $request->city,
               'address'    => $request->address,
               'latitude'   => $request->latitude,
               'longitude'  => $request->longitude,
           ]);
          $check =  $user->checks()->save($checks);
       }
       else
       {
           $check = $user->checks->update([
               'city'       => $request->city,
               'address'    => $request->address,
               'latitude'   => $request->latitude,
               'longitude'  => $request->longitude
           ]);
       }

       $locations = User::has('checks')->with('checks')->get();
        //return $locations;
       event(new MapEvent($locations));
       return response()->json(['message'=>'success']);
   }
}
