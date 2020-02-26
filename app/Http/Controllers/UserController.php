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

    /**
     *
     * @SWG\Post(
     *      tags={"Check in"},
     *      path="/check/{user}/add",
     *      summary="logout currently logged in user",
     *      security={
     *          {"jwt": {}}
     *      },
     *     @SWG\Parameter(
     *         name="user",
     *         in="path",
     *         required=true,
     *         type="integer",
     *         format="integer",
     *         default="1",
     *      ),
     *     @SWG\Parameter(
     *         name="city",
     *         in="formData",
     *         required=true,
     *         type="string",
     *         format="string",
     *         default="Jeddah",
     *      ),@SWG\Parameter(
     *         name="address",
     *         in="formData",
     *         required=true,
     *         type="string",
     *         format="string",
     *         default="Jeddah, Tihamah, Saudi Arabia	",
     *      ),@SWG\Parameter(
     *         name="latitude",
     *         in="formData",
     *         required=true,
     *         type="string",
     *         format="string",
     *          default="21.543333",
     *      ),@SWG\Parameter(
     *         name="longitude",
     *         in="formData",
     *         required=true,
     *         type="string",
     *         format="string",
     *         default="39.172779",
     *      ),
     *      @SWG\Response(response=200, description="message"),
     *      @SWG\Response(response=401, description="Unauthorized"),
     *      @SWG\Response(response=402, description="Validation Error"),
     *      @SWG\Response(response=403, description="Forbidden The client did not have permission to access the requested resource."),
     * )
     */

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

       //$locations = User::has('checks')->get();
        //return $locations;
       event(new MapEvent($user));
       return response()->json(['message'=>'success']);
   }
}
