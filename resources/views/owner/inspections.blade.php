@extends('layouts.owner')

@section('title', 'طلبات المعاينة - منصة دوميرا')
@section('page-title', 'طلبات المعاينة')

@push('styles')
<style>
    .filter-tabs {
        display: flex;
        gap: 0.5rem;
        margin-bottom: 2rem;
        flex-wrap: wrap;
    }
    
    .filter-tab {
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        border: 2px solid #E5E7EB;
        background: white;
        color: #6B7280;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.3s ease;
    }
    
    .filter-tab:hover,
    .filter-tab.active {
        background: linear-gradient(135deg, #1d313f 0%, #6b8980 100%);
        color: white;
        border-color: transparent;
    }
    
    .inspection-card {
        background: white;
        border-radius: 16px;
        padding: 1.5rem;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        margin-bottom: 1.5rem;
        border: 1px solid rgba(0, 0, 0, 0.05);
    }
</style>
@endpush

@section('content')
<!-- Stats -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
    <div style="background: white; border-radius: 16px; padding: 1.5rem; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);">
        <div style="font-size: 2rem; font-weight: 800; color: #F59E0B; margin-bottom: 0.5rem;">{{ $stats['pending'] ?? 0 }}</div>
        <div style="color: #6B7280; font-weight: 600;">قيد الانتظار</div>
    </div>
    <div style="background: white; border-radius: 16px; padding: 1.5rem; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);">
        <div style="font-size: 2rem; font-weight: 800; color: #6b8980; margin-bottom: 0.5rem;">{{ $stats['confirmed'] }}</div>
        <div style="color: #6B7280; font-weight: 600;">مؤكدة</div>
    </div>
    <div style="background: white; border-radius: 16px; padding: 1.5rem; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);">
        <div style="font-size: 2rem; font-weight: 800; color: #2a4456; margin-bottom: 0.5rem;">{{ $stats['completed'] }}</div>
        <div style="color: #6B7280; font-weight: 600;">مكتملة</div>
    </div>
</div>

<!-- Filter Tabs -->
<div class="filter-tabs">
    <a href="{{ route('owner.inspections') }}" class="filter-tab {{ !request('status') ? 'active' : '' }}">الكل</a>
    <a href="{{ route('owner.inspections', ['status' => 'pending']) }}" class="filter-tab {{ request('status') == 'pending' ? 'active' : '' }}">قيد الانتظار</a>
    <a href="{{ route('owner.inspections', ['status' => 'confirmed']) }}" class="filter-tab {{ request('status') == 'confirmed' ? 'active' : '' }}">مؤكدة</a>
    <a href="{{ route('owner.inspections', ['status' => 'completed']) }}" class="filter-tab {{ request('status') == 'completed' ? 'active' : '' }}">مكتملة</a>
</div>

<!-- Inspections List -->
@if($inspections->count() > 0)
@foreach($inspections as $inspection)
<div class="inspection-card">
    <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 1rem; flex-wrap: wrap; gap: 1rem;">
        <div style="flex: 1;">
            <h3 style="font-size: 1.25rem; font-weight: 700; color: #1F2937; margin-bottom: 0.5rem;">
                {{ $inspection->property->address }}
            </h3>
            <div style="display: flex; gap: 1.5rem; flex-wrap: wrap; color: #6B7280; font-size: 0.9rem;">
                <span><i class="fas fa-user ml-1"></i> مستأجر مهتم</span>
                <span><i class="fas fa-calendar ml-1"></i> {{ $inspection->inspection_date ? $inspection->inspection_date->format('Y-m-d') : 'غير محدد' }}</span>
                <span><i class="fas fa-clock ml-1"></i> {{ $inspection->inspection_time ?? 'غير محدد' }}</span>
            </div>
            <div style="margin-top: 0.75rem; padding: 0.75rem; background: #F3F4F6; border-radius: 8px; font-size: 0.875rem; color: #6B7280;">
                <strong>ملاحظة:</strong> رقم هاتف العميل غير متاح لحماية خصوصيته. سيتم التواصل معك من خلال المنصة عند تأكيد المعاينة.
            </div>
        </div>
        <div>
            @if($inspection->status == 'pending')
            <span style="background: #FEF3C7; color: #D97706; padding: 0.5rem 1rem; border-radius: 8px; font-weight: 600; font-size: 0.875rem;">قيد الانتظار</span>
            @elseif($inspection->status == 'confirmed')
            <span style="background: #D1FAE5; color: #536b63; padding: 0.5rem 1rem; border-radius: 8px; font-weight: 600; font-size: 0.875rem;">مؤكد</span>
            @elseif($inspection->status == 'completed')
            <span style="background: #DBEAFE; color: #2563EB; padding: 0.5rem 1rem; border-radius: 8px; font-weight: 600; font-size: 0.875rem;">مكتمل</span>
            @endif
        </div>
    </div>
    
    @if($inspection->status == 'pending')
    <div style="margin-top: 1rem; padding: 1rem; background: #FFFBEB; border: 2px solid #F59E0B; border-radius: 8px;">
        <p style="color: #D97706; font-weight: 600; margin-bottom: 1rem;">
            <i class="fas fa-exclamation-circle ml-1"></i>
            طلب معاينة جديد - يرجى الموافقة أو الرفض
        </p>
        <div style="display: flex; gap: 1rem; flex-wrap: wrap;">
            <form action="{{ route('owner.inspections.accept', $inspection) }}" method="POST" style="display: inline;">
                @csrf
                <button type="submit" style="background: linear-gradient(135deg, #6b8980 0%, #1d313f 100%); color: white; padding: 0.75rem 1.5rem; border-radius: 8px; border: none; font-weight: 700; cursor: pointer; display: inline-flex; align-items: center; gap: 0.5rem;">
                    <i class="fas fa-check"></i>
                    قبول الطلب
                </button>
            </form>
            <button onclick="showRejectModal({{ $inspection->id }})" style="background: #DC2626; color: white; padding: 0.75rem 1.5rem; border-radius: 8px; border: none; font-weight: 700; cursor: pointer; display: inline-flex; align-items: center; gap: 0.5rem;">
                <i class="fas fa-times"></i>
                رفض الطلب
            </button>
            <button onclick="showAlternativeModal({{ $inspection->id }})" style="background: #6366F1; color: white; padding: 0.75rem 1.5rem; border-radius: 8px; border: none; font-weight: 700; cursor: pointer; display: inline-flex; align-items: center; gap: 0.5rem;">
                <i class="fas fa-calendar-alt"></i>
                اقتراح موعد بديل
            </button>
        </div>
    </div>
    @elseif($inspection->status == 'confirmed')
    <div style="margin-top: 1rem; padding: 1rem; background: #F0FDF4; border: 2px solid #6b8980; border-radius: 8px;">
        <p style="color: #536b63; font-weight: 600; margin: 0;">
            <i class="fas fa-info-circle ml-1"></i>
            تم تأكيد موعد المعاينة. يرجى الاستعداد لاستقبال المستأجر في التاريخ والوقت المحددين.
        </p>
    </div>
    @endif
    </div>
</div>
@endforeach

{{ $inspections->links() }}
@else
<div style="background: white; border-radius: 16px; padding: 4rem 2rem; text-align: center; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);">
    <i class="fas fa-calendar-check" style="font-size: 4rem; color: #9CA3AF; margin-bottom: 1.5rem; opacity: 0.5;"></i>
    <h3 style="font-size: 1.5rem; font-weight: 700; color: #1F2937; margin-bottom: 0.5rem;">لا توجد معاينات</h3>
    <p style="color: #6B7280;">لا توجد معاينات حالياً.</p>
</div>
@endif

<!-- Reject Modal -->
<div id="rejectModal" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0, 0, 0, 0.5); z-index: 1000; align-items: center; justify-content: center;">
    <div style="background: white; border-radius: 16px; padding: 2rem; max-width: 500px; width: 90%; max-height: 90vh; overflow-y: auto;">
        <h3 style="font-size: 1.5rem; font-weight: 700; color: #1F2937; margin-bottom: 1.5rem;">رفض طلب المعاينة</h3>
        <form id="rejectForm" method="POST">
            @csrf
            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; font-weight: 600; color: #374151; margin-bottom: 0.5rem;">سبب الرفض <span style="color: #EF4444;">*</span></label>
                <textarea name="rejection_reason" rows="4" required style="width: 100%; padding: 0.875rem; border: 2px solid #E5E7EB; border-radius: 8px; font-family: 'Cairo', sans-serif;" placeholder="يرجى كتابة سبب رفض طلب المعاينة..."></textarea>
            </div>
            <div style="display: flex; gap: 1rem; justify-content: flex-end;">
                <button type="button" onclick="closeRejectModal()" style="padding: 0.75rem 1.5rem; background: #F3F4F6; color: #374151; border-radius: 8px; border: none; font-weight: 700; cursor: pointer;">إلغاء</button>
                <button type="submit" style="padding: 0.75rem 1.5rem; background: #DC2626; color: white; border-radius: 8px; border: none; font-weight: 700; cursor: pointer;">رفض الطلب</button>
            </div>
        </form>
    </div>
</div>

<!-- Alternative Date Modal -->
<div id="alternativeModal" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0, 0, 0, 0.5); z-index: 1000; align-items: center; justify-content: center;">
    <div style="background: white; border-radius: 16px; padding: 2rem; max-width: 500px; width: 90%; max-height: 90vh; overflow-y: auto;">
        <h3 style="font-size: 1.5rem; font-weight: 700; color: #1F2937; margin-bottom: 1.5rem;">اقتراح موعد بديل</h3>
        <form id="alternativeForm" method="POST">
            @csrf
            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; font-weight: 600; color: #374151; margin-bottom: 0.5rem;">التاريخ البديل <span style="color: #EF4444;">*</span></label>
                <input type="date" name="alternative_date" required min="{{ date('Y-m-d', strtotime('+1 day')) }}" style="width: 100%; padding: 0.875rem; border: 2px solid #E5E7EB; border-radius: 8px; font-family: 'Cairo', sans-serif;">
            </div>
            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; font-weight: 600; color: #374151; margin-bottom: 0.5rem;">الوقت البديل <span style="color: #EF4444;">*</span></label>
                <input type="time" name="alternative_time" required style="width: 100%; padding: 0.875rem; border: 2px solid #E5E7EB; border-radius: 8px; font-family: 'Cairo', sans-serif;">
            </div>
            <div style="display: flex; gap: 1rem; justify-content: flex-end;">
                <button type="button" onclick="closeAlternativeModal()" style="padding: 0.75rem 1.5rem; background: #F3F4F6; color: #374151; border-radius: 8px; border: none; font-weight: 700; cursor: pointer;">إلغاء</button>
                <button type="submit" style="padding: 0.75rem 1.5rem; background: #6366F1; color: white; border-radius: 8px; border: none; font-weight: 700; cursor: pointer;">إرسال الاقتراح</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
function showRejectModal(bookingId) {
    const modal = document.getElementById('rejectModal');
    const form = document.getElementById('rejectForm');
    form.action = '{{ route("owner.inspections.reject", ":id") }}'.replace(':id', bookingId);
    modal.style.display = 'flex';
}

function closeRejectModal() {
    document.getElementById('rejectModal').style.display = 'none';
    document.getElementById('rejectForm').reset();
}

function showAlternativeModal(bookingId) {
    const modal = document.getElementById('alternativeModal');
    const form = document.getElementById('alternativeForm');
    form.action = '{{ route("owner.inspections.suggest-alternative", ":id") }}'.replace(':id', bookingId);
    modal.style.display = 'flex';
}

function closeAlternativeModal() {
    document.getElementById('alternativeModal').style.display = 'none';
    document.getElementById('alternativeForm').reset();
}

// Close modals when clicking outside
document.getElementById('rejectModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeRejectModal();
    }
});

document.getElementById('alternativeModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeAlternativeModal();
    }
});
</script>
@endpush
@endsection



