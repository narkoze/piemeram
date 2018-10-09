<?php

namespace Blog\Http\Controllers\Api\Admin;

use Blog\Repositories\RoleRepository;
use Illuminate\Http\Request;
use Blog\Services\Excel;
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

    public function index(RoleRepository $roleRepo, Request $request)
    {
        $params = $request->all() + $roleRepo->params();

        $query = $roleRepo->roles($params);

        if ($request->all) {
            $roles = $query->get();
        } else {
            $roles = $query->paginate(10);
        }

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

    public function excel(RoleRepository $roleRepo, Request $request)
    {
        $params = $request->all() + $roleRepo->params();

        $title = trans('blog/admin/views/blog-admin-view-roles.title');

        $headings = [
            trans('blog/admin/views/blog-admin-view-roles.role') => null,
            trans('blog/admin/views/blog-admin-view-roles.description') => null,
            trans('blog/admin/views/blog-admin-view-roles.usercount') => [
                'format' => 'number',
            ],
        ];

        $data = $roleRepo->roles($params)
            ->get()
            ->transform(function ($role) {
                return [
                    $role->name,
                    $role->description,
                    $role->users_count,
                ];
            });

        return (new Excel($title, $headings, $data))->download("$title.xlsx");
    }
}
