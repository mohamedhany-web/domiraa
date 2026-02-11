<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'display_name',
        'group',
        'description',
        'guard_name',
    ];

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_permissions')
            ->withTimestamps();
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_permissions')
            ->withPivot('granted')
            ->withTimestamps();
    }

    // Get all permissions grouped by their group or name prefix
    public static function getAllGrouped()
    {
        return static::orderBy('group')->orderBy('name')->get()->groupBy(function ($permission) {
            // Use group field if set, otherwise extract from name
            if ($permission->group) {
                return $permission->group;
            }
            $parts = explode('.', $permission->name);
            return $parts[0] ?? 'general';
        });
    }
}

