@extends('layouts.admin')

@section('title', 'إدارة الوحدات')
@section('page-title', 'إدارة الوحدات')

@push('styles')
<style>
    /* Stats Grid - Same as Dashboard */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 1.25rem;
        margin-bottom: 1.25rem;
    }
    
    .stat-card {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
        border: 1px solid rgba(0, 0, 0, 0.05);
        min-height: 140px;
    }
    
    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.12);
    }
    
    .stat-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
    }
    
    .stat-icon {
        width: 50px;
        height: 50px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        flex-shrink: 0;
    }
    
    .stat-icon.orange {
        background: linear-gradient(135deg, #FB923C 0%, #F97316 100%);
        color: white;
    }
    
    .stat-icon.purple {
        background: linear-gradient(135deg, #A78BFA 0%, #8B5CF6 100%);
        color: white;
    }
    
    .stat-icon.green {
        background: linear-gradient(135deg, #8aa69d 0%, #6b8980 100%);
        color: white;
    }
    
    .stat-icon.red {
        background: linear-gradient(135deg, #F87171 0%, #EF4444 100%);
        color: white;
    }
    
    .stat-content {
        flex: 1;
        margin-left: 1rem;
    }
    
    .stat-label {
        color: #6B7280;
        font-weight: 600;
        font-size: 0.875rem;
        margin-bottom: 0.5rem;
    }
    
    .stat-value {
        font-size: 2rem;
        font-weight: 800;
        color: #1F2937;
        margin-bottom: 0.5rem;
        line-height: 1;
    }
    
    /* Section */
    .properties-section {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        border: 1px solid rgba(0, 0, 0, 0.05);
    }
    
    .section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid #F3F4F6;
    }
    
    .section-title {
        font-size: 1.125rem;
        font-weight: 700;
        color: #1F2937;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .filter-bar {
        display: flex;
        gap: 1rem;
        margin-bottom: 1.5rem;
        flex-wrap: wrap;
    }
    
    .filter-select {
        padding: 0.625rem 1rem;
        border: 2px solid #E5E7EB;
        border-radius: 8px;
        font-size: 0.875rem;
        background: white;
    }
    
    .filter-select:focus {
        outline: none;
        border-color: #1d313f;
    }
    
    .table-container {
        overflow-x: auto;
    }
    
    .table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        font-size: 0.875rem;
    }
    
    .table thead {
        background: #F9FAFB;
    }
    
    .table th {
        text-align: right;
        padding: 0.875rem 1rem;
        color: #374151;
        font-weight: 700;
        font-size: 0.8rem;
        border-bottom: 1px solid #E5E7EB;
    }
    
    .table td {
        padding: 0.875rem 1rem;
        border-bottom: 1px solid #F3F4F6;
        color: #4B5563;
        font-weight: 500;
    }
    
    .table tbody tr:hover {
        background: #F9FAFB;
    }
    
    .property-image {
        width: 60px;
        height: 60px;
        border-radius: 8px;
        object-fit: cover;
    }
    
    .badge {
        display: inline-flex;
        align-items: center;
        padding: 0.375rem 0.75rem;
        border-radius: 8px;
        font-size: 0.75rem;
        font-weight: 700;
    }
    
    .badge-pending {
        background: #FEF3C7;
        color: #D97706;
    }
    
    .badge-approved {
        background: #D1FAE5;
        color: #536b63;
    }
    
    .badge-rejected {
        background: #FEE2E2;
        color: #DC2626;
    }
    
    .badge-residential {
        background: #DBEAFE;
        color: #1d313f;
    }
    
    .badge-commercial {
        background: #EDE9FE;
        color: #7C3AED;
    }
    
    .btn-action {
        padding: 0.5rem 1rem;
        border-radius: 8px;
        font-size: 0.75rem;
        font-weight: 700;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.3s ease;
        border: none;
        cursor: pointer;
    }
    
    .btn-view {
        background: #DBEAFE;
        color: #1d313f;
    }
    
    .btn-view:hover {
        background: #BFDBFE;
    }
    
    .btn-edit {
        background: #DBEAFE;
        color: #1d313f;
    }
    
    .btn-edit:hover {
        background: #BFDBFE;
    }
    
    .btn-delete {
        background: #FEE2E2;
        color: #DC2626;
    }
    
    .btn-delete:hover {
        background: #FECACA;
    }
    
    .action-buttons {
        display: flex;
        gap: 0.5rem;
    }
    
    @media (max-width: 1400px) {
        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }
    
    @media (max-width: 768px) {
        .stats-grid {
            grid-template-columns: 1fr;
            gap: 0.875rem;
        }
        
        .stat-card {
            min-height: 110px;
            padding: 1rem;
        }
        
        .stat-value {
            font-size: 1.5rem;
        }
        
        .filter-bar {
            flex-direction: column;
        }
        
        .filter-select {
            width: 100%;
        }
        
        .table-container {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }
        
        .table {
            font-size: 0.75rem;
            min-width: 900px;
        }
        
        .action-buttons {
            flex-direction: column;
        }
    }
</style>
@endpush

@section('content')
<!-- Stats -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-header">
            <div class="stat-content">
                <div class="stat-label">إجمالي الوحدات</div>
                <div class="stat-value">{{ $totalProperties ?? 0 }}</div>
            </div>
            <div class="stat-icon orange">
                <i class="fas fa-building"></i>
            </div>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-header">
            <div class="stat-content">
                <div class="stat-label">وحدات معتمدة</div>
                <div class="stat-value">{{ $approvedProperties ?? 0 }}</div>
            </div>
            <div class="stat-icon green">
                <i class="fas fa-check-circle"></i>
            </div>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-header">
            <div class="stat-content">
                <div class="stat-label">قيد المراجعة</div>
                <div class="stat-value">{{ $pendingProperties ?? 0 }}</div>
            </div>
            <div class="stat-icon purple">
                <i class="fas fa-clock"></i>
            </div>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-header">
            <div class="stat-content">
                <div class="stat-label">مرفوضة</div>
                <div class="stat-value">{{ $rejectedProperties ?? 0 }}</div>
            </div>
            <div class="stat-icon red">
                <i class="fas fa-times-circle"></i>
            </div>
        </div>
    </div>
</div>

<!-- Properties Table -->
<div class="properties-section">
    <div class="section-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
        <h2 class="section-title" style="margin: 0;">
            <i class="fas fa-building"></i>
            قائمة الوحدات
        </h2>
        <a href="{{ route('admin.properties.create') }}" class="btn-primary" style="background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%); color: white; padding: 0.75rem 1.5rem; border-radius: 10px; font-weight: 700; text-decoration: none; display: inline-flex; align-items: center; gap: 0.5rem;">
            <i class="fas fa-plus"></i>
            إضافة وحدة
        </a>
    </div>
    
    <form method="GET" action="{{ route('admin.properties') }}" class="filter-bar">
        <select name="status" class="filter-select" onchange="this.form.submit()">
            <option value="">جميع الحالات</option>
            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>قيد المراجعة</option>
            <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>معتمدة</option>
            <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>مرفوضة</option>
        </select>
        
        <select name="type" class="filter-select" onchange="this.form.submit()">
            <option value="">جميع الأنواع</option>
            <option value="residential" {{ request('type') == 'residential' ? 'selected' : '' }}>سكني</option>
            <option value="commercial" {{ request('type') == 'commercial' ? 'selected' : '' }}>تجاري</option>
        </select>
    </form>
    
    <div class="table-container">
        <table class="table">
            <thead>
                <tr>
                    <th>الصورة</th>
                    <th>الوحدة</th>
                    <th>المالك</th>
                    <th>النوع</th>
                    <th>السعر</th>
                    <th>الحالة</th>
                    <th>التاريخ</th>
                    <th>الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                @forelse($properties as $property)
                <tr>
                    <td>
                        @if($property->images->first())
                        <img src="{{ $property->images->first()->thumbnail_url }}" 
                             alt="{{ $property->address }}" 
                             class="property-image"
                             loading="lazy"
                             onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                        <div class="property-image" style="background: #F3F4F6; display: none; align-items: center; justify-content: center;">
                            <i class="fas fa-image" style="color: #9CA3AF;"></i>
                        </div>
                        @else
                        <div class="property-image" style="background: #F3F4F6; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-image" style="color: #9CA3AF;"></i>
                        </div>
                        @endif
                    </td>
                    <td>
                        <div style="font-weight: 600; color: #1F2937;">{{ Str::limit($property->address, 30) }}</div>
                        <div style="font-size: 0.75rem; color: #6B7280;">كود: {{ $property->code }}</div>
                    </td>
                    <td>{{ $property->user->name }}</td>
                    <td>
                        <span class="badge {{ $property->propertyType->slug ?? '' === 'residential' ? 'badge-residential' : 'badge-commercial' }}">
                            {{ $property->propertyType->name ?? 'غير محدد' }}
                        </span>
                    </td>
                    <td>
                        <div style="font-weight: 600;">{{ number_format($property->price) }}</div>
                        <div style="font-size: 0.75rem; color: #6B7280;">
                            {{ $property->price_type === 'monthly' ? 'شهري' : ($property->price_type === 'yearly' ? 'سنوي' : 'يومي') }}
                        </div>
                    </td>
                    <td>
                        @if($property->admin_status === 'pending')
                            <span class="badge badge-pending">قيد المراجعة</span>
                        @elseif($property->admin_status === 'approved')
                            <span class="badge badge-approved">معتمد</span>
                        @else
                            <span class="badge badge-rejected">مرفوض</span>
                        @endif
                    </td>
                    <td>{{ $property->created_at->format('Y-m-d') }}</td>
                    <td>
                        <div class="action-buttons">
                            <a href="{{ route('admin.properties.review', $property) }}" class="btn-action btn-view">
                                <i class="fas fa-eye"></i>
                                مراجعة
                            </a>
                            <a href="{{ route('admin.properties.edit', $property) }}" class="btn-action btn-edit">
                                <i class="fas fa-edit"></i>
                                تعديل
                            </a>
                            <form method="POST" action="{{ route('admin.properties.destroy', $property) }}" style="display: inline;" onsubmit="return confirm('هل أنت متأكد من حذف هذه الوحدة؟');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-action btn-delete">
                                    <i class="fas fa-trash"></i>
                                    حذف
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" style="text-align: center; padding: 2rem; color: #6B7280;">
                        لا توجد وحدات
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection


