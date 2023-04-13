<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function index(){
        $user = User::get();
        return response()->json(['user' => $user ? $user : []]);
    }

    public function store(Request $request){
        // if(!auth()->user()){
        //     return response()->json(['message' => 'Unauthorized access'], 401);
        // }
        $validate = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'email' => 'required|unique:users',
            'password' => 'required',
            'roles' => 'required',
        ]);
        
        if($validate->fails()){
            return response()->json(['message' => $validate->messages()]);
        }
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->roles = $request->roles;
        $user->save();
        return response()->json([
            'message' => 'User added successfully',
            'user' => $user
        ], 201);
    }

    public function edit($id){
        $user = User::find($id);
        if(!$user){
            return response()->json(['message' => 'Record not found']);
        }
        return response()->json(['user' => $user]);
    }

    public function update(Request $request, $id){
        
        $user = User::find($id);
        if(!$user){
            return response()->json(['message' => 'Record not found']);
        }
        if($user->email == $request->email){
            $validate = Validator::make($request->all(), [
                'name' => 'required|max:255',
                'email' => 'required|email|exists:users',
                'password' => 'required',
                'role' => 'required',

            ]);
        }else{
            $validate = Validator::make($request->all(), [
                'name' => 'required|max:255',
                'email' => 'required|email|unique:users',
                'password' => 'required',

            ]);
        }
        
        if($validate->fails()){
            return response()->json(['message' => $validate->messages()]);
        }
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->role = $request->role;
        $user->save();
        return response()->json(['message' => 'Record Updated Successfully', 'user' => $user]);
    }

    public function destroy($id){
        $user = User::find($id);
        $user->delete();
        if(!$user){
            return response()->json(['message' => 'Record not found']);
        }
        return response()->json(['message' => 'Record deleted successfully']);
    }

    public function search_user(Request $request){
        $user = User::where('name', 'like', '%' . $request->search . '%')->Orwhere('email', 'like', '%' . $request->search . '%')->get();
        if(count($user) == 0){
            return response()->json(['message' => 'Record not found']);
        }
        return response()->json(['user' => $user]);
    }

    public function get_user(){
        $users = auth()->user();
        return response()->json(['user' => $users]);
    }
}
