<?php

namespace Blog\Repositories;

use Piemeram\User;

class UserRepository
{
    public function params(): array
    {
        return [
            'search' => '',
            'limit' => null,
            'sortBy' => 'name',
            'sortDirection' => 'asc',
        ];
    }

    public function users($params = [])
    {
        $params = $params + $this->params();

        $query = User::select([
            'users.id',
            'users.name',
            'users.email',
            'blog_role_id',
        ])
            ->with('blogRole:id,name,description')
            ->withCount('blogPosts')
            ->withCount('blogComments');


        $search = trim($params['search']);
        if ($search) {
            $query->whereRaw("unaccent(users.name) ILIKE unaccent(?)", "%$search%");
        }

        if ($params['sortBy'] == 'blog_roles.name') {
            $query->leftJoin('blog_roles', 'blog_roles.id', '=', 'users.blog_role_id');
        }

        $sortDirection  = strtolower($params['sortDirection'] == 'asc' ? 'asc' : 'desc');
        if ($params['sortBy'] == 'name') {
            $query->orderByRaw("unaccent(users.name) $sortDirection");
        } else {
            $query->orderBy($params['sortBy'], $sortDirection);
        }

        if ($params['limit']) {
            $query->limit($params['limit']);
        }

        return $query;
    }
}
