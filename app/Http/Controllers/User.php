<?php

namespace App\Http\Controllers;

use JWTAuth;
use Validator;
use Tymon\JWTAuth\Exceptions\JWTException;  
use Illuminate\Http\Request;
use App\Models\User as muser;

class User extends Controller{

    public function __construct(){
        $this->middleware('auth:api', ['except' => ['login', 'register_user']]);
    }

    public function cekExistingEmail($mail){
        if (muser::where('email', $mail)->first()) {
            return true;
        } else {
            return false;
        }
    }
    
    public function register_user(Request $req){
        muser::create([
            'name' => $req->name,
            'email' => $req->email,
            'password' => bcrypt($req->password)
        ]);
    }

    public function login(Request $req){
        $credential = $req->only('email', 'password');
        $validator = Validator::make($credential, [
            'email' => 'required|email',
            // 'password' => 'required|string|min:6',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => ['Login credentials are invalid.'],
            ], 400); 
        }

        try {
            if (! $token = JWTAuth::attempt($credential)) {
                return response()->json([
                	'status' => false,
                	'message' => ['Login credentials are invalid.'],
                ], 400);
            }
        } catch (JWTException $e) {
    	return $credential;
            return response()->json([
                	'status' => false,
                	'message' => ['Could not create token.'],
                ], 500);
        }

        return $this->respondWithToken($token);
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
            'expires_in' => auth('api')->factory()->getTTL() * 60
        ]);
    }
}
