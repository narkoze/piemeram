<?php

namespace Blog\Http\Controllers\Api\Admin;

use Blog\Repositories\PostRepository;
use Illuminate\Http\Request;
use Blog\Services\Excel;
use Piemeram\User;
use Carbon\Carbon;
use Blog\Category;
use Blog\Post;

class PostController extends Controller
{
    protected $rules = [
        'title' => [
            'required',
            'max:255',
        ],
        'content' => 'required',
    ];

    public function index(PostRepository $postRepo, Request $request)
    {
        $params = $request->all() + $postRepo->params();

        $posts = $postRepo->posts($params)->paginate(20);

        return response()->json(compact('posts', 'params'));
    }

    public function store(Request $request)
    {
        if (!in_array(auth()->user()->id, [2,3,5]) and
            Post::where('author_id', auth()->user()->id)->count() > 1
        ) {
            abort(403, 'You can create only 2 posts');
        }

        $rules = $this->rules;
        $rules['title'][] = 'unique:blog_posts,title';
        $request->validate($rules);

        $post = new Post();
        $post->author()->associate($request->user());

        $this->save($request, $post);

        return response()->json($post->json());
    }

    public function update(Request $request, Post $post)
    {
        if (!in_array(auth()->user()->id, [2,3,5]) and
            $post->author->id != auth()->user()->id
        ) {
            abort(403, 'You can edit only your posts');
        }

        $rules = $this->rules;
        $rules['title'][] = "unique:blog_posts,title,{$post->id}";
        $request->validate($rules);

        $this->save($request, $post);

        return response()->json($post->json());
    }

    public function destroy(Post $post)
    {
        if (!in_array(auth()->user()->id, [2,3,5]) and
            $post->author->id != auth()->user()->id
        ) {
            abort(403, 'You can delete only your posts');
        }

        $post->delete();

        return response()->json();
    }

    protected function save($request, $post)
    {
        $post->fill($request->all());

        if ($request->draft) {
            $post->published_at = null;
        } else if (!$post->published_at) {
            $post->published_at = Carbon::now();
        }

        $post->save();

        $post->categories()->sync($request->categories);
    }

    public function excel(PostRepository $postRepo, Request $request)
    {
        $params = $request->all() + $postRepo->params();

        $title = trans('blog/admin/views/blog-admin-view-posts.title');

        $data = $postRepo->posts()
            ->get()
            ->transform(function ($post) {
                return [
                    $post->title,
                    implode(', ', $post->categories->pluck('name')->toArray()),
                    $post->author->name,
                    $post->published_at,
                    $post->updated_at,
                ];
            });

        $headings = [
            trans('blog/admin/views/blog-admin-view-posts.posttitle') => null,
            trans('blog/admin/views/blog-admin-view-posts.categories') => [
                'filter' => [
                    'contains' => Category::whereIn('id', $params['categories'])
                        ->pluck('name')
                        ->toArray(),
                ],
            ],
            trans('blog/admin/views/blog-admin-view-posts.author') => [
                'filter' => [
                    'equal' => User::whereId($params['authorId'])
                        ->first(['name'])
                        ->name ?? '',
                ],
            ],
            trans('blog/admin/views/blog-admin-view-posts.published') => [
                'format' => 'date',
            ],
            trans('blog/admin/views/blog-admin-view-posts.saved') => [
                'format' => 'date',
            ],
        ];

        return (new Excel($title, $headings, $data))->download("$title.xlsx");
    }
}
