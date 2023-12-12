<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    protected $model;

    public function __construct(){
        $this->model = new User();
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        try{

            if(Auth::attempt($credentials)){
                return response(['message' => "Account is not registered"], 200);
            }

            $user = $this->model->where('email', $request->email)->first();
            $token = $user->createToken($request->email . Str::random(8))->plainTextToken;

            return response(['token' => $token], 200);
        }catch(\Exception $e){
            return response(['message' => $e->getMessage()], 400);
        }
    }

    public function register(Request $request)
    {
        try{
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|confirmed|min:6',
        ]);

            return response()->json($user, 201);
            return response(['message' => "Succesfully created"], 201);

        } catch (\Throwable $e) {
            return response()->json(['status' => false,'message' => $e->getMessage()], 500); 
            return response("Error");
        }
    }
}
