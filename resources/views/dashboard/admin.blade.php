@extends('layouts.app')

@section('title', 'لوحة تحكم الأدمن')

@section('content')
<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="px-4 py-6 sm:px-0">
        <h1 class="text-3xl font-bold text-gray-900 mb-6">لوحة تحكم الأدمن</h1>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-yellow-500 rounded-md p-3">
                            <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="mr-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">في انتظار المراجعة</dt>
                                <dd class="text-lg font-medium text-gray-900">{{ $pendingProperties }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-green-500 rounded-md p-3">
                            <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="mr-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">موافق عليها</dt>
                                <dd class="text-lg font-medium text-gray-900">{{ $approvedProperties }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-blue-500 rounded-md p-3">
                            <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                        </div>
                        <div class="mr-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">إجمالي الوحدات</dt>
                                <dd class="text-lg font-medium text-gray-900">{{ $properties->count() }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="bg-white shadow overflow-hidden sm:rounded-md">
            <ul class="divide-y divide-gray-200">
                @foreach($properties as $property)
                <li>
                    <div class="px-4 py-4 sm:px-6">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                @if($property->images->first())
                                <img class="h-16 w-16 rounded-lg object-cover" src="{{ $property->images->first()->url }}" alt="" onerror="this.src='{{ \App\Helpers\StorageHelper::placeholder() }}'; this.onerror=null;">
                                @endif
                                <div class="mr-4">
                                    <p class="text-sm font-medium text-gray-900">{{ $property->address }}</p>
                                    <p class="text-sm text-gray-500">{{ $property->user->name }} - {{ $property->user->phone }}</p>
                                    <p class="text-sm text-gray-500">{{ $property->price }} {{ $property->price_type === 'monthly' ? 'شهرياً' : ($property->price_type === 'yearly' ? 'سنوياً' : 'يومياً') }}</p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-2 space-x-reverse">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    @if($property->admin_status === 'pending') bg-yellow-100 text-yellow-800
                                    @elseif($property->admin_status === 'approved') bg-green-100 text-green-800
                                    @else bg-red-100 text-red-800
                                    @endif">
                                    @if($property->admin_status === 'pending') في الانتظار
                                    @elseif($property->admin_status === 'approved') موافق عليها
                                    @else مرفوضة
                                    @endif
                                </span>
                                <a href="{{ route('admin.properties.review', $property) }}" class="text-blue-600 hover:text-blue-900">مراجعة</a>
                            </div>
                        </div>
                    </div>
                </li>
                @endforeach
            </ul>
        </div>
    </div>
</div>
@endsection



