<?php

namespace App\Http\Controllers;

use App\Http\Requests\Api\LoginRequest;
use App\Http\Requests\Api\RegisterRequest;
use App\Http\Requests\Api\ResetPasswordRequest;
use App\Http\Resources\AccountResource;
use App\User;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Intervention\Image\Facades\Image;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login','register','resetPassword']]);
    }

    /**
     *
     * @SWG\Post(
     *      tags={"auth"},
     *      path="/auth/register",
     *      summary="register",
     *      security={
     *          {"jwt": {}}
     *      },
     *      @SWG\Parameter(
     *         name="name",
     *         in="formData",
     *         required=true,
     *         type="string",
     *         format="string",
     *         default="Ahmed",
     *      ),@SWG\Parameter(
     *         name="email",
     *         in="formData",
     *         required=true,
     *         type="string",
     *         format="string",
     *         default="email@gmail.com",
     *      ),@SWG\Parameter(
     *         name="image",
     *         in="formData",
     *         required=true,
     *         type="file",
     *         format="string",
     *      ),@SWG\Parameter(
     *         name="password",
     *         in="formData",
     *         required=true,
     *         type="string",
     *         format="string",
     *         default="123456",
     *      ),
     *      @SWG\Response(response=200, description="token"),
     *      @SWG\Response(response=401, description="Unauthorized"),
     *      @SWG\Response(response=422, description="Validation Error"),
     *      @SWG\Response(response=403, description="Forbidden The client did not have permission to access the requested resource."),
     * )
     * @param RegisterRequest $request
     * @return \Illuminate\Http\JsonResponse
     */

    public function register(RegisterRequest $request)
    {


        $user = User::create($request->all());
        $image = Image::make($request->file('image'));
        $image->encode('png');

        /* if you want to have a perfect and complete circle using the whole width and height the image
         must be shaped as as square. If your images are not guaranteed to be a square maybe you could
         use Intervention's fit() function */
        $image->fit(50,50);

        // create empty canvas
        $width = $image->getWidth();
        $height = $image->getHeight();
        $mask = Image::canvas($width, $height);

        // draw a white circle
        $mask->circle($width, $width/2, $height/2, function ($draw) {
            $draw->background('#fff');
        });

        $image->mask($mask, false);
        $path = "uploads/".$user->id."circled.png";
        $image->save($path);

        $user->image = $path;

        $user->save();
        $token = auth()->login($user);
        return $this->respondWithToken($token);
    }

    /**
     *
     * @SWG\Post(
     *      tags={"auth"},
     *      path="/auth/login",
     *      summary="login",
     *      security={
     *          {"jwt": {}}
     *      },
     *      @SWG\Parameter(
     *         name="email",
     *         in="formData",
     *         required=true,
     *         type="string",
     *         format="string",
     *         default="ahmed@gmail.com",
     *      ),
     *      @SWG\Parameter(
     *         name="password",
     *         in="formData",
     *         required=true,
     *         type="string",
     *         format="string",
     *         default="123456",
     *      ),
     *      @SWG\Response(response=200, description="token"),
     *      @SWG\Response(response=401, description="Unauthorized"),
     *      @SWG\Response(response=402, description="Validation Error"),
     *      @SWG\Response(response=403, description="Forbidden The client did not have permission to access the requested resource."),
     * )
     * @param LoginRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(LoginRequest $request)
    {
        $credentials = $request->only(['email', 'password']);

        if (! $token = auth()->attempt($credentials)) {
            return $this->responseJson('Email Or Password Is Invalid.',401);
        }
        return $this->respondWithToken($token);
    }


    /**
     *
     * @SWG\Post(
     *      tags={"auth"},
     *      path="/auth/logout",
     *      summary="logout currently logged in user",
     *      security={
     *          {"jwt": {}}
     *      },
     *      @SWG\Response(response=200, description="message"),
     *      @SWG\Response(response=401, description="Unauthorized"),
     *      @SWG\Response(response=402, description="Validation Error"),
     *      @SWG\Response(response=403, description="Forbidden The client did not have permission to access the requested resource."),
     * )
     */
    public function logout()
    {
        auth()->logout();
        return $this->responseJson('Successfully logged out',200);
    }

    /**
     *
     * @SWG\Post(
     *      tags={"auth"},
     *      path="/auth/refresh",
     *      summary="refreshes expired token",
     *      security={
     *          {"jwt": {}}
     *      },
     *      @SWG\Response(response=200, description="message"),
     *      @SWG\Response(response=401, description="Unauthorized"),
     *      @SWG\Response(response=402, description="Validation Error"),
     *      @SWG\Response(response=403, description="Forbidden The client did not have permission to access the requested resource."),
     * )
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60,
            'account' => AccountResource::make(auth()->user()),
        ]);
    }
}
