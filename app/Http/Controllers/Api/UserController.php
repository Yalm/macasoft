<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $users = User::latest()->search($request->get('search'))->paginate(7);
        $users->each(function($user){
            $user->role;
        });

        return response()->json($users,200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:191',
            'email' => 'required|string|email|max:191|unique:users',
            'password' => 'required|string|min:6|max:10',
            'role_id' => 'required|numeric|exists:roles,id',
            'avatar' => 'required|image'
        ]);

        if($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $path = $request->file('avatar')->store('avatars','public_folder');

        $user = User::create([
            'name' => $request->get('name'),
            'email' => $request->get('email'),
            'password' => Hash::make($request->get('password')),
            'role_id' => $request->get('role_id'),
            'avatar' => $path
        ]);

        return response()->json($user,201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::findOrFail($id);
        $user->avatar = $user->avatar ? url("uploads/$user->avatar") : null;
        return response()->json($user,201);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:191',
            'email' => 'required|string|email|max:191|unique:users,email,'.$id,
            'password' => 'string|max:10',
            'role_id' => 'required|numeric|exists:roles,id',
            'avatar' => 'image'
        ]);

        if($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $user = User::findOrFail($id);

        if($request->hasFile('avatar')) {
            Storage::disk('public_folder')->delete($user->avatar);
            $user->avatar = Storage::disk('public_folder')->put('avatars', $request->file('avatar'));
        }

        $user->name = $request->get('name');
        $user->email = $request->get('email');
        $user->role_id = $request->get('role_id');

        if($request->get('password')) {
            $user->password = Hash::make($request->get('password'));
        }

        $user->save();
        return response()->json($user);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        Storage::disk('public_folder')->delete($user->avatar);
        $user->delete();

        return response()->json(null, 204);
    }

    public function byRole(Request $request)
    {
        $users = User::byRole($request->get('role'))->paginate(10);

        return response()->json($users);
    }
}
