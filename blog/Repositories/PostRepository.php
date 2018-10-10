<?php

namespace Blog\Repositories;

use Carbon\Carbon;
use Blog\Post;
use DB;

class PostRepository
{
    public function params(): array
    {
        return [
            'categories' => [],
            'authorId' => null,
            'from' => null,
            'to' => null,
            'status' => null,
            'search' => '',
            'sortBy' => 'dates',
            'sortDirection' => 'desc',
        ];
    }

    public function posts($params = [])
    {
        $params = $params + $this->params();

        $query = Post::select([
            'blog_posts.id',
            'title',
            'content',
            'author_id',
            'blog_posts.updated_at',
            'published_at',

        ])
            ->with([
                'author:id,name,email',
                'categories:id,name'
            ]);

        if ($params['categories']) {
            $categories = $params['categories'];

            $query->whereHas('categories', function ($query) use ($categories) {
                $query->whereIn('id', $categories);
            });
        }

        if ($params['authorId']) {
            $authorId = $params['authorId'];

            $query->whereHas('author', function ($query) use ($authorId) {
                $query->where('id', $authorId);
            });
        }

        $search = trim($params['search']);
        if ($search) {
            $query->where(function ($query) use ($search) {
                $query->whereRaw("title ILIKE ?", "%$search%")
                    ->orWhereRaw("content ILIKE ?", "%$search%");
            });

        }

        switch ($params['status']) {
            case 'published':
                $query->whereNotNull('published_at');
                break;
            case 'draft':
                $query->whereNull('published_at');
                break;
        }

        if ($params['from']) {
            $from = Carbon::parse($params['from'])->toDateString();
            $query->whereRaw('COALESCE(published_at::date, blog_posts.updated_at::date) >= ?', $from);
        }

        if ($params['to']) {
            $to = Carbon::parse($params['from'])->toDateString();
            $query->whereRaw('COALESCE(published_at::date, blog_posts.updated_at::date) <= ?', $to);
        }

        if ($params['sortBy'] == 'authors.name') {
            $query->leftJoin('users as authors', 'authors.id', '=', 'blog_posts.author_id');
        }

        if ($params['sortBy'] == 'categories_abc') {
            $categories = DB::table('blog_category_post')
                ->select('post_id')
                ->selectRaw("string_agg(blog_categories.name, ', ') as categories_abc")
                ->join('blog_categories', 'blog_categories.id', '=', 'blog_category_post.category_id')
                ->groupBy('post_id');

            $query->leftJoinSub($categories, 'categories', function ($query) {
                $query->on('categories.post_id', '=', 'blog_posts.id');
            })->addSelect('categories_abc');
        }

        $sortDirection  = strtolower($params['sortDirection'] == 'asc' ? 'asc' : 'desc');
        if ($params['sortBy'] == 'dates') {
            $query->orderByRaw("COALESCE(published_at, blog_posts.updated_at) $sortDirection");
        } else {
            $query->orderBy($params['sortBy'], $params['sortDirection']);
        }

        return $query;
    }
}
