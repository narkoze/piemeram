<?php

namespace Blog\Http\Controllers\Api\Admin;

use Illuminate\Http\Request;
use Carbon\Carbon;
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

    public function index(Request $request)
    {
        $query = Post::select([
            'id',
            'title',
            'content',
            'author_id',
            'updated_at',
            'published_at',
        ])
        ->with([
            'author:id,name',
            'categories:id,name'
        ])
        ->orderByRaw('COALESCE(published_at, updated_at) DESC');

        if ($request->filled('categories')) {
            $query->whereHas('categories', function ($query) use ($request) {
                $query->whereIn('id', $request->categories);
            });
        }

        $posts = $query->get();

        return response()->json($posts);
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
