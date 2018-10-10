<?php

namespace Blog\Repositories;

use Blog\Role;

class RoleRepository
{
    public function params(): array
    {
        return [
            'search' => '',
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

        $search = trim($params['search']);
        if ($search) {
            $query->where(function ($query) use ($search) {
                $query->whereRaw("name ILIKE ?", "%$search%")
                    ->orWhereRaw("description ILIKE ?", "%$search%");
            });
        }

        return $query;
    }
}
