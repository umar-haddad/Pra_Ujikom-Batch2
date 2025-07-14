<?php

namespace App\Http\Controllers;

use Throwable;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ApiController extends Controller
{

    public function loginAction(Request $request)
    {
        $credential = $request->only("email" , "password");
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|min:5'
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'errors' => $validator->errors()], 422);
        }

        if (!Auth::attempt($credential)) {
            return response()->json(['status' => 'error', 'message'=>'Invalid Credential'], 401);
        }

        $user = Auth::user();
        $token= $user->createToken('api-token')->plainTextToken;
        return response()->json(['status' => 'success', 'user' => $user, 'token' => $token]);

        // kita ini generate token pake sanctum ataun jwt
    }

    public function me() {
        return response()->json(['status' => 'success', 'data' => Auth::user()]);
    }

    public function getUsers()
    {
        $users = User::get();
        return response()->json(['data' => $users]);
    }
    public function storeUser(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'email' => 'required|email|unique:users,email',
                'password' => 'required'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'errors' => $validator->errors()
                ], 422);

            }
            $users = User::create($request->all());
            return response()->json(['data' => $users, 'message' => 'Request Success'], 201);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => 'Request failed',
                'error' => $th->getMessage()
            ], 500);
        }
    }

    public function updateUser(Request $request, $id)
    {
        try {
            $user = User::findOrFail($request->id);
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|min:8'
            ]);

             if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $user->name = $request->name;
            $user->email = $request->email;

            if ($request->filled('password')) {
                $user->password = Hash::make($request->password);
            }
            $user->save();
            return response()->json(['status' => 'success', 'message' => 'Request Update Success', 'data' => $user]);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => 'Request failed',
                'error' => $th->getMessage()
            ], 500);
        }
    }


    public function deleteUser ($id)
    {
        try {
            $user = User::findOrFail($id);

             if (!$id) {
                return response()->json([
                    'status' => 'error',
                    'errors' => $$id->errors()
                ], 422);
            }
            $user->delete();
            return response()->json(
                [
                    'status' => 'success',
                    'message' => 'User deleted successfully',
                    'data' => $user],
                    200);


        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => 'Request failed',
                'error' => $th->getMessage()
            ], 500);
        }
    }

    public function editUser(Request $request)
{

    $userId = $request->query('id');

    // if ($id) {
    //     $userId = $id;
    // } else {
    //     $userId = $request->query('id');
    // }

    $user = User::find($userId);
    if (!$user) {
        return response()->json([
            'status' => 'error',
            'message' => 'User ID is required.'
        ], 422);
    }

    // if ($user->id) {
        return response()->json([
            'status' => 'success',
            'message' => 'Request Success',
            'data' => $user
        ], 200);
    // }

}

}
