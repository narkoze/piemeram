<?php

namespace Blog\Repositories;

use Carbon\Carbon;
use Blog\Image;

class ImageRepository
{
    public function params(): array
    {
        return [
            'from' => null,
            'to' => null,
            'authorId' => null,
            'search' => '',
            'sortBy' => 'updated_at',
            'sortDirection' => 'desc',
        ];
    }

    public function images($params = [])
    {
        $params = $params + $this->params();

        $query = Image::select([
            'blog_images.id',
            'blog_images.name',
            'filename',
            'author_id',
            'size',
            'type',
            'width',
            'height',
            'model',
            'blog_images.updated_at',
            'blog_images.created_at',
        ])->with([
            'author:id,name,email',
        ]);

        $search = trim($params['search']);
        if ($search) {
            $query->whereRaw("name ILIKE ?", "%$search%");
        }

        if ($params['authorId']) {
            $query->where('author_id', $params['authorId']);
        }

        if ($params['from']) {
            $from = Carbon::parse($params['from'])->toDateString();
            $query->whereRaw('updated_at::date >= ?', $from);
        }

        if ($params['to']) {
            $to = Carbon::parse($params['to'])->toDateString();
            $query->whereRaw('updated_at::date <= ?', $to);
        }

        if ($params['sortBy'] == 'authors.name') {
            $query->leftJoin('users as authors', 'authors.id', '=', 'blog_images.author_id');
        }

        $query->orderBy($params['sortBy'], $params['sortDirection']);

        return $query;
    }
}
