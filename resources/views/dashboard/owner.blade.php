@extends('layouts.app')

@section('title', 'لوحة تحكم المؤجر')

@section('content')
<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="px-4 py-6 sm:px-0">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-900">لوحة تحكم المؤجر</h1>
            <a href="{{ route('owner.properties.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                إضافة وحدة جديدة
            </a>
        </div>
        
        <div class="mb-8">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">وحداتي</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($properties as $property)
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    @if($property->images->first())
                    <img class="w-full h-48 object-cover" src="{{ $property->images->first()->url }}" alt="" onerror="this.src='{{ \App\Helpers\StorageHelper::placeholder() }}'; this.onerror=null;">
                    @endif
                    <div class="p-4">
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ $property->address }}</h3>
                        <p class="text-gray-600 mb-2">{{ $property->price }} {{ $property->price_type === 'monthly' ? 'شهرياً' : ($property->price_type === 'yearly' ? 'سنوياً' : 'يومياً') }}</p>
                        <span class="px-2 py-1 text-xs rounded-full 
                            @if($property->admin_status === 'pending') bg-yellow-100 text-yellow-800
                            @elseif($property->admin_status === 'approved') bg-green-100 text-green-800
                            @else bg-red-100 text-red-800
                            @endif">
                            @if($property->admin_status === 'pending') في الانتظار
                            @elseif($property->admin_status === 'approved') موافق عليها
                            @else مرفوضة
                            @endif
                        </span>
                        <div class="mt-4">
                            <a href="{{ route('owner.properties.show', $property) }}" class="text-blue-600 hover:text-blue-900">عرض التفاصيل</a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        
        <div>
            <h2 class="text-xl font-semibold text-gray-900 mb-4">الحجوزات</h2>
            <div class="bg-white shadow overflow-hidden sm:rounded-md">
                <ul class="divide-y divide-gray-200">
                    @foreach($bookings as $booking)
                    <li class="px-4 py-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-900">{{ $booking->property->address }}</p>
                                <p class="text-sm text-gray-500">المستأجر: {{ $booking->user->name }}</p>
                                <p class="text-sm text-gray-500">تاريخ المعاينة: {{ $booking->inspection_date->format('Y-m-d') }} في {{ $booking->inspection_time }}</p>
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
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection



