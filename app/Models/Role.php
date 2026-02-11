<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'display_name',
        'description',
        'is_system',
        'guard_name',
    ];

    protected $casts = [
        'is_system' => 'boolean',
    ];

    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'role_permissions')
            ->withTimestamps();
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function hasPermission(string $permissionName): bool
    {
        // Load permissions if not already loaded
        if (!$this->relationLoaded('permissions')) {
            $this->load('permissions');
        }
        
        // Check if permission exists in loaded permissions
        if ($this->permissions->isNotEmpty()) {
            return $this->permissions->contains('name', $permissionName);
        }
        
        // Fallback to query if not loaded
        return $this->permissions()->where('name', $permissionName)->exists();
    }

    public function givePermission($permission)
    {
        if (is_string($permission)) {
            $permission = Permission::where('name', $permission)->first();
        }

        if ($permission) {
            $this->permissions()->syncWithoutDetaching([$permission->id]);
        }

        return $this;
    }

    public function revokePermission($permission)
    {
        if (is_string($permission)) {
            $permission = Permission::where('name', $permission)->first();
        }

        if ($permission) {
            $this->permissions()->detach($permission->id);
        }

        return $this;
    }

    public function syncPermissions(array $permissionIds)
    {
        $this->permissions()->sync($permissionIds);
        return $this;
    }
}

