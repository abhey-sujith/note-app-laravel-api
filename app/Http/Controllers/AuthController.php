<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Requests\AuthRequest;
use App\Http\Requests\AuthLoginRequest;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

/**
 * @OA\Post(
 * path="/api/v1/register",
 * summary="Sign up",
 * description="Signup by name,email, password",
 * operationId="authSignin",
 * tags={"auth"},
 * @OA\RequestBody(
 *    required=true,
 *    description="Pass user credentials",
 *    @OA\JsonContent(
 *       required={"name","email","password","password_confirmation"},
 *       @OA\Property(property="name", type="string", format="name", example="user1"),
 *       @OA\Property(property="email", type="string", format="email", example="user1@mail.com"),
 *       @OA\Property(property="password", type="string", format="password", example="PassWord12345"),
 *       @OA\Property(property="password_confirmation", type="string", format="password", example="PassWord12345"),
 *    ),
 * ),
 *   @OA\Response(
 *     response=200,
 *     description="Success",
 *     @OA\JsonContent(
 *        @OA\Property(property="user", type="object", ref="#/components/schemas/User"),
 *       @OA\Property(property="token", type="string", format="token", example="4|w1HHXZj1AaN8RubsBv96n6Rp8jCcn9H64saaNCRO"),
 *     )
 *  ),
 * @OA\Response(
 *    response=422,
 *    description="Unprocessable Entity",
 *    @OA\JsonContent(
 *       @OA\Property(property="message", type="string", example="The given data was invalid.")
 *        )
 *     )
 * )
 */

 /**
 * @OA\Post(
 * path="/api/v1/login",
 * summary="Sign in",
 * description="Login by email, password",
 * operationId="authLogin",
 * tags={"auth"},
 * @OA\RequestBody(
 *    required=true,
 *    description="Pass user credentials",
 *    @OA\JsonContent(
 *       required={"email","password"},
 *       @OA\Property(property="email", type="string", format="email", example="user1@mail.com"),
 *       @OA\Property(property="password", type="string", format="password", example="PassWord12345"),
 *    ),
 * ),
 *    @OA\Response(
 *     response=200,
 *     description="Success",
 *     @OA\JsonContent(
 *        @OA\Property(property="user", type="object", ref="#/components/schemas/User"),
 *       @OA\Property(property="token", type="string", format="token", example="4|w1HHXZj1AaN8RubsBv96n6Rp8jCcn9H64saaNCRO"),
 *     )
 *  ),
 * @OA\Response(
 *    response=401,
 *    description="Wrong credentials response",
 *    @OA\JsonContent(
 *       @OA\Property(property="message", type="string", example="Email not found/Bad Credentials")
 *        )
 *     )
 * )
 */

  /**
 * @OA\Post(
 * path="/api/v1/logout",
 * summary="logout",
 * description="Logout user and invalidate token",
 * operationId="authLogout",
 * tags={"auth"},
 * security={ {"sanctum": {} }},
 *    @OA\Response(
 *     response=200,
 *     description="Success",
 *     @OA\JsonContent(
 *       @OA\Property(property="message", type="string", example="Logged out")
 *     )
 *  ),
 * @OA\Response(
 *    response=401,
 *    description="Wrong credentials response",
 *    @OA\JsonContent(
 *       @OA\Property(property="message", type="string", example="Unauthenticated")
 *        )
 *     )
 * )
 */


class AuthController extends Controller
{
    public function register(AuthRequest $request){

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);

        $token = $user->createToken('key-note-app')->plainTextToken;

        $response = [
            'user' => $user,
            'token' => $token
        ];

        return response($response,201);
    }

    public function login(AuthLoginRequest $request){

        $user = User::where('email',$request->email)->first();

        if(!$user){
            return response([
                'message' => 'Email not found'
            ],401);
        }
        if(!Hash::check($request->password, $user->password)){
            return response([
                'message' => 'Bad Credentials'
            ],401);
        }

        $token = $user->createToken('key-note-app')->plainTextToken;

        $response = [
            'user' => $user,
            'token' => $token
        ];

        return response($response,201);
    }

    public function logout(Request $request){
        auth()->user()->tokens()->delete();
        return [
            'message' => 'Logged out'
        ];
    }
}
