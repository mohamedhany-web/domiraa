@extends('layouts.admin')

@section('title', 'إدارة الحجوزات')
@section('page-title', 'إدارة الحجوزات')

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
    
    .stat-icon.green {
        background: linear-gradient(135deg, #8aa69d 0%, #6b8980 100%);
        color: white;
    }
    
    .stat-icon.blue {
        background: linear-gradient(135deg, #60A5FA 0%, #2a4456 100%);
        color: white;
    }
    
    .stat-icon.purple {
        background: linear-gradient(135deg, #A78BFA 0%, #8B5CF6 100%);
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
    .bookings-section {
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
    
    .badge-confirmed {
        background: #D1FAE5;
        color: #536b63;
    }
    
    .badge-completed {
        background: #DBEAFE;
        color: #1d313f;
    }
    
    .badge-cancelled {
        background: #FEE2E2;
        color: #DC2626;
    }
    
    .badge-paid {
        background: #D1FAE5;
        color: #536b63;
    }
    
    .badge-unpaid {
        background: #FEF3C7;
        color: #D97706;
    }
    
    .action-buttons {
        display: flex;
        gap: 0.5rem;
        flex-wrap: wrap;
    }
    
    .modal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        overflow: auto;
    }
    
    .modal.active {
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .modal-content {
        background: white;
        border-radius: 16px;
        padding: 2rem;
        max-width: 800px;
        width: 90%;
        max-height: 90vh;
        overflow-y: auto;
        margin: auto;
    }
    
    .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid #E5E7EB;
    }
    
    .modal-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: #1F2937;
    }
    
    .close-modal {
        background: none;
        border: none;
        font-size: 1.5rem;
        color: #6B7280;
        cursor: pointer;
    }
    
    .booking-details-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1.5rem;
        margin-bottom: 1.5rem;
    }
    
    .detail-item {
        padding: 1rem;
        background: #F9FAFB;
        border-radius: 8px;
    }
    
    .detail-label {
        font-size: 0.875rem;
        color: #6B7280;
        font-weight: 600;
        margin-bottom: 0.5rem;
    }
    
    .detail-value {
        font-size: 1rem;
        color: #1F2937;
        font-weight: 700;
    }
    
    .receipt-preview {
        max-width: 100%;
        max-height: 400px;
        border-radius: 8px;
        margin-top: 1rem;
    }
    
    @media (max-width: 768px) {
        .booking-details-grid {
            grid-template-columns: 1fr;
        }
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
        
        .table-container {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }
        
        .table {
            font-size: 0.75rem;
            min-width: 800px;
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
                <div class="stat-label">قيد الانتظار</div>
                <div class="stat-value">{{ $pendingBookings }}</div>
            </div>
            <div class="stat-icon orange">
                <i class="fas fa-clock"></i>
            </div>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-header">
            <div class="stat-content">
                <div class="stat-label">مؤكدة</div>
                <div class="stat-value">{{ $confirmedBookings }}</div>
            </div>
            <div class="stat-icon green">
                <i class="fas fa-check-circle"></i>
            </div>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-header">
            <div class="stat-content">
                <div class="stat-label">مكتملة</div>
                <div class="stat-value">{{ $completedBookings }}</div>
            </div>
            <div class="stat-icon blue">
                <i class="fas fa-calendar-check"></i>
            </div>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-header">
            <div class="stat-content">
                <div class="stat-label">إجمالي</div>
                <div class="stat-value">{{ $bookings->count() }}</div>
            </div>
            <div class="stat-icon purple">
                <i class="fas fa-list"></i>
            </div>
        </div>
    </div>
</div>

<!-- Filter Tabs -->
<div style="display: flex; gap: 0.5rem; margin-bottom: 1.5rem; flex-wrap: wrap;">
    <a href="{{ route('admin.bookings') }}" style="padding: 0.75rem 1.5rem; border-radius: 8px; border: 2px solid #E5E7EB; background: white; color: #6B7280; font-weight: 600; text-decoration: none; transition: all 0.3s ease; {{ !request('status') ? 'background: linear-gradient(135deg, #1d313f 0%, #6b8980 100%); color: white; border-color: transparent;' : '' }}">
        الكل
    </a>
    <a href="{{ route('admin.bookings', ['status' => 'pending']) }}" style="padding: 0.75rem 1.5rem; border-radius: 8px; border: 2px solid #E5E7EB; background: white; color: #6B7280; font-weight: 600; text-decoration: none; transition: all 0.3s ease; {{ request('status') == 'pending' ? 'background: linear-gradient(135deg, #1d313f 0%, #6b8980 100%); color: white; border-color: transparent;' : '' }}">
        قيد الانتظار
    </a>
    <a href="{{ route('admin.bookings', ['status' => 'confirmed']) }}" style="padding: 0.75rem 1.5rem; border-radius: 8px; border: 2px solid #E5E7EB; background: white; color: #6B7280; font-weight: 600; text-decoration: none; transition: all 0.3s ease; {{ request('status') == 'confirmed' ? 'background: linear-gradient(135deg, #1d313f 0%, #6b8980 100%); color: white; border-color: transparent;' : '' }}">
        مؤكدة
    </a>
    <a href="{{ route('admin.bookings', ['status' => 'completed']) }}" style="padding: 0.75rem 1.5rem; border-radius: 8px; border: 2px solid #E5E7EB; background: white; color: #6B7280; font-weight: 600; text-decoration: none; transition: all 0.3s ease; {{ request('status') == 'completed' ? 'background: linear-gradient(135deg, #1d313f 0%, #6b8980 100%); color: white; border-color: transparent;' : '' }}">
        مكتملة
    </a>
    <a href="{{ route('admin.bookings', ['status' => 'cancelled']) }}" style="padding: 0.75rem 1.5rem; border-radius: 8px; border: 2px solid #E5E7EB; background: white; color: #6B7280; font-weight: 600; text-decoration: none; transition: all 0.3s ease; {{ request('status') == 'cancelled' ? 'background: linear-gradient(135deg, #1d313f 0%, #6b8980 100%); color: white; border-color: transparent;' : '' }}">
        ملغاة
    </a>
</div>

<!-- Bookings Table -->
<div class="bookings-section">
    <div class="section-header">
        <h2 class="section-title">
            <i class="fas fa-calendar-check"></i>
            قائمة طلبات المعاينة
        </h2>
        <a href="{{ route('admin.bookings.create') }}" style="background: linear-gradient(135deg, #1d313f 0%, #6b8980 100%); color: white; padding: 0.75rem 1.5rem; border-radius: 8px; text-decoration: none; font-weight: 600; display: inline-flex; align-items: center; gap: 0.5rem;">
            <i class="fas fa-plus"></i>
            إضافة حجز جديد
        </a>
    </div>
    
    <div class="table-container">
        <table class="table">
            <thead>
                <tr>
                    <th>المستأجر</th>
                    <th>الوحدة / المؤجر</th>
                    <th>تاريخ المعاينة</th>
                    <th>النوع</th>
                    <th>المبلغ</th>
                    <th>حالة الدفع</th>
                    <th>الحالة</th>
                    <th>الإجراءات</th>
                    <th>التاريخ</th>
                </tr>
            </thead>
            <tbody>
                @forelse($bookings as $booking)
                <tr>
                    <td>
                        <div style="font-weight: 600; color: #1F2937;">{{ $booking->user->name }}</div>
                        <div style="font-size: 0.75rem; color: #6B7280;">{{ $booking->user->phone }}</div>
                        <div style="font-size: 0.75rem; color: #6B7280;">{{ $booking->user->email }}</div>
                    </td>
                    <td>
                        <div style="font-weight: 600; color: #1F2937;">{{ Str::limit($booking->property->address, 25) }}</div>
                        <div style="font-size: 0.75rem; color: #6B7280;">المؤجر: {{ $booking->property->user->name ?? 'غير محدد' }}</div>
                    </td>
                    <td>
                        <div>{{ $booking->inspection_date ? $booking->inspection_date->format('Y-m-d') : 'غير محدد' }}</div>
                        <div style="font-size: 0.75rem; color: #6B7280;">{{ $booking->inspection_time ?? 'غير محدد' }}</div>
                    </td>
                    <td>
                        <span class="badge {{ $booking->booking_type === 'inspection' ? 'badge-pending' : 'badge-confirmed' }}">
                            {{ $booking->booking_type === 'inspection' ? 'معاينة' : 'حجز' }}
                        </span>
                    </td>
                    <td>
                        <div style="font-weight: 600;">{{ number_format($booking->amount, 2) }} ج.م</div>
                    </td>
                    <td>
                        <span class="badge {{ $booking->payment_status === 'paid' ? 'badge-paid' : 'badge-unpaid' }}">
                            {{ $booking->payment_status === 'paid' ? 'مدفوع' : 'غير مدفوع' }}
                        </span>
                    </td>
                    <td>
                        @if($booking->status === 'pending')
                            <span class="badge badge-pending">قيد الانتظار</span>
                        @elseif($booking->status === 'confirmed')
                            <span class="badge badge-confirmed">مؤكدة</span>
                        @elseif($booking->status === 'completed')
                            <span class="badge badge-completed">مكتملة</span>
                        @else
                            <span class="badge badge-cancelled">ملغاة</span>
                        @endif
                    </td>
                    <td>
                        <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
                            @if($booking->status === 'pending')
                                <form action="{{ route('admin.inspections.approve', $booking) }}" method="POST" style="display: inline;">
                                    @csrf
                                    <button type="submit" style="background: #6b8980; color: white; padding: 0.375rem 0.75rem; border-radius: 6px; border: none; font-size: 0.75rem; font-weight: 600; cursor: pointer;">
                                        <i class="fas fa-check"></i> موافقة
                                    </button>
                                </form>
                                <button onclick="showRejectModal({{ $booking->id }})" style="background: #DC2626; color: white; padding: 0.375rem 0.75rem; border-radius: 6px; border: none; font-size: 0.75rem; font-weight: 600; cursor: pointer;">
                                    <i class="fas fa-times"></i> رفض
                                </button>
                            @endif
                            
                            <a href="{{ route('admin.bookings.edit', $booking) }}" style="background: #6366F1; color: white; padding: 0.375rem 0.75rem; border-radius: 6px; border: none; font-size: 0.75rem; font-weight: 600; cursor: pointer; text-decoration: none; display: inline-block;">
                                <i class="fas fa-edit"></i> تعديل
                            </a>
                            
                            <button onclick="viewBookingDetails({{ $booking->id }})" style="background: #1d313f; color: white; padding: 0.375rem 0.75rem; border-radius: 6px; border: none; font-size: 0.75rem; font-weight: 600; cursor: pointer;">
                                <i class="fas fa-eye"></i> عرض
                            </button>
                            
                            @php
                                $paymentWithReceipt = $booking->payments->where('receipt_path', '!=', null)->first();
                            @endphp
                            @if($paymentWithReceipt)
                            <button onclick="viewReceipt('{{ \App\Helpers\StorageHelper::url($paymentWithReceipt->receipt_path) }}')" style="background: #2563EB; color: white; padding: 0.375rem 0.75rem; border-radius: 6px; border: none; font-size: 0.75rem; font-weight: 600; cursor: pointer;">
                                <i class="fas fa-receipt"></i> إيصال
                            </button>
                            @endif
                            
                            <form action="{{ route('admin.bookings.destroy', $booking) }}" method="POST" style="display: inline;" onsubmit="return confirm('هل أنت متأكد من حذف هذا الحجز؟');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" style="background: #DC2626; color: white; padding: 0.375rem 0.75rem; border-radius: 6px; border: none; font-size: 0.75rem; font-weight: 600; cursor: pointer;">
                                    <i class="fas fa-trash"></i> حذف
                                </button>
                            </form>
                        </div>
                    </td>
                    <td>{{ $booking->created_at->format('Y-m-d') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" style="text-align: center; padding: 2rem; color: #6B7280;">
                        لا توجد طلبات معاينة
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($bookings->hasPages())
    <div style="margin-top: 1.5rem; display: flex; justify-content: center;">
        {{ $bookings->links() }}
    </div>
    @endif
</div>

<!-- Reject Modal -->
@foreach($bookings as $booking)
@if($booking->status === 'pending')
<div id="reject-modal-{{ $booking->id }}" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0, 0, 0, 0.5); z-index: 1000; align-items: center; justify-content: center;">
    <div style="background: white; border-radius: 12px; padding: 2rem; max-width: 500px; width: 90%; margin: auto;">
        <h3 style="font-size: 1.25rem; font-weight: 700; color: #1F2937; margin-bottom: 1rem;">رفض طلب المعاينة</h3>
        <form action="{{ route('admin.inspections.reject', $booking) }}" method="POST">
            @csrf
            <div style="margin-bottom: 1rem;">
                <label style="display: block; font-weight: 600; color: #374151; margin-bottom: 0.5rem;">سبب الرفض <span style="color: #DC2626;">*</span></label>
                <textarea name="rejection_reason" required style="width: 100%; padding: 0.75rem; border: 2px solid #E5E7EB; border-radius: 8px; min-height: 100px; font-family: 'Cairo', sans-serif;"></textarea>
            </div>
            <div style="display: flex; gap: 0.5rem; justify-content: flex-end;">
                <button type="button" onclick="closeRejectModal({{ $booking->id }})" style="background: #6B7280; color: white; padding: 0.75rem 1.5rem; border-radius: 8px; border: none; font-weight: 600; cursor: pointer;">إلغاء</button>
                <button type="submit" style="background: #DC2626; color: white; padding: 0.75rem 1.5rem; border-radius: 8px; border: none; font-weight: 600; cursor: pointer;">رفض</button>
            </div>
        </form>
    </div>
</div>
@endif
@endforeach

<!-- Booking Details Modal -->
<div id="bookingDetailsModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3 class="modal-title">تفاصيل الحجز</h3>
            <button type="button" class="close-modal" onclick="closeBookingDetailsModal()">&times;</button>
        </div>
        <div id="bookingDetailsContent"></div>
    </div>
</div>

<!-- Receipt Modal -->
<div id="receiptModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3 class="modal-title">إيصال التحويل</h3>
            <button type="button" class="close-modal" onclick="closeReceiptModal()">&times;</button>
        </div>
        <div>
            <img id="receiptImage" src="" alt="Receipt" class="receipt-preview" style="display: none;">
            <iframe id="receiptPdf" src="" style="width: 100%; height: 500px; border: none; display: none;"></iframe>
        </div>
    </div>
</div>

<script>
// Booking data from server
const bookingsData = {
    @foreach($bookings as $booking)
    {{ $booking->id }}: {
        id: {{ $booking->id }},
        user: {
            name: "{{ $booking->user->name }}",
            email: "{{ $booking->user->email }}",
            phone: "{{ $booking->user->phone }}"
        },
        property: {
            address: "{{ $booking->property->address }}",
            code: "{{ $booking->property->code }}",
            owner: "{{ $booking->property->user->name ?? 'غير محدد' }}"
        },
        inspection_date: "{{ $booking->inspection_date ? $booking->inspection_date->format('Y-m-d') : 'غير محدد' }}",
        inspection_time: "{{ $booking->inspection_time ?? 'غير محدد' }}",
        booking_type: "{{ $booking->booking_type === 'inspection' ? 'معاينة' : 'حجز' }}",
        amount: "{{ number_format($booking->amount, 2) }}",
        payment_status: "{{ $booking->payment_status === 'paid' ? 'مدفوع' : 'غير مدفوع' }}",
        status: "{{ $booking->status === 'pending' ? 'قيد الانتظار' : ($booking->status === 'confirmed' ? 'مؤكدة' : ($booking->status === 'completed' ? 'مكتملة' : 'ملغاة')) }}",
        created_at: "{{ $booking->created_at->format('Y-m-d H:i') }}",
        payments: [
            @foreach($booking->payments as $payment)
            {
                id: {{ $payment->id }},
                amount: "{{ number_format($payment->amount, 2) }}",
                payment_type: "{{ $payment->payment_type === 'inspection_fee' ? 'رسوم المعاينة' : 'رسوم الحجز' }}",
                payment_method: "{{ $payment->payment_method }}",
                status: "{{ $payment->status }}",
                review_status: "{{ $payment->review_status }}",
                receipt_path: @if($payment->receipt_path) "{{ \App\Helpers\StorageHelper::url($payment->receipt_path) }}" @else null @endif,
                wallet: @if($payment->wallet) {
                    name: "{{ $payment->wallet->name }}",
                    bank_name: "{{ $payment->wallet->bank_name ?? '' }}",
                    account_number: "{{ $payment->wallet->account_number ?? '' }}",
                    iban: "{{ $payment->wallet->iban ?? '' }}"
                } @else null @endif,
                created_at: "{{ $payment->created_at->format('Y-m-d H:i') }}"
            }@if(!$loop->last),@endif
            @endforeach
        ]
    }@if(!$loop->last),@endif
    @endforeach
};

function viewBookingDetails(bookingId) {
    const booking = bookingsData[bookingId];
    if (!booking) return;
    
    let html = '<div class="booking-details-grid">';
    
    // معلومات المستأجر
    html += '<div class="detail-item"><div class="detail-label">المستأجر</div><div class="detail-value">' + booking.user.name + '</div></div>';
    html += '<div class="detail-item"><div class="detail-label">البريد الإلكتروني</div><div class="detail-value">' + booking.user.email + '</div></div>';
    html += '<div class="detail-item"><div class="detail-label">رقم الهاتف</div><div class="detail-value">' + booking.user.phone + '</div></div>';
    
    // معلومات الوحدة
    html += '<div class="detail-item"><div class="detail-label">عنوان الوحدة</div><div class="detail-value">' + booking.property.address + '</div></div>';
    html += '<div class="detail-item"><div class="detail-label">كود الوحدة</div><div class="detail-value">' + booking.property.code + '</div></div>';
    html += '<div class="detail-item"><div class="detail-label">المؤجر</div><div class="detail-value">' + booking.property.owner + '</div></div>';
    
    // معلومات الحجز
    html += '<div class="detail-item"><div class="detail-label">تاريخ المعاينة</div><div class="detail-value">' + booking.inspection_date + '</div></div>';
    html += '<div class="detail-item"><div class="detail-label">وقت المعاينة</div><div class="detail-value">' + booking.inspection_time + '</div></div>';
    html += '<div class="detail-item"><div class="detail-label">نوع الحجز</div><div class="detail-value">' + booking.booking_type + '</div></div>';
    html += '<div class="detail-item"><div class="detail-label">المبلغ</div><div class="detail-value">' + booking.amount + ' ج.م</div></div>';
    html += '<div class="detail-item"><div class="detail-label">حالة الدفع</div><div class="detail-value">' + booking.payment_status + '</div></div>';
    html += '<div class="detail-item"><div class="detail-label">حالة الحجز</div><div class="detail-value">' + booking.status + '</div></div>';
    html += '<div class="detail-item"><div class="detail-label">تاريخ الإنشاء</div><div class="detail-value">' + booking.created_at + '</div></div>';
    
    html += '</div>';
    
    // معلومات الدفع
    if (booking.payments && booking.payments.length > 0) {
        html += '<div style="margin-top: 2rem; padding-top: 1.5rem; border-top: 1px solid #E5E7EB;"><h4 style="font-size: 1.125rem; font-weight: 700; color: #1F2937; margin-bottom: 1rem;">معلومات الدفع</h4>';
        booking.payments.forEach(function(payment) {
            html += '<div style="padding: 1rem; background: #F9FAFB; border-radius: 8px; margin-bottom: 1rem;">';
            html += '<div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 1rem;">';
            html += '<div><span style="font-weight: 600; color: #6B7280;">النوع:</span> <span style="font-weight: 700;">' + payment.payment_type + '</span></div>';
            html += '<div><span style="font-weight: 600; color: #6B7280;">المبلغ:</span> <span style="font-weight: 700;">' + payment.amount + ' ج.م</span></div>';
            html += '<div><span style="font-weight: 600; color: #6B7280;">طريقة الدفع:</span> <span style="font-weight: 700;">' + payment.payment_method + '</span></div>';
            html += '<div><span style="font-weight: 600; color: #6B7280;">حالة المراجعة:</span> <span style="font-weight: 700;">' + payment.review_status + '</span></div>';
            if (payment.wallet) {
                html += '<div><span style="font-weight: 600; color: #6B7280;">المحفظة:</span> <span style="font-weight: 700;">' + payment.wallet.name + (payment.wallet.bank_name ? ' - ' + payment.wallet.bank_name : '') + '</span></div>';
            }
            if (payment.receipt_path) {
                html += '<div><button onclick="viewReceipt(\'' + payment.receipt_path + '\')" style="background: #2563EB; color: white; padding: 0.5rem 1rem; border-radius: 6px; border: none; font-size: 0.875rem; font-weight: 600; cursor: pointer;"><i class="fas fa-receipt"></i> عرض الإيصال</button></div>';
            }
            html += '</div></div>';
        });
        html += '</div>';
    }
    
    document.getElementById('bookingDetailsContent').innerHTML = html;
    document.getElementById('bookingDetailsModal').classList.add('active');
}

function closeBookingDetailsModal() {
    document.getElementById('bookingDetailsModal').classList.remove('active');
}

function showRejectModal(bookingId) {
    document.getElementById('reject-modal-' + bookingId).style.display = 'flex';
}

function closeRejectModal(bookingId) {
    document.getElementById('reject-modal-' + bookingId).style.display = 'none';
}

function viewReceipt(receiptPath) {
    const modal = document.getElementById('receiptModal');
    const image = document.getElementById('receiptImage');
    const pdf = document.getElementById('receiptPdf');
    
    closeBookingDetailsModal(); // Close booking details modal if open
    
    if (receiptPath.endsWith('.pdf')) {
        image.style.display = 'none';
        pdf.style.display = 'block';
        pdf.src = receiptPath;
    } else {
        pdf.style.display = 'none';
        image.style.display = 'block';
        image.src = receiptPath;
    }
    
    modal.classList.add('active');
}

function closeReceiptModal() {
    document.getElementById('receiptModal').classList.remove('active');
}

// Close modals when clicking outside
window.onclick = function(event) {
    const bookingModal = document.getElementById('bookingDetailsModal');
    const receiptModal = document.getElementById('receiptModal');
    
    if (event.target === bookingModal) {
        closeBookingDetailsModal();
    }
    if (event.target === receiptModal) {
        closeReceiptModal();
    }
    if (event.target.id && event.target.id.startsWith('reject-modal-')) {
        closeRejectModal(event.target.id.replace('reject-modal-', ''));
    }
}
</script>
@endsection


