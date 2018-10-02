<?php

namespace Blog\Http\Controllers\Api\Admin;

use Illuminate\Http\Request;
use Piemeram\User;

class UserController extends Controller
{
    protected $defaultParams = [
        'sortBy' => 'name',
        'sortDirection' => 'asc',
    ];

    public function index(Request $request)
    {
        $params = $request->all() + $this->defaultParams;

        $users = $this->query($params)->paginate(15);

        $users->setCollection($users->getCollection()
            ->map(function ($user) {
                $parts = explode("@", $user->email);
                $name = implode(array_slice($parts, 0, count($parts) -1), '@');
                $len  = floor(strlen($name) / 2);

                $user->email = substr($name, 0, $len) . str_repeat('*', $len) . "@" . end($parts);
                return $user;
            }
        ));

        return response()->json(compact('users', 'params'));
    }

    public function update(Request $request, User $user)
    {
        $user->blogRole()->associate($request->blogRole);
        $user->save();

        $user = $this->query()
            ->whereId($user->id)
            ->firstOrFail();

        return response()->json(compact('user', 'params'));
    }

    protected function query($params = [])
    {
        $query = User::select([
            'users.id',
            'users.name',
            'email',
            'blog_role_id',
        ])
            ->with('blogRole:id,name,description')
            ->withCount('blogPosts')
            ->withCount('blogComments');

        if (isset($params['sortBy']) and isset($params['sortDirection'])) {
            if ($params['sortBy'] == 'blog_roles.name') {
                $query->leftJoin('blog_roles', 'blog_roles.id', '=', 'users.blog_role_id');
            }

            $sortDirection  = strtolower($params['sortDirection'] == 'asc' ? 'asc' : 'desc');
            if ($params['sortBy'] == 'name') {
                $query->orderByRaw("unaccent(users.name) $sortDirection");
            } else {
                $query->orderBy($params['sortBy'], $sortDirection);
            }
        }

        return $query;
    }
}
