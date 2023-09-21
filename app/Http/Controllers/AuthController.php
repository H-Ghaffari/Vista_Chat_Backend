<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\Validator;

class AuthController extends ApiController
{
    public function register(Request $request){
        $validator = Validator::make($request->all(),[
            'name'=> 'string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string',
        ]); 

        if($validator->fails()){
            return $this->errorResponse($validator->messages(), 422);
        }

        $user=User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // dd($user);
        $token = $user->createToken('myApp')->plainTextToken;

        return $this->successResponse([
            'user' => new UserResource($user),
            'token' => $token
        ], 201);
    }

    public function login(Request $request){
        $validator = Validator::make($request->all(),[
            'email' => 'required|email',
            'password' => 'required|string',
        ]); 

        if($validator->fails()){
            return $this->errorResponse($validator->messages(), 422);
        }

        $user = User::where('email', $request->email)->first();
        
        if(!$user){
             return $this->errorResponse('کاربری با این ایمیل یافت نشد.', 401);
        }

        if(!Hash::check($request->password , $user->password)){
            return $this->errorResponse('رمز عبور اشتباه است.', 401);
        }

        $token = $user->createToken('myApp')->plainTextToken;

        return $this->successResponse([
            'user' => new UserResource($user),
            'token' => $token
        ],200);
    }

    public function logout(){
        auth()->user()->tokens()->delete();
        return $this->successResponse('logged out', 200);
    }

    public function me()
    {
        $user = User::find(Auth::id());
        return $this->successResponse(new UserResource($user), 200);
    }
}