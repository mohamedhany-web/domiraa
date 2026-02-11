<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'role',
        'role_id',
        'verification_code',
        'verification_code_expires_at',
        'account_status',
        'is_verified',
        'violations_history',
        'violations_count',
        'suspended_until',
        'suspension_reason',
        'suspended_at',
        'suspension_ends_at',
        'language',
        'notification_email',
        'notification_sms',
        'preferred_contact',
        'id_card_path',
        'ownership_proof_path',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_verified' => 'boolean',
            'notification_email' => 'boolean',
            'notification_sms' => 'boolean',
        ];
    }

    // ==================== Relationships ====================

    public function roleModel()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    public function directPermissions()
    {
        return $this->belongsToMany(Permission::class, 'user_permissions')
            ->withPivot('granted')
            ->withTimestamps();
    }

    public function properties()
    {
        return $this->hasMany(Property::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function inquiries()
    {
        return $this->hasMany(Inquiry::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function complaints()
    {
        return $this->hasMany(Complaint::class);
    }

    public function reportedComplaints()
    {
        return $this->hasMany(Complaint::class, 'reported_user_id');
    }

    public function sentMessages()
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    public function receivedMessages()
    {
        return $this->hasMany(Message::class, 'receiver_id');
    }

    public function ratings()
    {
        return $this->hasMany(Rating::class);
    }

    public function wallets()
    {
        return $this->hasMany(Wallet::class);
    }

    public function activityLogs()
    {
        return $this->morphMany(ActivityLog::class, 'causer');
    }

    // ==================== Permission Methods ====================

    /**
     * Check if user has a specific permission
     */
    public function hasPermission(string $permissionName): bool
    {
        // Super admin has all permissions
        if ($this->isSuperAdmin()) {
            return true;
        }

        // Always reload direct permissions from database to get fresh data
        // This ensures we get the latest permissions even if they were just updated
        $directPermission = $this->directPermissions()
            ->where('name', $permissionName)
            ->first();

        if ($directPermission) {
            return (bool) $directPermission->pivot->granted;
        }

        // Check role permissions
        if ($this->role_id) {
            // Load roleModel if not already loaded
            if (!$this->relationLoaded('roleModel')) {
                $this->load('roleModel');
            }
            
            // If roleModel is loaded and exists, check its permissions
            if ($this->roleModel) {
                // Load permissions on role if not loaded
                if (!$this->roleModel->relationLoaded('permissions')) {
                    $this->roleModel->load('permissions');
                }
                return $this->roleModel->hasPermission($permissionName);
            }
        }

        return false;
    }

    /**
     * Check if user has any of the given permissions
     */
    public function hasAnyPermission(array $permissions): bool
    {
        foreach ($permissions as $permission) {
            if ($this->hasPermission($permission)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Check if user has all of the given permissions
     */
    public function hasAllPermissions(array $permissions): bool
    {
        foreach ($permissions as $permission) {
            if (!$this->hasPermission($permission)) {
                return false;
            }
        }
        return true;
    }

    /**
     * Get all permissions for this user (combined from role and direct)
     */
    public function getAllPermissions(): array
    {
        $permissions = [];

        // Load roleModel if not loaded
        if ($this->role_id && !$this->relationLoaded('roleModel')) {
            $this->load('roleModel');
        }

        // Get role permissions
        if ($this->roleModel) {
            // Load permissions on role if not loaded
            if (!$this->roleModel->relationLoaded('permissions')) {
                $this->roleModel->load('permissions');
            }
            
            foreach ($this->roleModel->permissions as $permission) {
                $permissions[$permission->name] = true;
            }
        }

        // Load direct permissions if not loaded
        if (!$this->relationLoaded('directPermissions')) {
            $this->load('directPermissions');
        }

        // Apply direct permissions (can override role permissions)
        foreach ($this->directPermissions as $permission) {
            $permissions[$permission->name] = (bool) $permission->pivot->granted;
        }

        // Filter out denied permissions
        return array_keys(array_filter($permissions));
    }

    /**
     * Give a permission directly to user
     */
    public function givePermission($permission, bool $granted = true)
    {
        if (is_string($permission)) {
            $permission = Permission::where('name', $permission)->first();
        }

        if ($permission) {
            $this->directPermissions()->syncWithoutDetaching([
                $permission->id => ['granted' => $granted]
            ]);
        }

        return $this;
    }

    /**
     * Revoke a permission from user
     */
    public function revokePermission($permission)
    {
        if (is_string($permission)) {
            $permission = Permission::where('name', $permission)->first();
        }

        if ($permission) {
            $this->directPermissions()->detach($permission->id);
        }

        return $this;
    }

    /**
     * Deny a permission for user (override role permission)
     */
    public function denyPermission($permission)
    {
        return $this->givePermission($permission, false);
    }

    /**
     * Sync direct permissions
     */
    public function syncPermissions(array $permissionData)
    {
        $syncData = [];
        foreach ($permissionData as $permissionId => $granted) {
            $syncData[$permissionId] = ['granted' => $granted];
        }
        $this->directPermissions()->sync($syncData);
        return $this;
    }

    /**
     * Assign a role to user
     */
    public function assignRole($role)
    {
        if (is_string($role)) {
            $role = Role::where('name', $role)->first();
        }

        if ($role) {
            $this->role_id = $role->id;
            $this->save();
        }

        return $this;
    }

    // ==================== Role Check Methods ====================

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isSuperAdmin(): bool
    {
        return $this->role === 'admin' && $this->email === 'admin@domiraa.com';
    }

    public function isOwner(): bool
    {
        return $this->role === 'owner';
    }

    public function isTenant(): bool
    {
        return $this->role === 'tenant';
    }

    // ==================== Security Methods ====================

    public function isActive(): bool
    {
        return $this->account_status === 'active';
    }

    public function isSuspended(): bool
    {
        if ($this->account_status !== 'suspended') {
            return false;
        }

        if ($this->suspended_until && now()->gt($this->suspended_until)) {
            // Suspension expired, reactivate
            $this->account_status = 'active';
            $this->suspended_until = null;
            $this->save();
            return false;
        }

        return true;
    }

    public function suspend(\DateTime $until = null, string $reason = null)
    {
        $this->account_status = 'suspended';
        $this->suspended_until = $until;
        
        if ($reason) {
            $violations = $this->violations_history ? json_decode($this->violations_history, true) : [];
            $violations[] = [
                'type' => 'suspension',
                'reason' => $reason,
                'date' => now()->toDateTimeString(),
                'until' => $until?->format('Y-m-d H:i:s'),
            ];
            $this->violations_history = json_encode($violations);
            $this->violations_count = ($this->violations_count ?? 0) + 1;
        }
        
        $this->save();

        ActivityLog::log('suspend', "تم إيقاف حساب المستخدم: {$this->name}", $this);

        return $this;
    }

    public function activate()
    {
        $this->account_status = 'active';
        $this->suspended_until = null;
        $this->save();

        ActivityLog::log('activate', "تم تفعيل حساب المستخدم: {$this->name}", $this);

        return $this;
    }
}
