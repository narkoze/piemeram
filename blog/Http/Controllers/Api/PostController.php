<?php

namespace Blog\Http\Controllers\Api;

use Illuminate\Http\Request;
use Blog\Post;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::whereNotNull('published_at')
            ->with([
                'author:id,name',
                'categories:id,name',
            ])
            ->withCount('comments')
            ->orderBy('published_at', 'desc')
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
}
