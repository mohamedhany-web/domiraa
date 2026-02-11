@extends('layouts.app')

@section('title', 'حجز موعد للمعاينة')

@section('content')
<div class="max-w-2xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="px-4 py-6 sm:px-0">
        <h1 class="text-3xl font-bold text-gray-900 mb-6">حجز موعد للمعاينة</h1>
        
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">{{ $property->address }}</h2>
            <p class="text-gray-600 mb-2">السعر: {{ $property->price }} {{ $property->price_type === 'monthly' ? 'شهرياً' : ($property->price_type === 'yearly' ? 'سنوياً' : 'يومياً') }}</p>
        </div>
        
        @if ($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6">
                <div class="font-bold mb-2">يرجى تصحيح الأخطاء التالية:</div>
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6">
                <div class="font-bold mb-2">خطأ:</div>
                <p>{{ session('error') }}</p>
            </div>
        @endif

        @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-6">
                <p>{{ session('success') }}</p>
            </div>
        @endif

        <form method="POST" action="{{ route('tenant.booking.store', $property) }}" class="bg-white rounded-lg shadow-md p-6" id="bookingForm">
            @csrf
            
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">نوع الحجز *</label>
                <select name="booking_type" id="booking_type" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('booking_type') border-red-500 @enderror">
                    <option value="">اختر نوع الحجز</option>
                    <option value="inspection" {{ old('booking_type') == 'inspection' ? 'selected' : '' }}>معاينة فقط ({{ number_format($inspectionFee) }} جنيه)</option>
                    <option value="reservation" {{ old('booking_type') == 'reservation' ? 'selected' : '' }}>حجز الوحدة ({{ $reservationPercentage }}% من السعر - {{ number_format(($property->price * $reservationPercentage) / 100, 2) }} جنيه)</option>
                </select>
                @error('booking_type')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">تاريخ المعاينة *</label>
                <input type="date" name="inspection_date" required min="{{ date('Y-m-d', strtotime('+1 day')) }}" value="{{ old('inspection_date') }}"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('inspection_date') border-red-500 @enderror">
                @error('inspection_date')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">وقت المعاينة *</label>
                <input type="time" name="inspection_time" required value="{{ old('inspection_time') }}"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('inspection_time') border-red-500 @enderror">
                @error('inspection_time')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="bg-blue-50 p-4 rounded-lg mb-6">
                <p class="text-sm text-gray-700">
                    <strong>ملاحظة:</strong> 
                    <span id="amount_note">رسوم المعاينة {{ number_format($inspectionFee) }} جنيه. سيتم تحويلك لصفحة الدفع بعد تأكيد الحجز.</span>
                </p>
            </div>
            
            <div id="amount_display" class="bg-green-50 border-2 border-green-200 p-4 rounded-lg mb-6" style="display: none;">
                <div class="flex items-center justify-between">
                    <span class="text-gray-700 font-semibold">المبلغ المطلوب دفعه:</span>
                    <span id="amount_value" class="text-2xl font-bold text-green-600">0 جنيه</span>
                </div>
            </div>
            
            <div class="flex justify-end space-x-4 space-x-reverse">
                <a href="{{ route('property.show', $property) }}" 
                   class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                    إلغاء
                </a>
                <button type="submit" id="submitBtn"
                        class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                    تأكيد الحجز
                </button>
            </div>
        </form>
    </div>
</div>

<script>
const inspectionFee = {{ $inspectionFee }};
const reservationPercentage = {{ $reservationPercentage }};
const propertyPrice = {{ $property->price }};

document.getElementById('booking_type').addEventListener('change', function() {
    const note = document.getElementById('amount_note');
    const amountDisplay = document.getElementById('amount_display');
    const amountValue = document.getElementById('amount_value');
    
    if (this.value === 'reservation') {
        const reservationAmount = (propertyPrice * reservationPercentage) / 100;
        note.textContent = 'حجز الوحدة يتطلب دفع ' + reservationPercentage + '% من السعر كعربون. سيتم استرداد المبلغ إذا لم يتم الإيجار.';
        amountValue.textContent = new Intl.NumberFormat('ar-EG', { 
            minimumFractionDigits: 2, 
            maximumFractionDigits: 2 
        }).format(reservationAmount) + ' جنيه';
        amountDisplay.style.display = 'block';
    } else if (this.value === 'inspection') {
        note.textContent = 'رسوم المعاينة ' + new Intl.NumberFormat('ar-EG').format(inspectionFee) + ' جنيه. سيتم تحويلك لصفحة الدفع بعد تأكيد الحجز.';
        amountValue.textContent = new Intl.NumberFormat('ar-EG').format(inspectionFee) + ' جنيه';
        amountDisplay.style.display = 'block';
    } else {
        amountDisplay.style.display = 'none';
    }
});

// التحقق من البيانات قبل الإرسال
document.getElementById('bookingForm').addEventListener('submit', function(e) {
    const bookingType = document.querySelector('[name="booking_type"]').value;
    const inspectionDate = document.querySelector('[name="inspection_date"]').value;
    const inspectionTime = document.querySelector('[name="inspection_time"]').value;
    
    if (!bookingType || !inspectionDate || !inspectionTime) {
        e.preventDefault();
        alert('يرجى ملء جميع الحقول المطلوبة');
        return false;
    }
    
    // التحقق من التاريخ
    const tomorrow = new Date();
    tomorrow.setDate(tomorrow.getDate() + 1);
    tomorrow.setHours(0, 0, 0, 0);
    
    const selectedDate = new Date(inspectionDate);
    selectedDate.setHours(0, 0, 0, 0);
    
    if (selectedDate < tomorrow) {
        e.preventDefault();
        alert('يجب أن يكون تاريخ المعاينة من الغد فصاعداً');
        return false;
    }
    
    // تعطيل الزر لمنع الإرسال المتكرر
    const submitBtn = document.getElementById('submitBtn');
    submitBtn.disabled = true;
    submitBtn.textContent = 'جاري المعالجة...';
});
</script>
@endsection



