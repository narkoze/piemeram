<?php

namespace Blog\Http\Controllers\Api;

use Illuminate\Http\Request;
use Blog\Post;

class PostController extends Controller
{
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
            'categories:id,name',
        ])
        ->withCount('comments')
        ->whereNotNull('published_at')
        ->orderBy('published_at', 'desc');

        if ($request->filled('categories')) {
            $query->whereHas('categories', function ($query) use ($request) {
                $query->whereIn('id', $request->categories);
            });
        }

        $posts = $query->get();

        return response()->json($posts);
    }
}
