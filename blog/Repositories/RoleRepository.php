<?php

namespace Blog\Repositories;

use Blog\Role;

class RoleRepository
{
    public function params(): array
    {
        return [
            'sortBy' => 'name',
            'sortDirection' => 'asc',
        ];
    }

    public function roles($params = [])
    {
        $params = $params + $this->params();

        $query = Role::select([
            'id',
            'name',
            'description',
        ])
            ->withCount('users')
            ->orderBy($params['sortBy'], $params['sortDirection']);

        return $query;
    }
}
