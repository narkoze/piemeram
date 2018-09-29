<?php

namespace Blog\Http\Controllers\Api\Admin;

use Illuminate\Http\Request;
use Blog\Role;

class RoleController extends Controller
{
    protected $rules = [
        'name' => [
            'required',
            'max:255',
        ],
        'description' => 'required'
    ];

    public function index()
    {
        $roles = Role::select([
            'id',
            'name',
            'description',
        ])
        ->withCount('users')
        ->get();

        return response()->json($roles);
    }

    public function store(Request $request)
    {
        if (!in_array(auth()->user()->id, [1,3,5]) and
            Role::where('created_by', auth()->user()->id)->count() > 1
        ) {
            abort(403, 'You can create only 2 roles');
        }

        $rules = $this->rules;
        $rules['name'][] = 'unique:blog_roles,name';
        $request->validate($rules);

        $role = new Role();
        $role->fill($request->all());
        $role->createdBy()->associate(auth()->user());
        $role->save();

        return response()->json($role);
    }

    public function update(Request $request, Role $role)
    {
        if (!in_array(auth()->user()->id, [1,3,5]) and
            $role->createdBy->id != auth()->user()->id
        ) {
            abort(403, 'You can edit only your roles');
        }

        $rules = $this->rules;
        $rules['name'][] = "unique:blog_roles,name,{$role->id}";
        $request->validate($rules);

        $role->fill($request->all());
        $role->save();

        return response()->json($role);
    }

    public function destroy(Role $role)
    {
        if (!in_array(auth()->user()->id, [1,3,5]) and
            $role->createdBy->id != auth()->user()->id
        ) {
            abort(403, 'You can delete only your roles');
        }

        $role->delete();

        return response()->json();
    }
}
