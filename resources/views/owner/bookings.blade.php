@extends('layouts.owner')

@section('title', 'الحجوزات والعقود - منصة دوميرا')
@section('page-title', 'الحجوزات والعقود')

@section('content')
@if($bookings->count() > 0)
<div style="display: grid; gap: 1.5rem;">
    @foreach($bookings as $booking)
    <div style="background: white; border-radius: 16px; padding: 1.5rem; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);">
        <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 1rem; flex-wrap: wrap; gap: 1rem;">
            <div style="flex: 1;">
                <h3 style="font-size: 1.25rem; font-weight: 700; color: #1F2937; margin-bottom: 0.5rem;">
                    {{ $booking->property->address }}
                </h3>
                <div style="display: flex; gap: 1.5rem; flex-wrap: wrap; color: #6B7280; font-size: 0.9rem;">
                    <span><i class="fas fa-user ml-1"></i> مستأجر</span>
                    <span><i class="fas fa-calendar ml-1"></i> {{ $booking->inspection_date ? $booking->inspection_date->format('Y-m-d') : 'غير محدد' }}</span>
                    <span><i class="fas fa-hashtag ml-1"></i> حجز #{{ $booking->id }}</span>
                </div>
            </div>
            <div>
                @if($booking->status == 'confirmed')
                <span style="background: #D1FAE5; color: #536b63; padding: 0.5rem 1rem; border-radius: 8px; font-weight: 600; font-size: 0.875rem;">تمت المعاينة</span>
                @elseif($booking->status == 'completed')
                <span style="background: #DBEAFE; color: #2563EB; padding: 0.5rem 1rem; border-radius: 8px; font-weight: 600; font-size: 0.875rem;">تم التعاقد</span>
                @endif
            </div>
        </div>
        
        @if($booking->status == 'completed' && !$booking->contract_path)
        <div style="margin-top: 1rem; padding: 1rem; background: #FEF3C7; border-radius: 8px;">
            <form action="{{ route('owner.bookings.upload-contract', $booking) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <label style="display: block; font-weight: 600; color: #374151; margin-bottom: 0.5rem;">رفع نسخة من العقد</label>
                <input type="file" name="contract" accept=".pdf,.jpg,.jpeg,.png" required style="width: 100%; padding: 0.75rem; border: 2px solid #E5E7EB; border-radius: 8px; margin-bottom: 0.75rem;">
                <button type="submit" style="background: linear-gradient(135deg, #1d313f 0%, #6b8980 100%); color: white; padding: 0.75rem 1.5rem; border-radius: 8px; border: none; font-weight: 600; cursor: pointer;">
                    <i class="fas fa-upload ml-1"></i> رفع العقد
                </button>
            </form>
        </div>
        @endif
        
        @if($booking->payment && $booking->payment->status == 'pending')
        <div style="margin-top: 1rem; padding: 1rem; background: #DBEAFE; border-radius: 8px;">
            <form action="{{ route('owner.bookings.confirm-payment', $booking) }}" method="POST">
                @csrf
                <p style="color: #1d313f; margin-bottom: 0.75rem;">تم استلام الدفعة الأولى؟</p>
                <button type="submit" style="background: linear-gradient(135deg, #6b8980 0%, #536b63 100%); color: white; padding: 0.75rem 1.5rem; border-radius: 8px; border: none; font-weight: 600; cursor: pointer;">
                    <i class="fas fa-check ml-1"></i> تأكيد استلام الدفعة
                </button>
            </form>
        </div>
        @endif
    </div>
    @endforeach
</div>

{{ $bookings->links() }}
@else
<div style="background: white; border-radius: 16px; padding: 4rem 2rem; text-align: center; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);">
    <i class="fas fa-file-contract" style="font-size: 4rem; color: #9CA3AF; margin-bottom: 1.5rem; opacity: 0.5;"></i>
    <h3 style="font-size: 1.5rem; font-weight: 700; color: #1F2937; margin-bottom: 0.5rem;">لا توجد حجوزات</h3>
    <p style="color: #6B7280;">لم يتم تأكيد أي حجوزات بعد</p>
</div>
@endif
@endsection



