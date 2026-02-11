@extends('layouts.app')

@section('title', 'لوحة تحكم المستأجر')

@section('content')
<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="px-4 py-6 sm:px-0">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-900">حجوزاتي</h1>
            <div class="flex gap-3">
                <a href="{{ route('tenant.inquiries.index') }}" 
                   class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors duration-200 flex items-center gap-2">
                    <i class="fas fa-comments"></i>
                    استفساراتي
                </a>
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit" 
                            class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition-colors duration-200 flex items-center gap-2">
                        <i class="fas fa-sign-out-alt"></i>
                        تسجيل الخروج
                    </button>
                </form>
            </div>
        </div>
        
        <div class="bg-white shadow overflow-hidden sm:rounded-md">
            <ul class="divide-y divide-gray-200">
                @forelse($bookings as $booking)
                <li class="px-4 py-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            @if($booking->property->images->first())
                            <img class="h-16 w-16 rounded-lg object-cover" src="{{ $booking->property->images->first()->url }}" alt="" onerror="this.src='{{ \App\Helpers\StorageHelper::placeholder() }}'; this.onerror=null;">
                            @endif
                            <div class="mr-4">
                                <p class="text-sm font-medium text-gray-900">{{ $booking->property->address }}</p>
                                <p class="text-sm text-gray-500">تاريخ المعاينة: {{ $booking->inspection_date->format('Y-m-d') }} في {{ $booking->inspection_time }}</p>
                                <p class="text-sm text-gray-500">المبلغ: {{ $booking->amount }} جنيه</p>
                            </div>
                        </div>
                        <span class="px-2 py-1 text-xs rounded-full 
                            @if($booking->status === 'pending') bg-yellow-100 text-yellow-800
                            @elseif($booking->status === 'confirmed') bg-green-100 text-green-800
                            @else bg-gray-100 text-gray-800
                            @endif">
                            {{ $booking->status === 'pending' ? 'في الانتظار' : ($booking->status === 'confirmed' ? 'مؤكد' : 'مكتمل') }}
                        </span>
                    </div>
                </li>
                @empty
                <li class="px-4 py-8 text-center text-gray-500">
                    لا توجد حجوزات حالياً
                </li>
                @endforelse
            </ul>
        </div>
    </div>
</div>
@endsection



