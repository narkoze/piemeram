<?php

namespace Blog;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $table = 'blog_roles';

    protected $fillable = [
        'name',
        'description',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function users()
    {
        return $this->hasMany(\Piemeram\User::class, 'blog_role_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function createdBy()
    {
        return $this->belongsTo(\Piemeram\User::class, 'created_by');
    }
}
