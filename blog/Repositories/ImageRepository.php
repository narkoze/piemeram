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
            'id',
            'name',
            'filename',
            'author_id',
            'size',
            'type',
            'width',
            'height',
            'model',
            'updated_at',
            'created_at',
        ])->with([
            'author:id,name,email',
        ])
        ->orderBy($params['sortBy'], $params['sortDirection']);

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

        return $query;
    }
}
