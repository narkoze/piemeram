<?php

namespace Blog\Http\Controllers\Api\Admin;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Blog\Post;
use DB;

class PostController extends Controller
{
    protected $defaultParams = [
        'sortBy' => 'dates',
        'sortDirection' => 'desc',
    ];

    protected $rules = [
        'title' => [
            'required',
            'max:255',
        ],
        'content' => 'required',
    ];

    public function index(Request $request)
    {
        $params = $request->all() + $this->defaultParams;

        $query = Post::select([
            'blog_posts.id',
            'title',
            'content',
            'author_id',
            'blog_posts.updated_at',
            'published_at',

        ])
            ->with([
                'author:id,name',
                'categories:id,name'
            ]);

        if ($request->filled('categories')) {
            $query->whereHas('categories', function ($query) use ($request) {
                $query->whereIn('id', $request->categories);
            });
        }

        if ($params['sortBy'] == 'authors.name') {
            $query->leftJoin('users as authors', 'authors.id', '=', 'blog_posts.author_id');
        }

        if ($params['sortBy'] == 'categories') {
            $categories = DB::table('blog_category_post')
                ->select('post_id')
                ->selectRaw("string_agg(blog_categories.name, '') as categories")
                ->join('blog_categories', 'blog_categories.id', '=', 'blog_category_post.category_id')
                ->groupBy('post_id');

            $query->leftJoinSub($categories, 'categories', function ($query) {
                $query->on('categories.post_id', '=', 'blog_posts.id');
            })->addSelect('categories');
        }

        $sortDirection  = strtolower($params['sortDirection'] == 'asc' ? 'asc' : 'desc');
        if ($params['sortBy'] == 'dates') {
            $query->orderByRaw("COALESCE(published_at, blog_posts.updated_at) $sortDirection");
        } else {
            $query->orderBy($params['sortBy'], $params['sortDirection']);
        }

        $posts = $query->paginate(11);

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
}
