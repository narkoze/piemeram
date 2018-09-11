<?php

namespace Blog\Http\Controllers\Api;

use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Blog\Comment;
use Blog\Post;

class CommentController extends Controller
{
    protected $rules = [
        'comment' => [
            'required',
            'max:140',
        ],
    ];

    public function index(Post $post)
    {
        $comments = $post->comments->each(function ($comment) {
            $comment->append('is_edited');
        });

        return response()->json($comments);
    }

    public function store(Request $request, Post $post)
    {
        $request->validate($this->rules);

        $comment = new Comment();
        $comment->fill($request->all());
        $comment->author()->associate(auth()->user());
        $post->comments()->save($comment);

        return response()->json($comment);
    }

    public function update(Request $request, Post $post, Comment $comment)
    {
        $rules = $this->rules;
        $rules['author_id'][] = Rule::in(auth()->user()->id);
        $request->validate($rules);

        $comment->fill($request->all());
        $post->comments()->save($comment);

        return response()->json($comment);
    }

    public function destroy(Post $post, Comment $comment)
    {
        $comment->delete();

        return response()->json();
    }
}
