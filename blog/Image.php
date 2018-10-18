<?php

namespace Blog;

use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    protected $table = 'blog_images';

    protected $fillable = [
        'name',
    ];

    protected $appends = [
        'original',
        'medium',
        'small',
        'dimensions',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function author()
    {
        return $this->belongsTo(\Piemeram\User::class, 'author_id');
    }

    public function getOriginalAttribute()
    {
        return Storage::disk('public')->url("blog/original/$this->filename");
    }

    public function getMediumAttribute()
    {
        return Storage::disk('public')->url("blog/medium/$this->filename");
    }

    public function getSmallAttribute()
    {
        return Storage::disk('public')->url("blog/small/$this->filename");
    }

    public function getDimensionsAttribute()
    {
        if ($this->width and $this->height) {
            return "$this->width × $this->height";
        } else {
            return '-';
        }
    }

    public function json()
    {
        $image = $this->only([
            'id',
            'name',
            'filename',
            'size',
            'type',
            'width',
            'height',
            'model',
            'updated_at',
            'created_at',
        ]);

        $author = $this->author->only([
            'id',
            'name',
        ]);

        return array_merge(
            $image,
            compact('author')
        );
    }
}
