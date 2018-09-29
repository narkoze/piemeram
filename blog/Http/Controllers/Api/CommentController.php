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
        if (!in_array(auth()->user()->id, [1,3,5]) and
            Comment::where('author_id', auth()->user()->id)->count() > 1
        ) {
            abort(403, 'You can add only 2 comments');
        }

        $request->validate($this->rules);

        $comment = new Comment();
        $comment->fill($request->all());
        $comment->author()->associate(auth()->user());
        $post->comments()->save($comment);

        return response()->json($comment);
    }

    public function update(Request $request, Post $post, Comment $comment)
    {
        if (!in_array(auth()->user()->id, [1,3,5]) and
            $comment->author->id != auth()->user()->id
        ) {
            abort(403, 'You can edit only your comments');
        }

        $rules = $this->rules;
        $rules['author_id'][] = Rule::in(auth()->user()->id);
        $request->validate($rules);

        $comment->fill($request->all());
        $post->comments()->save($comment);

        return response()->json($comment);
    }

    public function destroy(Post $post, Comment $comment)
    {
        if (!in_array(auth()->user()->id, [1,3,5]) and
            $comment->author->id != auth()->user()->id
        ) {
            abort(403, 'You can delete only your comments');
        }

        $comment->delete();

        return response()->json();
    }
}
