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

    public function index()
    {
        $posts = Post::with('author:id,name')
            ->orderByRaw('COALESCE(published_at, updated_at) DESC')
            ->get([
                'id',
                'title',
                'content',
                'author_id',
                'updated_at',
                'published_at',
            ]);

        return response()->json($posts);
    }

    public function store(Request $request)
    {
        $rules = $this->rules;
        $rules['title'][] = 'unique:posts,title';
        $request->validate($rules);

        $post = new Post();
        $post->author()->associate($request->user());

        $this->save($request, $post);

        return response()->json($post->json());
    }

    public function update(Request $request, Post $post)
    {
        $rules = $this->rules;
        $rules['title'][] = "unique:posts,title,{$post->id}";
        $request->validate($rules);

        $this->save($request, $post);

        return response()->json($post->json());
    }

    public function destroy(Post $post)
    {
        $post->delete();

        return response()->json();
    }

    protected function save($request, $post)
    {
        $post->fill($request->all());

        $post->published_at = $request->draft ? null :  Carbon::now();

        $post->save();
    }
}