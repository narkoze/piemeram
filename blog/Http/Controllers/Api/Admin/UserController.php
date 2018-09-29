<?php

namespace Blog\Http\Controllers\Api\Admin;

use Illuminate\Http\Request;
use Piemeram\User;

class UserController extends Controller
{
    public function index()
    {
        $users = $this->query()->get()->map(function ($user) {
            $parts = explode("@", $user->email);
            $name = implode(array_slice($parts, 0, count($parts) -1), '@');
            $len  = floor(strlen($name) / 2);

            $user->email = substr($name, 0, $len) . str_repeat('*', $len) . "@" . end($parts);
            return $user;
        });

        return response()->json($users);
    }

    public function update(Request $request, User $user)
    {
        $user->blogRole()->associate($request->blogRole);
        $user->save();

        $user = $this->query()->whereId($user->id)->firstOrFail();

        return response()->json($user);
    }

    protected function query()
    {
        return User::select([
            'id',
            'name',
            'email',
            'blog_role_id',
        ])
            ->with('blogRole:id,name,description')
            ->withCount('blogPosts')
            ->withCount('blogComments')
            ->orderBy('name');
    }
}
