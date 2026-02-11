@extends('layouts.admin')

@section('title', 'تعديل المستخدم: ' . $user->name)

@push('styles')
<style>
    .page-header {
        margin-bottom: 2rem;
    }
    
    .page-title {
        font-size: 1.75rem;
        font-weight: 800;
        color: #1F2937;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }
    
    .page-title i {
        color: var(--primary);
    }
    
    .form-card {
        background: white;
        border-radius: 16px;
        padding: 2rem;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        border: 1px solid #E5E7EB;
    }
    
    .form-section {
        margin-bottom: 2rem;
    }
    
    .form-section-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: #1F2937;
        margin-bottom: 1.5rem;
        padding-bottom: 0.75rem;
        border-bottom: 2px solid #E5E7EB;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .form-section-title i {
        color: var(--primary);
    }
    
    .form-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1.5rem;
    }
    
    .form-group {
        margin-bottom: 1.5rem;
    }
    
    .form-label {
        display: block;
        font-weight: 600;
        color: #374151;
        margin-bottom: 0.5rem;
    }
    
    .form-input, .form-select {
        width: 100%;
        padding: 0.875rem 1rem;
        border: 2px solid #E5E7EB;
        border-radius: 10px;
        font-size: 1rem;
        transition: all 0.3s ease;
    }
    
    .form-input:focus, .form-select:focus {
        outline: none;
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(29, 49, 63, 0.1);
    }
    
    .permissions-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
        gap: 1.5rem;
    }
    
    .permission-group {
        background: #F9FAFB;
        border-radius: 12px;
        padding: 1.25rem;
        border: 1px solid #E5E7EB;
    }
    
    .permission-group-title {
        font-weight: 700;
        color: var(--primary);
        margin-bottom: 1rem;
        padding-bottom: 0.5rem;
        border-bottom: 1px solid #E5E7EB;
        text-transform: capitalize;
    }
    
    .permission-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0.75rem;
        border-radius: 8px;
        margin-bottom: 0.5rem;
        transition: all 0.2s ease;
    }
    
    .permission-item:hover {
        background: white;
    }
    
    .permission-item.from-role {
        background: rgba(107, 137, 128, 0.1);
        border: 1px dashed var(--secondary);
    }
    
    .permission-item.granted {
        background: #D1FAE5;
        border: 1px solid #10B981;
    }
    
    .permission-item.denied {
        background: #FEE2E2;
        border: 1px solid #EF4444;
    }
    
    .permission-info {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .permission-label {
        font-size: 0.9rem;
        color: #374151;
        font-weight: 500;
    }
    
    .permission-badge {
        font-size: 0.65rem;
        padding: 0.15rem 0.5rem;
        border-radius: 10px;
        font-weight: 600;
    }
    
    .permission-badge.role {
        background: rgba(107, 137, 128, 0.2);
        color: var(--secondary-dark);
    }
    
    .permission-controls {
        display: flex;
        gap: 0.25rem;
    }
    
    .permission-btn {
        padding: 0.35rem 0.6rem;
        border-radius: 6px;
        font-size: 0.75rem;
        font-weight: 600;
        border: none;
        cursor: pointer;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        gap: 0.25rem;
    }
    
    .permission-btn.grant {
        background: #E5E7EB;
        color: #374151;
    }
    
    .permission-btn.grant.active {
        background: #10B981;
        color: white;
    }
    
    .permission-btn.default {
        background: #E5E7EB;
        color: #374151;
    }
    
    .permission-btn.default.active {
        background: var(--primary);
        color: white;
    }
    
    .permission-btn.deny {
        background: #E5E7EB;
        color: #374151;
    }
    
    .permission-btn.deny.active {
        background: #EF4444;
        color: white;
    }
    
    .permission-btn:hover {
        transform: scale(1.05);
    }
    
    .current-permissions {
        background: #F0FDF4;
        border: 1px solid #BBF7D0;
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
    }
    
    .current-permissions h4 {
        font-weight: 700;
        color: #166534;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .current-permissions-list {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
    }
    
    .current-perm-tag {
        background: white;
        padding: 0.35rem 0.75rem;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
        color: #166534;
        border: 1px solid #BBF7D0;
    }
    
    .form-actions {
        display: flex;
        gap: 1rem;
        justify-content: flex-end;
        margin-top: 2rem;
        padding-top: 1.5rem;
        border-top: 1px solid #E5E7EB;
    }
    
    .btn-submit {
        background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
        color: white;
        padding: 0.875rem 2rem;
        border-radius: 10px;
        font-weight: 700;
        border: none;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.3s ease;
    }
    
    .btn-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(29, 49, 63, 0.3);
    }
    
    .btn-cancel {
        background: #F3F4F6;
        color: #374151;
        padding: 0.875rem 2rem;
        border-radius: 10px;
        font-weight: 700;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .btn-cancel:hover {
        background: #E5E7EB;
    }
    
    .legend {
        display: flex;
        gap: 1.5rem;
        flex-wrap: wrap;
        margin-bottom: 1.5rem;
        padding: 1rem;
        background: #F9FAFB;
        border-radius: 8px;
    }
    
    .legend-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.85rem;
        color: #374151;
    }
    
    .legend-color {
        width: 16px;
        height: 16px;
        border-radius: 4px;
    }
    
    .legend-color.role {
        background: rgba(107, 137, 128, 0.3);
        border: 1px dashed var(--secondary);
    }
    
    .legend-color.granted {
        background: #D1FAE5;
        border: 1px solid #10B981;
    }
    
    .legend-color.denied {
        background: #FEE2E2;
        border: 1px solid #EF4444;
    }
    
    @media (max-width: 768px) {
        .form-grid {
            grid-template-columns: 1fr;
        }
        
        .permissions-grid {
            grid-template-columns: 1fr;
        }
        
        .form-actions {
            flex-direction: column;
        }
        
        .btn-submit, .btn-cancel {
            width: 100%;
            justify-content: center;
        }
        
        .permission-item {
            flex-direction: column;
            align-items: flex-start;
            gap: 0.75rem;
        }
        
        .permission-controls {
            width: 100%;
            justify-content: flex-end;
        }
    }
</style>
@endpush

@section('content')
<div class="page-header">
    <h1 class="page-title">
        <i class="fas fa-user-edit"></i>
        تعديل المستخدم: {{ $user->name }}
    </h1>
</div>

<form action="{{ route('admin.users-permissions.update', $user) }}" method="POST" id="editForm">
    @csrf
    @method('PUT')
    
    <div class="form-card">
        <div class="form-section">
            <h2 class="form-section-title">
                <i class="fas fa-user"></i>
                المعلومات الأساسية
            </h2>
            
            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label">الاسم الكامل *</label>
                    <input type="text" name="name" class="form-input" value="{{ old('name', $user->name) }}" required>
                    @error('name')
                        <p style="color: #DC2626; font-size: 0.875rem; margin-top: 0.5rem;">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label class="form-label">البريد الإلكتروني *</label>
                    <input type="email" name="email" class="form-input" value="{{ old('email', $user->email) }}" required>
                    @error('email')
                        <p style="color: #DC2626; font-size: 0.875rem; margin-top: 0.5rem;">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label class="form-label">رقم الهاتف *</label>
                    <input type="text" name="phone" class="form-input" value="{{ old('phone', $user->phone) }}" required>
                    @error('phone')
                        <p style="color: #DC2626; font-size: 0.875rem; margin-top: 0.5rem;">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label class="form-label">نوع الحساب *</label>
                    <select name="role" class="form-select" required {{ $user->isSuperAdmin() ? 'disabled' : '' }}>
                        <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>مدير</option>
                        <option value="owner" {{ old('role', $user->role) === 'owner' ? 'selected' : '' }}>مؤجر</option>
                        <option value="tenant" {{ old('role', $user->role) === 'tenant' ? 'selected' : '' }}>مستأجر</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label class="form-label">الدور</label>
                    <select name="role_id" class="form-select" id="roleSelect" onchange="updateRolePermissions()">
                        <option value="">بدون دور محدد</option>
                        @foreach($roles as $role)
                        <option value="{{ $role->id }}" 
                                data-permissions="{{ $role->permissions->pluck('id')->toJson() }}"
                                {{ old('role_id', $user->role_id) == $role->id ? 'selected' : '' }}>
                            {{ $role->display_name ?? $role->name }}
                        </option>
                        @endforeach
                    </select>
                    <p style="font-size: 0.75rem; color: #6B7280; margin-top: 0.5rem;">اختيار دور سيمنح المستخدم جميع صلاحيات هذا الدور</p>
                </div>
                
                <div class="form-group">
                    <label class="form-label">حالة الحساب *</label>
                    <select name="account_status" class="form-select" required {{ $user->isSuperAdmin() ? 'disabled' : '' }}>
                        <option value="active" {{ old('account_status', $user->account_status) === 'active' ? 'selected' : '' }}>نشط</option>
                        <option value="pending" {{ old('account_status', $user->account_status) === 'pending' ? 'selected' : '' }}>قيد المراجعة</option>
                        <option value="suspended" {{ old('account_status', $user->account_status) === 'suspended' ? 'selected' : '' }}>موقوف</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label class="form-label">كلمة المرور الجديدة</label>
                    <input type="password" name="password" class="form-input" minlength="8">
                    <p style="font-size: 0.75rem; color: #6B7280; margin-top: 0.5rem;">اتركها فارغة إذا لم ترد تغييرها</p>
                    @error('password')
                        <p style="color: #DC2626; font-size: 0.875rem; margin-top: 0.5rem;">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label class="form-label">تأكيد كلمة المرور</label>
                    <input type="password" name="password_confirmation" class="form-input" minlength="8">
                </div>
            </div>
        </div>
        
        <!-- Current Effective Permissions -->
        @if(count($effectivePermissions) > 0)
        <div class="current-permissions">
            <h4>
                <i class="fas fa-check-circle"></i>
                الصلاحيات الفعلية الحالية ({{ count($effectivePermissions) }})
            </h4>
            <div class="current-permissions-list">
                @foreach($effectivePermissions as $permName)
                    @php
                        $perm = \App\Models\Permission::where('name', $permName)->first();
                    @endphp
                    @if($perm)
                    <span class="current-perm-tag">{{ $perm->display_name ?? $perm->name }}</span>
                    @endif
                @endforeach
            </div>
        </div>
        @endif
        
        <div class="form-section">
            <h2 class="form-section-title">
                <i class="fas fa-key"></i>
                إدارة الصلاحيات
            </h2>
            <p style="color: #6B7280; margin-bottom: 1rem; font-size: 0.9rem;">
                يمكنك تخصيص صلاحيات المستخدم. الصلاحيات المباشرة تتجاوز صلاحيات الدور.
            </p>
            
            <div class="legend">
                <div class="legend-item">
                    <div class="legend-color role"></div>
                    <span>من الدور</span>
                </div>
                <div class="legend-item">
                    <div class="legend-color granted"></div>
                    <span>ممنوح مباشرة</span>
                </div>
                <div class="legend-item">
                    <div class="legend-color denied"></div>
                    <span>ممنوع مباشرة</span>
                </div>
            </div>
            
            <div class="permissions-grid">
                @forelse($permissions as $group => $groupPermissions)
                <div class="permission-group">
                    <h3 class="permission-group-title">
                        <i class="fas fa-folder"></i>
                        {{ ucfirst($group) ?? 'عام' }}
                    </h3>
                    @foreach($groupPermissions as $permission)
                    @php
                        $isFromRole = in_array($permission->id, $rolePermissionIds);
                        $directValue = $userDirectPermissions[$permission->id] ?? null;
                        $itemClass = '';
                        if ($directValue === true) {
                            $itemClass = 'granted';
                        } elseif ($directValue === false) {
                            $itemClass = 'denied';
                        } elseif ($isFromRole) {
                            $itemClass = 'from-role';
                        }
                    @endphp
                    <div class="permission-item {{ $itemClass }}" id="perm-item-{{ $permission->id }}">
                        <div class="permission-info">
                            <span class="permission-label">{{ $permission->display_name ?? $permission->name }}</span>
                            @if($isFromRole && $directValue === null)
                            <span class="permission-badge role">من الدور</span>
                            @endif
                        </div>
                        <div class="permission-controls">
                            <button type="button" 
                                    class="permission-btn grant {{ $directValue === true ? 'active' : '' }}"
                                    onclick="setPermission({{ $permission->id }}, 'grant')"
                                    title="منح الصلاحية">
                                <i class="fas fa-check"></i>
                            </button>
                            <button type="button" 
                                    class="permission-btn default {{ $directValue === null ? 'active' : '' }}"
                                    onclick="setPermission({{ $permission->id }}, 'default')"
                                    title="افتراضي (من الدور)">
                                <i class="fas fa-minus"></i>
                            </button>
                            <button type="button" 
                                    class="permission-btn deny {{ $directValue === false ? 'active' : '' }}"
                                    onclick="setPermission({{ $permission->id }}, 'deny')"
                                    title="منع الصلاحية">
                                <i class="fas fa-ban"></i>
                            </button>
                        </div>
                    </div>
                    @endforeach
                </div>
                @empty
                <p style="color: #6B7280;">لا توجد صلاحيات متاحة.</p>
                @endforelse
            </div>
            
            <!-- Hidden inputs for permissions -->
            <div id="permissions-container"></div>
        </div>
        
        <div class="form-actions">
            <a href="{{ route('admin.users-permissions.index') }}" class="btn-cancel">
                <i class="fas fa-times"></i>
                إلغاء
            </a>
            <button type="submit" class="btn-submit">
                <i class="fas fa-save"></i>
                حفظ التعديلات
            </button>
        </div>
    </div>
</form>

<script>
// Store permission states: true = grant, false = deny, null/undefined = default
let permissionsState = {};
let rolePermissions = @json($rolePermissionIds);

// Initialize from existing direct permissions
@foreach($userDirectPermissions as $permId => $granted)
permissionsState[{{ $permId }}] = {{ $granted ? 'true' : 'false' }};
@endforeach

function setPermission(permissionId, action) {
    const item = document.getElementById('perm-item-' + permissionId);
    const buttons = item.querySelectorAll('.permission-btn');
    
    // Remove all active classes
    buttons.forEach(btn => btn.classList.remove('active'));
    
    // Remove item classes
    item.classList.remove('granted', 'denied', 'from-role');
    
    if (action === 'grant') {
        permissionsState[permissionId] = true;
        item.classList.add('granted');
        buttons[0].classList.add('active');
    } else if (action === 'deny') {
        permissionsState[permissionId] = false;
        item.classList.add('denied');
        buttons[2].classList.add('active');
    } else {
        // default - remove from direct permissions
        delete permissionsState[permissionId];
        buttons[1].classList.add('active');
        
        // Check if it's from role
        if (rolePermissions.includes(permissionId)) {
            item.classList.add('from-role');
        }
    }
    
    updateHiddenInputs();
}

function updateHiddenInputs() {
    const container = document.getElementById('permissions-container');
    container.innerHTML = '';
    
    for (const [permId, granted] of Object.entries(permissionsState)) {
        const input = document.createElement('input');
        input.type = 'hidden';
        
        if (granted === true) {
            input.name = 'permissions[]';
            input.value = permId;
        } else if (granted === false) {
            input.name = 'denied_permissions[]';
            input.value = permId;
        }
        
        container.appendChild(input);
    }
}

function updateRolePermissions() {
    const select = document.getElementById('roleSelect');
    const selectedOption = select.options[select.selectedIndex];
    
    if (selectedOption && selectedOption.dataset.permissions) {
        rolePermissions = JSON.parse(selectedOption.dataset.permissions);
    } else {
        rolePermissions = [];
    }
    
    // Update UI for permissions that are now from role
    document.querySelectorAll('.permission-item').forEach(item => {
        const permId = parseInt(item.id.replace('perm-item-', ''));
        
        // Only update if not directly set
        if (permissionsState[permId] === undefined) {
            item.classList.remove('from-role');
            if (rolePermissions.includes(permId)) {
                item.classList.add('from-role');
            }
        }
    });
}

// Initialize hidden inputs on page load
document.addEventListener('DOMContentLoaded', function() {
    updateHiddenInputs();
    updateRolePermissions();
});
</script>
@endsection
