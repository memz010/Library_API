<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct() {
        $this->middleware('auth:api')->except(['index','show']);
        $this->middleware('admin')->except(['index','show','update']);
    }


    public function index()
    {
        $users = User::all();
        return response()->json([
            "stuats" => "success",
            "authors" => $users
        ]);
    }
    public function show($id)
    {
        $user = User::find($id);
        if ($user)
            return response()->json([
                "status" => "success",
                "author" => $user
                ]);
        else return response()->json([
            "status" => "error",
            "message" => "there is no user with this id"
        ],422);
    }

    public function update(Request $request, $id)
    {
        $user = User::find($id);
        if ($request->user()->id!= $id && $request->user()->id!= 101)
                return response()->json(['error' => 'Unauthorized'], 401);
        if ($user){
            if ($request->has('name')) {
                $request->validate([
                    'name' => 'required|string',
                ]);
                $user->name = $request->name;
            }       
            if ($request->has('image')) {
                $request->validate([
                    'image' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
                ]);
                $imagepath = $request->file('image')->store('images');
                $user->image = $imagepath;
            }
            $user->save();

            return response()->json([
                'status' => 'success',
                'message' => 'user details updated successfully'
            ], 201);
        }
        else return response()->json([
            "status" => "error",
            "message" => "there is no user with this id"
        ],422);
    }
    public function addBalance(Request $request,$id) {
        $validator = validator($request->all(), [
            'point' => 'required|integer|min:1',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors(),
            ], 422);
        }

        $user = User::find($id);
        if ($user) {
            $user->balance += $request->point;
            $user->save();

            return response()->json([
                'status' => 'success',
                'message' => 'points added successfully'
            ]);
        }
        else return response()->json([
            'status' => 'error',
            'message' => 'there is no user with this id'
        ]);
    }
    public function removeBalance(Request $request,$id) {
        $validator = validator($request->all(), [
            'point' => 'required|integer|min:1',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors(),
            ], 422);
        }

        $user = User::find($id);
        if ($user) {
            if ($user->balance < $request->point) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'there is no enough points to deleted'
                ]);
            }
            $user->balance -= $request->point;
            $user->save();

            return response()->json([
                'status' => 'success',
                'message' => 'points removed successfully'
            ]);
        }
        else return response()->json([
            'status' => 'error',
            'message' => 'there is no user with this id'
        ]);
    }
}
