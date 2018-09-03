<?php

namespace Blog\Http\Controllers;

use Illuminate\Http\Request;
use Blog\Post;

class PostController extends Controller
{
    /**
     * @return \Illuminate\View\View
     */
    public function show(Post $post)
    {
        $auth = null;

        if (auth()->check()) {
            $auth = auth()->user()->only([
                'name',
            ]);
        }

        $post->load('author:id,name');
        $post->load('categories:id,name');

        return view('blog.layout', compact('auth', 'post'));
    }
}
