@extends('layouts.app')

@section('title', 'حالة الطلب - منصة دوميرا')

@push('styles')
<style>
    .booking-card {
        background: white;
        border-radius: 16px;
        padding: 2rem;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        margin-bottom: 1.5rem;
    }
    
    .booking-header {
        display: flex;
        justify-content: space-between;
        align-items: start;
        margin-bottom: 1.5rem;
        padding-bottom: 1.5rem;
        border-bottom: 2px solid #F3F4F6;
    }
    
    .booking-code {
        font-size: 1.5rem;
        font-weight: 800;
        color: #1d313f;
    }
    
    .status-badge {
        padding: 0.5rem 1rem;
        border-radius: 8px;
        font-weight: 700;
        font-size: 0.875rem;
    }
    
    .status-pending {
        background: #FEF3C7;
        color: #D97706;
    }
    
    .status-confirmed {
        background: #D1FAE5;
        color: #065F46;
    }
    
    .status-completed {
        background: #DBEAFE;
        color: #1E40AF;
    }
    
    .status-cancelled {
        background: #FEE2E2;
        color: #DC2626;
    }
    
    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
        margin-bottom: 1.5rem;
    }
    
    .info-item {
        padding: 1rem;
        background: #F9FAFB;
        border-radius: 8px;
    }
    
    .info-label {
        font-size: 0.875rem;
        color: #6B7280;
        margin-bottom: 0.5rem;
        font-weight: 600;
    }
    
    .info-value {
        font-size: 1.125rem;
        font-weight: 700;
        color: #1F2937;
    }
    
    .btn-back {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.875rem 2rem;
        background: #F3F4F6;
        color: #374151;
        border-radius: 12px;
        text-decoration: none;
        font-weight: 700;
        transition: all 0.3s ease;
    }
    
    .btn-back:hover {
        background: #E5E7EB;
        transform: translateY(-2px);
    }
</style>
@endpush

@section('content')
<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="px-4 py-6 sm:px-0">
        <div style="margin-bottom: 2rem;">
            <h1 style="font-size: 2rem; font-weight: 800; color: #1F2937; margin-bottom: 0.5rem;">
                <i class="fas fa-list" style="margin-left: 0.5rem;"></i>
                طلباتك
            </h1>
            <p style="color: #6B7280;">تم العثور على {{ $bookings->count() }} طلب مرتبط برقم الهاتف</p>
        </div>
        
        @foreach($bookings as $booking)
        <div class="booking-card">
            <div class="booking-header">
                <div>
                    <div class="booking-code">
                        <i class="fas fa-hashtag" style="margin-left: 0.5rem;"></i>
                        {{ $booking->booking_code }}
                    </div>
                    <p style="color: #6B7280; margin-top: 0.5rem;">
                        @if($booking->property)
                            {{ $booking->property->address }}
                        @else
                            معلومات الوحدة غير متوفرة
                        @endif
                    </p>
                </div>
                <span class="status-badge status-{{ $booking->status }}">
                    @if($booking->status === 'pending')
                        قيد الانتظار
                    @elseif($booking->status === 'confirmed')
                        مؤكد
                    @elseif($booking->status === 'completed')
                        مكتمل
                    @else
                        ملغي
                    @endif
                </span>
            </div>
            
            <div class="info-grid">
                <div class="info-item">
                    <div class="info-label">نوع الحجز</div>
                    <div class="info-value">
                        {{ $booking->booking_type === 'inspection' ? 'معاينة' : 'حجز' }}
                    </div>
                </div>
                
                @if($booking->inspection_date)
                <div class="info-item">
                    <div class="info-label">تاريخ المعاينة</div>
                    <div class="info-value">
                        {{ $booking->inspection_date->format('Y-m-d') }}
                    </div>
                </div>
                
                <div class="info-item">
                    <div class="info-label">وقت المعاينة</div>
                    <div class="info-value">
                        {{ $booking->inspection_time ? $booking->inspection_time->format('H:i') : 'غير محدد' }}
                    </div>
                </div>
                @endif
                
                @if($booking->amount)
                <div class="info-item">
                    <div class="info-label">المبلغ</div>
                    <div class="info-value">
                        {{ number_format($booking->amount, 2) }} ج.م
                    </div>
                </div>
                @endif
                
                @if($booking->payment_status)
                <div class="info-item">
                    <div class="info-label">حالة الدفع</div>
                    <div class="info-value">
                        @if($booking->payment_status === 'pending')
                            <span style="color: #D97706;">قيد الانتظار</span>
                        @elseif($booking->payment_status === 'confirmed')
                            <span style="color: #065F46;">مؤكد</span>
                        @else
                            <span style="color: #DC2626;">ملغي</span>
                        @endif
                    </div>
                </div>
                @endif
                
                <div class="info-item">
                    <div class="info-label">تاريخ الحجز</div>
                    <div class="info-value">
                        {{ $booking->created_at->format('Y-m-d H:i') }}
                    </div>
                </div>
            </div>
            
            @if($booking->status === 'cancelled' && $booking->cancellation_reason)
            <div style="padding: 1rem; background: #FEE2E2; border-radius: 8px; margin-top: 1rem;">
                <strong style="color: #DC2626;">سبب الإلغاء:</strong>
                <p style="color: #991B1B; margin-top: 0.5rem;">{{ $booking->cancellation_reason }}</p>
            </div>
            @endif
        </div>
        @endforeach
        
        <div style="margin-top: 2rem; text-align: center;">
            <a href="{{ route('booking.tracking') }}" class="btn-back">
                <i class="fas fa-arrow-right"></i>
                البحث مرة أخرى
            </a>
        </div>
    </div>
</div>
@endsection

