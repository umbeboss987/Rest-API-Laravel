<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class UserController extends Controller
{
    public function login(Request $req){
       $login =  $req->validate([
            'username' => 'required|string',
            'password' => 'required|string'
       ]);
        if(!Auth::attempt($login)){
            return response(['message' => 'Invalid credentials'], 401);
        }
        $accessToken = Auth::user()->createToken('authToken')->accessToken;

        $user =  Auth::user();

        unset($user['image_id'], $user['created_at'],  $user['updated_at']);

        return response(['user'=> $user, 'accessToken'=>$accessToken],200);
    }

    public function register(Request $req)
    {
        $req->validate([
                'username' => 'required|min:2',
                'email' => 'required|email',
                'password' => 'required|min:4',
                'role' => 'required',
        ]);

        if (!User::where('username', $req->username)->first()){
            $user = new User();
            $user->email = $req->email;
            $user->username = $req->username;
            $user->password = bcrypt($req->password);
            $user->role = $req->role;
            $user->save();
            return response()->json(['message' => "User Created"], 201);
        }else{
            return response()->json(['error' => 'Username is already registered.'], 409);
        }
    }

    function updateUser (Request $req) {
        $req_all = $req->all();
        
        $user_id = auth()->user()->id;
        $user =  User::find($user_id);
        isset($req_all['email']) ? $user->email = $req_all['email'] : $user->email;
        isset($req_all['username']) ? $user->username = $req_all['username'] : $user->username;
        $user->save();
        return response(null, 204);
    }

    function getUser(){
        $user = Auth::user();
        return response()->json($user);
    }


    function getAllUsers(){
        $users = User::all();
        return response()->json($users);
     }
 
     function deleteUser ($user_id){
        $user = User::where('id', $user_id);
        if(!$user->exists()){
            return response()->json(["message" => "user dosnt exist"], 404);
        }else{
            $user->delete(); 
            return response()->json(null, 204);       
        }
     } 
}
