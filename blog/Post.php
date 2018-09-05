<?php

namespace Blog;

use Illuminate\Database\Eloquent\Model;

/**
 * Blog\Post
 *
 * @property int $id
 * @property string $title
 * @property string $content
 * @property int $author_id
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @mixin \Eloquent
 */
class Post extends Model
{
    protected $fillable = [
        'title',
        'content',
    ];

    protected $casts = [
        'published_at' => 'string',
        'updated_at' => 'string',
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
        return $this->belongsToMany(Category::class);
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
