<?php

namespace Blog\Repositories;

use Blog\Category;

class CategoryRepository
{
    public function params(): array
    {
        return [
            'sortBy' => 'name',
            'sortDirection' => 'asc',
        ];
    }

    public function categories($params = [])
    {
        $params = $params + $this->params();

        $query = Category::select([
            'blog_categories.id',
            'name'
        ])
            ->selectRaw('
                sum(case when blog_posts.published_at is not null then 1 else 0 end) as published_posts_count,
                sum(case when blog_posts.id is not null and blog_posts.published_at is null then 1 else 0 end) as draft_posts_count,
                sum(case when blog_posts.id is not null then 1 else 0 end) as total
            ')
            ->leftJoin('blog_category_post', 'blog_category_post.category_id', '=', 'blog_categories.id')
            ->leftJoin('blog_posts', 'blog_posts.id', '=', 'blog_category_post.post_id')
            ->groupBy([
                'blog_categories.id',
                'name',
            ])->orderBy($params['sortBy'], $params['sortDirection']);

        return $query;
    }
}
