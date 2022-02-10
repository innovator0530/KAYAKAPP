<?php

namespace App\Http\Controllers\Api\v1;

use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;

class UserController extends Controller
{
    //
    public function __construct() {
        $this->middleware('auth:api', ['except' => []]);
    }
    /**
     * Response all data
     *
     * @return \Illuminate\Http\Response
     */
    public function getAll()
    {
        $users = User::all();
        foreach ($users as $user) {
            $user->roles;
        }
        return response()->json([
            'message' => 'success',
            'users' => $users
        ], 200);
    }

    /**
     * Response one data by id
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getById(Request $request, $userId)
    {
        $user = User::find($userId);
        $roleNames = [];
        foreach ($user->roles as $role) {
            array_push($roleNames, $role->name);
        }
        $user->roleNames = $roleNames;
        return response()->json([
            'message' => 'success',
            'user' => $user,
        ], 200);
    }

    /**
     * Create new data
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|between:2,100',
            'email' => 'required|string|email|max:100|unique:users',
            'password' => 'required|string|confirmed|min:6',
            'roles' => 'required',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }

        $user = User::create(array_merge(
                    $validator->validated(),
                    ['password' => bcrypt($request->password)]
                ));

        $roles = Role::whereIn('name', $request->roles)->get();
        $roleIds = [];
        foreach ($roles as $role) {
            array_push($roleIds, $role->id);
        }
        $user->roles()->attach($roleIds);

        return response()->json([
            'message' => 'Usuario Creado Correctamente',
            'user' => $user
        ], 201);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request)
    {
        // Update user
        $request->validate([
            'name' => 'required|string|between:2,100',
            'email' => 'required|string|email|max:100',
            'password' => 'confirmed',
        ]);
        $user = User::find($request->id);
        if ($request->password) {
            $user -> update([
                'password' => bcrypt($request->password),
                'name' => $request->name,
                'email' => $request->email,
            ]);
        } else {
            $user -> update([
                'name' => $request->name,
                'email' => $request->email,
            ]);
        }

        $roles = Role::whereIn('name', $request->roles)->get();
        $roleIds = [];
        foreach ($roles as $role) {
            array_push($roleIds, $role->id);
        }
        $user->roles()->sync($roleIds);

        return response()->json([
            'message' => 'Usuario Actualizado Correctamente',
            'user' => $user
        ], 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete(Request $request, $userId)
    {
        //delete User
        $user = User::find($userId);
        $user -> delete();
        $users = User::all();
        foreach ($users as $user) {
            $user->roles;
        }
        return response()->json([
            'message' => 'Usuario Eliminado Correctamente',
            'users' => $users
        ], 200);
    }
}
