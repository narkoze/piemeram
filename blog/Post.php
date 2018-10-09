<?php

namespace Blog;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $table = 'blog_posts';

    protected $hidden = [
        'pivot'
    ];

    protected $fillable = [
        'title',
        'content',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function author()
    {
        return $this->belongsTo(\Piemeram\User::class, 'author_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'blog_category_post');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function comments()
    {
        return $this->hasMany(Comment::class)
            ->select([
                'id',
                'author_id',
                'comment',
                'updated_at',
                'created_at',
            ])
            ->with('author:id,name')
            ->orderBy('created_at', 'desc');
    }

    public function json()
    {
        $post = $this->only([
            'id',
            'title',
            'content',
            'updated_at',
            'published_at',
        ]);

        $author = $this->author->only([
            'id',
            'name',
        ]);

        return array_merge(
            $post,
            compact('author')
        );
    }
}
