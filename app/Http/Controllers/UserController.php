<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

use App\User;

class UserController extends Controller
{
    public function login(Request $request){
        $credentials = $request->only('username', 'password');

        try {
            if (! $token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'invalid_credentials'], 400);
            }
        } catch (JWTException $e) {
            return response()->json(['error' => 'could_not_create_token'], 500);
        }

        // return response()->json(compact('token'));
        return response()->json([
        	'token' => $token,
        	'username' => $request->user()->username,
        	'email' => $request->user()->email,
        	'role' => $request->user()->role,
        	'created_at' => $request->user()->created_at,
        	'updated_at' => $request->user()->updated_at
        ]);
    }

    public function register(Request $request){
        $validator = Validator::make($request->all(), [
            'username' => 'required|string|max:255|unique:users,username',
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'password' => 'required|string',
            'role' => 'required|string',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }

        // exist username
        $user = User::where('username', $request->username)
                    ->orWhere('email', $request->email)->get();
        // dd($user);
        if(!$user->isEmpty()){
            return response()->json(['status' => 'Username or Email exist'], 400);
        }

        $user = User::create([
        	'username' => $request->get('username'),
            'name' => $request->get('name'),
            'email' => $request->get('email'),
            'role' => $request->get('role'),
            'password' => Hash::make($request->get('password')),
        ]);

        $token = JWTAuth::fromUser($user);

        return response()->json(compact('user','token'),201);
    }

    public function users(Request $request){
        if(app(PermissionController::class)->isAdmin($request->user()->role)){
            $user = User::all();
        } else if(app(PermissionController::class)->isStaff($request->user()->role)){
            $user = User::where('role', '!=', 'admin')->get();
        } else {
            $user = User::find($request->user()->_id);
        }
    	return $user;
    }

    public function getAuthenticatedUser(){
        try {

            if (! $user = JWTAuth::parseToken()->authenticate()) {
                return response()->json(['user_not_found'], 404);
            }

        } catch (Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {

            return response()->json(['token_expired'], $e->getStatusCode());

        } catch (Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {

            return response()->json(['token_invalid'], $e->getStatusCode());

        } catch (Tymon\JWTAuth\Exceptions\JWTException $e) {

            return response()->json(['token_absent'], $e->getStatusCode());

        }

        return response()->json(compact('user'));
    }
}
