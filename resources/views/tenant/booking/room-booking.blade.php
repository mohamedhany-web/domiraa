@extends('layouts.app')

@section('title', 'حجز الغرفة - ' . $room->room_name)

@push('styles')
<style>
    .booking-container {
        max-width: 800px;
        margin: 0 auto;
    }
    
    .booking-card {
        background: white;
        border-radius: 16px;
        padding: 2rem;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        margin-bottom: 2rem;
    }
    
    .room-info {
        display: flex;
        gap: 1.5rem;
        margin-bottom: 2rem;
        padding: 1.5rem;
        background: #F9FAFB;
        border-radius: 12px;
    }
    
    .room-image {
        width: 200px;
        height: 150px;
        border-radius: 12px;
        object-fit: cover;
    }
    
    .room-details h3 {
        font-size: 1.5rem;
        font-weight: 700;
        color: #1F2937;
        margin-bottom: 0.5rem;
    }
    
    .form-group {
        margin-bottom: 1.5rem;
    }
    
    .form-label {
        display: block;
        font-weight: 700;
        color: #374151;
        margin-bottom: 0.5rem;
    }
    
    .form-input, .form-select {
        width: 100%;
        padding: 0.75rem;
        border: 2px solid #E5E7EB;
        border-radius: 8px;
        font-size: 1rem;
        transition: all 0.3s ease;
    }
    
    .form-input:focus, .form-select:focus {
        outline: none;
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(29, 49, 63, 0.1);
    }
    
    .btn-primary {
        background: linear-gradient(135deg, var(--primary), var(--secondary));
        color: white;
        padding: 1rem 2rem;
        border-radius: 10px;
        border: none;
        font-weight: 700;
        font-size: 1.1rem;
        cursor: pointer;
        transition: all 0.3s ease;
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
    }
    
    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(29, 49, 63, 0.3);
    }
    
    .price-summary {
        background: linear-gradient(135deg, rgba(29, 49, 63, 0.05), rgba(107, 137, 128, 0.05));
        padding: 1.5rem;
        border-radius: 12px;
        margin-top: 1.5rem;
    }
    
    .price-item {
        display: flex;
        justify-content: space-between;
        padding: 0.75rem 0;
        border-bottom: 1px solid #E5E7EB;
    }
    
    .price-item:last-child {
        border-bottom: none;
        font-weight: 700;
        font-size: 1.25rem;
        color: var(--primary);
    }
</style>
@endpush

@section('content')
<div class="booking-container py-8">
    <div class="booking-card">
        <h1 style="font-size: 2rem; font-weight: 800; color: #1F2937; margin-bottom: 2rem; text-align: center;">
            <i class="fas fa-door-open"></i>
            حجز الغرفة
        </h1>
        
        <!-- Room Info -->
        <div class="room-info">
            @if($room->images && count($room->images) > 0)
            <img src="{{ \App\Helpers\StorageHelper::url($room->images[0]) }}" alt="{{ $room->room_name }}" class="room-image" onerror="this.src='{{ \App\Helpers\StorageHelper::placeholder() }}'">
            @else
            <div class="room-image" style="background: linear-gradient(135deg, var(--primary), var(--secondary)); display: flex; align-items: center; justify-content: center; color: white; font-size: 3rem;">
                <i class="fas fa-door-open"></i>
            </div>
            @endif
            
            <div class="room-details" style="flex: 1;">
                <h3>{{ $room->room_name }}</h3>
                @if($room->room_number)
                <p style="color: #6B7280; margin-bottom: 0.5rem;">رقم الغرفة: {{ $room->room_number }}</p>
                @endif
                @if($room->description)
                <p style="color: #6B7280; margin-bottom: 1rem;">{{ Str::limit($room->description, 100) }}</p>
                @endif
                
                <div style="display: flex; gap: 1rem; flex-wrap: wrap;">
                    @if($room->area)
                    <div style="background: white; padding: 0.5rem 1rem; border-radius: 8px;">
                        <span style="color: #6B7280; font-size: 0.875rem;">المساحة:</span>
                        <span style="color: #1F2937; font-weight: 700; margin-right: 0.5rem;">{{ $room->area }} م²</span>
                    </div>
                    @endif
                    <div style="background: white; padding: 0.5rem 1rem; border-radius: 8px;">
                        <span style="color: #6B7280; font-size: 0.875rem;">الأسرة:</span>
                        <span style="color: #1F2937; font-weight: 700; margin-right: 0.5rem;">{{ $room->beds }}</span>
                    </div>
                </div>
                
                <div style="margin-top: 1rem; font-size: 1.5rem; font-weight: 800; color: var(--primary);">
                    {{ number_format($room->price) }}
                    <span style="font-size: 1rem; color: #6B7280;">
                        {{ $room->price_type === 'monthly' ? ' /شهر' : ($room->price_type === 'yearly' ? ' /سنة' : ' /يوم') }}
                    </span>
                </div>
            </div>
        </div>
        
        <!-- Booking Form -->
        <form method="POST" action="{{ route('room.booking.store', ['property' => $property->id, 'room' => $room->id]) }}" id="roomBookingForm">
            @csrf
            <input type="hidden" name="_token" value="{{ csrf_token() }}" id="csrf_token_input">
            
            @guest
            <input type="hidden" name="guest" value="1">
            <div class="form-group">
                <label class="form-label">الاسم <span style="color: #EF4444;">*</span></label>
                <input type="text" name="name" class="form-input" required>
            </div>
            
            <div class="form-group">
                <label class="form-label">البريد الإلكتروني <span style="color: #EF4444;">*</span></label>
                <input type="email" name="email" class="form-input" required>
            </div>
            
            <div class="form-group">
                <label class="form-label">رقم الهاتف <span style="color: #EF4444;">*</span></label>
                <input type="tel" name="phone" class="form-input" required>
            </div>
            @endguest
            
            <div class="form-group">
                <label class="form-label">نوع الحجز <span style="color: #EF4444;">*</span></label>
                <select name="booking_type" id="booking_type" class="form-select" required onchange="toggleBookingFields()">
                    <option value="inspection">معاينة فقط</option>
                    <option value="reservation">حجز نهائي</option>
                </select>
            </div>
            
            <!-- حقول المعاينة (تظهر فقط عند اختيار "معاينة فقط") -->
            <div id="inspectionFields">
                <div class="form-group">
                    <label class="form-label">تاريخ المعاينة <span style="color: #EF4444;">*</span></label>
                    <input type="date" name="inspection_date" id="inspection_date" class="form-input" min="{{ date('Y-m-d', strtotime('+1 day')) }}">
                </div>
                
                <div class="form-group">
                    <label class="form-label">وقت المعاينة <span style="color: #EF4444;">*</span></label>
                    <input type="time" name="inspection_time" id="inspection_time" class="form-input">
                </div>
            </div>
            
            <!-- حقول الحجز النهائي (تظهر فقط عند اختيار "حجز نهائي") -->
            <div id="reservationFields" style="display: none;">
                <div class="form-group">
                    <label class="form-label">تاريخ الدخول <span style="color: #EF4444;">*</span></label>
                    <input type="date" name="check_in_date" id="check_in_date" class="form-input" min="{{ date('Y-m-d') }}">
                </div>
                
                <div class="form-group">
                    <label class="form-label">تاريخ الخروج</label>
                    <input type="date" name="check_out_date" id="check_out_date" class="form-input">
                    <small style="color: #6B7280; font-size: 0.875rem;">اتركه فارغاً للإيجار بدون تاريخ انتهاء</small>
                </div>
            </div>
            
            <!-- Price Summary -->
            <div class="price-summary">
                <h3 style="font-size: 1.25rem; font-weight: 700; color: #1F2937; margin-bottom: 1rem;">ملخص السعر</h3>
                <div class="price-item">
                    <span>سعر الغرفة:</span>
                    <span>{{ number_format($room->price) }} {{ $room->price_type === 'monthly' ? ' /شهر' : ($room->price_type === 'yearly' ? ' /سنة' : ' /يوم') }}</span>
                </div>
                <div class="price-item" id="reservationFee" style="display: none;">
                    <span>نسبة الحجز:</span>
                    <span id="reservationFeeAmount">0</span>
                </div>
                <div class="price-item" id="inspectionFee" style="display: none;">
                    <span>رسوم المعاينة:</span>
                    <span id="inspectionFeeAmount">0</span>
                </div>
                <div class="price-item">
                    <span>المبلغ الإجمالي:</span>
                    <span id="totalAmount">0</span>
                </div>
            </div>
            
            <button type="submit" class="btn-primary" style="margin-top: 2rem;">
                <i class="fas fa-calendar-check"></i>
                تأكيد الحجز
            </button>
        </form>
    </div>
</div>

<script>
function toggleBookingFields() {
    const bookingType = document.getElementById('booking_type').value;
    const inspectionFields = document.getElementById('inspectionFields');
    const reservationFields = document.getElementById('reservationFields');
    const inspectionDateInput = document.getElementById('inspection_date');
    const inspectionTimeInput = document.getElementById('inspection_time');
    const checkInDateInput = document.getElementById('check_in_date');
    const checkOutDateInput = document.getElementById('check_out_date');
    
    if (bookingType === 'inspection') {
        // إظهار حقول المعاينة وإخفاء حقول الحجز
        inspectionFields.style.display = 'block';
        reservationFields.style.display = 'none';
        
        // جعل حقول المعاينة مطلوبة
        inspectionDateInput.required = true;
        inspectionTimeInput.required = true;
        inspectionDateInput.disabled = false;
        inspectionTimeInput.disabled = false;
        
        // جعل حقول الحجز غير مطلوبة وتعطيلها
        checkInDateInput.required = false;
        checkOutDateInput.required = false;
        checkInDateInput.disabled = true;
        checkOutDateInput.disabled = true;
        
        // مسح قيم حقول الحجز
        checkInDateInput.value = '';
        checkOutDateInput.value = '';
    } else {
        // إظهار حقول الحجز وإخفاء حقول المعاينة
        inspectionFields.style.display = 'none';
        reservationFields.style.display = 'block';
        
        // جعل حقول الحجز مطلوبة وتفعيلها
        checkInDateInput.required = true;
        checkInDateInput.disabled = false;
        checkOutDateInput.disabled = false;
        
        // جعل حقول المعاينة غير مطلوبة وتعطيلها
        inspectionDateInput.required = false;
        inspectionTimeInput.required = false;
        inspectionDateInput.disabled = true;
        inspectionTimeInput.disabled = true;
        
        // مسح قيم حقول المعاينة
        inspectionDateInput.value = '';
        inspectionTimeInput.value = '';
    }
    
    updatePrice();
}

function updatePrice() {
    const bookingType = document.getElementById('booking_type').value;
    const roomPrice = {{ $room->price }};
    const priceType = '{{ $room->price_type }}';
    const reservationPercentage = {
        'daily': {{ \App\Models\Setting::getReservationPercentageByType('daily') }},
        'monthly': {{ \App\Models\Setting::getReservationPercentageByType('monthly') }},
        'yearly': {{ \App\Models\Setting::getReservationPercentageByType('yearly') }}
    };
    const inspectionFee = {{ \App\Models\Setting::getInspectionFee() }};
    
    const reservationFeeEl = document.getElementById('reservationFee');
    const inspectionFeeEl = document.getElementById('inspectionFee');
    const totalAmountEl = document.getElementById('totalAmount');
    
    let total = 0;
    
    if (bookingType === 'reservation') {
        const percentage = reservationPercentage[priceType] / 100;
        const fee = roomPrice * percentage;
        document.getElementById('reservationFeeAmount').textContent = new Intl.NumberFormat('ar-EG').format(fee) + ' ج.م';
        reservationFeeEl.style.display = 'flex';
        inspectionFeeEl.style.display = 'none';
        total = fee;
    } else {
        document.getElementById('inspectionFeeAmount').textContent = new Intl.NumberFormat('ar-EG').format(inspectionFee) + ' ج.م';
        reservationFeeEl.style.display = 'none';
        inspectionFeeEl.style.display = 'flex';
        total = inspectionFee;
    }
    
    totalAmountEl.textContent = new Intl.NumberFormat('ar-EG').format(total) + ' ج.م';
}

// Initialize on load
document.addEventListener('DOMContentLoaded', function() {
    toggleBookingFields();
    updatePrice();
    
    // Update check_out_date min date when check_in_date changes
    document.getElementById('check_in_date')?.addEventListener('change', function() {
        const checkOutDate = document.getElementById('check_out_date');
        if (checkOutDate && checkOutDate.value && checkOutDate.value <= this.value) {
            checkOutDate.value = '';
        }
        if (checkOutDate) {
            checkOutDate.min = this.value;
        }
    });
    
    // Form validation and submission
    document.getElementById('roomBookingForm')?.addEventListener('submit', function(e) {
        const bookingType = document.getElementById('booking_type').value;
        
        if (bookingType === 'inspection') {
            const inspectionDate = document.getElementById('inspection_date').value;
            const inspectionTime = document.getElementById('inspection_time').value;
            
            if (!inspectionDate || !inspectionTime) {
                e.preventDefault();
                alert('يرجى اختيار تاريخ ووقت المعاينة');
                return false;
            }
        } else {
            const checkInDate = document.getElementById('check_in_date').value;
            
            if (!checkInDate) {
                e.preventDefault();
                alert('يرجى اختيار تاريخ الدخول');
                return false;
            }
        }
        
        // Re-enable all fields before submission to ensure they are sent
        document.getElementById('inspection_date').disabled = false;
        document.getElementById('inspection_time').disabled = false;
        document.getElementById('check_in_date').disabled = false;
        document.getElementById('check_out_date').disabled = false;
    });
});
</script>
@endsection

