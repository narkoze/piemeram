<?php

namespace Blog;

use Illuminate\Database\Eloquent\Model;


class Category extends Model
{
    protected $fillable = [
        'name',
    ];

    protected $hidden = [
        'pivot'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function posts()
    {
        return $this->belongsToMany(Post::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function publishedPosts()
    {
        return $this->belongsToMany(Post::class)->whereNotNull('published_at');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function draftPosts()
    {
        return $this->belongsToMany(Post::class)->whereNull('published_at');
    }
}
