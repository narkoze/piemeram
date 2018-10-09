<?php

namespace Blog\Http\Controllers\Api\Admin;

use Blog\Repositories\UserRepository;
use Illuminate\Http\Request;
use Blog\Services\Excel;
use Piemeram\User;

class UserController extends Controller
{
    public function index(UserRepository $userRepo, Request $request)
    {
        $params = $request->all() + $userRepo->params();

        if ($request->all) {
            $users = $userRepo->users($params)->get();
        } else {
            $users = $userRepo->users($params)->paginate(20);
        }

        return response()->json(compact('users', 'params'));
    }

    public function update(
        UserRepository $userRepo,
        Request $request,
        User $user
    ) {
        $user->blogRole()->associate($request->blogRole);
        $user->save();

        $user = $userRepo->users()->whereId($user->id)->firstOrFail();

        return response()->json(compact('user', 'params'));
    }

    public function excel(UserRepository $userRepo, Request $request)
    {
        $title = trans('blog/admin/views/blog-admin-view-users.title');

        $headings = [
            trans('blog/admin/views/blog-admin-view-users.name') => null,
            trans('blog/admin/views/blog-admin-view-users.email') => null,
            trans('blog/admin/views/blog-admin-view-users.role') => null,
            trans('blog/admin/views/blog-admin-view-users.posts') => [
                'format' => 'number',
            ],
            trans('blog/admin/views/blog-admin-view-users.comments') => [
                'format' => 'number',
            ],
        ];

        $data = $userRepo->users($request->all() + $userRepo->params())
            ->get()
            ->transform(function ($user) {
                return [
                    $user->name,
                    $user->emailMasked,
                    $user->blogRole->name ?? trans('blog/admin/views/blog-admin-view-users.user'),
                    $user->blog_posts_count,
                    $user->blog_comments_count,
                ];
            });

        return (new Excel($title, $headings, $data))->download("$title.xlsx");
    }
}
