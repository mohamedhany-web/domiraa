<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class TestPermissions extends Command
{
    protected $signature = 'test:permissions {user_id} {permission}';
    protected $description = 'Test if a user has a specific permission';

    public function handle()
    {
        $userId = $this->argument('user_id');
        $permission = $this->argument('permission');

        $user = User::with(['roleModel.permissions', 'directPermissions'])->find($userId);

        if (!$user) {
            $this->error("User not found!");
            return 1;
        }

        $this->info("Testing permission for user: {$user->name} (ID: {$user->id})");
        $this->info("Role: {$user->role}");
        $this->info("Role ID: " . ($user->role_id ?? 'NULL'));
        
        if ($user->roleModel) {
            $this->info("Role Model: {$user->roleModel->name} ({$user->roleModel->display_name})");
            $this->info("Role Permissions Count: " . $user->roleModel->permissions->count());
        } else {
            $this->warn("No role model found!");
        }
        
        $this->info("Direct Permissions Count: " . $user->directPermissions->count());
        
        $hasPermission = $user->hasPermission($permission);
        
        $this->info("\nPermission: {$permission}");
        $this->info("Has Permission: " . ($hasPermission ? 'YES' : 'NO'));
        
        // Show all permissions
        $allPermissions = $user->getAllPermissions();
        $this->info("\nAll User Permissions (" . count($allPermissions) . "):");
        foreach ($allPermissions as $perm) {
            $this->line("  - {$perm}");
        }

        return 0;
    }
}

