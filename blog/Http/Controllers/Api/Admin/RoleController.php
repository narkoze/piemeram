<?php

namespace Blog\Http\Controllers\Api\Admin;

use Illuminate\Http\Request;
use Blog\Role;

class RoleController extends Controller
{
    protected $defaultParams = [
        'sortBy' => 'name',
        'sortDirection' => 'asc',
    ];

    protected $rules = [
        'name' => [
            'required',
            'max:255',
        ],
        'description' => 'required'
    ];

    public function index(Request $request)
    {
        $params = $request->all() + $this->defaultParams;

        $query = Role::select([
            'id',
            'name',
            'description',
        ])
            ->withCount('users')
            ->orderBy($params['sortBy'], $params['sortDirection']);

        $roles = $query->get();

        return response()->json(compact('roles', 'params'));
    }

    public function store(Request $request)
    {
        if (!in_array(auth()->user()->id, [2,3,5]) and
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
        if (!in_array(auth()->user()->id, [2,3,5]) and
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
        if (!in_array(auth()->user()->id, [2,3,5]) and
            $role->createdBy->id != auth()->user()->id
        ) {
            abort(403, 'You can delete only your roles');
        }

        $role->delete();

        return response()->json();
    }
}
