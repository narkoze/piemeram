<?php

namespace Blog;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'blog_categories';

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
        return $this->belongsToMany(Post::class, 'blog_category_post');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function publishedPosts()
    {
        return $this->belongsToMany(Post::class, 'blog_category_post')->whereNotNull('published_at');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function draftPosts()
    {
        return $this->belongsToMany(Post::class, 'blog_category_post')->whereNull('published_at');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function createdBy()
    {
        return $this->belongsTo(\Piemeram\User::class, 'created_by');
    }
}
