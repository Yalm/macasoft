<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Role;
use Illuminate\Support\Facades\Validator;

class RoleController extends Controller
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
        $roles = Role::latest()->search($request->get('search'))->paginate(10);
        return response()->json($roles,200);
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
            'slug' => 'required|string|max:191|unique:roles,slug',
            'description' => 'string|min:5'
        ]);

        if($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $role = Role::create([
            'name' => $request->get('name'),
            'slug' => $request->get('slug'),
            'description' => $request->get('description')
        ]);

        return response()->json($role,201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $role = Role::findOrFail($id);
        return response()->json($role,200);
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
            'slug' => 'required|string|max:191|unique:roles,slug,'.$id,
            'description' => 'string|min:5'
        ]);

        if($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $role = Role::findOrFail($id);

        $role->name = $request->get('name');
        $role->slug =  $request->get('slug');
        $role->description =  $request->get('description');

        $role->save();

        return response()->json($role,200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $role = Role::findOrFail($id);

        if($role->users()->count() > 0) {
            return response()->json(['error' => 'Your role is related, can not be eliminated.'],409);
        }

        $role->delete();
		return response()->json($role,204);
    }
}
