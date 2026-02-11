@extends('layouts.app')

@section('title', 'البحث عن عقارات')

@push('styles')
<style>
    /* Filter Section */
    .filter-section {
        background: white;
        border-radius: 16px;
        padding: 2rem;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        margin-bottom: 2rem;
        border: 1px solid #E5E7EB;
    }
    
    .filter-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: #1d313f;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .filter-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1.5rem;
    }
    
    .filter-group {
        display: flex;
        flex-direction: column;
    }
    
    .filter-label {
        font-weight: 600;
        color: #374151;
        margin-bottom: 0.5rem;
        font-size: 0.9rem;
    }
    
    .filter-input,
    .filter-select {
        padding: 0.75rem;
        border: 2px solid #E5E7EB;
        border-radius: 8px;
        font-size: 0.9rem;
        transition: all 0.3s ease;
    }
    
    .filter-input:focus,
    .filter-select:focus {
        outline: none;
        border-color: #1d313f;
        box-shadow: 0 0 0 3px rgba(29, 49, 63, 0.1);
    }
    
    .filter-buttons {
        display: flex;
        gap: 1rem;
        margin-top: 1.5rem;
    }
    
    .btn-filter {
        background: linear-gradient(135deg, #1d313f 0%, #6b8980 100%);
        color: white;
        font-weight: 700;
        padding: 0.75rem 2rem;
        border-radius: 8px;
        border: none;
        cursor: pointer;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .btn-filter:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(29, 49, 63, 0.3);
    }
    
    .btn-reset {
        background: #F3F4F6;
        color: #374151;
        font-weight: 700;
        padding: 0.75rem 2rem;
        border-radius: 8px;
        border: none;
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .btn-reset:hover {
        background: #E5E7EB;
    }
    
    /* Results Section */
    .results-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
    }
    
    .results-count {
        font-size: 1.25rem;
        font-weight: 700;
        color: #1F2937;
    }
    
    .properties-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
        gap: 2rem;
        margin-bottom: 2rem;
    }
    
    .property-card {
        background: white;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        transition: all 0.3s ease;
        border: 1px solid #E5E7EB;
    }
    
    .property-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12);
    }
    
    .property-image {
        height: 240px;
        overflow: hidden;
        background: linear-gradient(135deg, rgba(29, 49, 63, 0.1) 0%, rgba(107, 137, 128, 0.1) 100%);
        position: relative;
    }
    
    .property-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }
    
    .property-card:hover .property-image img {
        transform: scale(1.1);
    }
    
    .property-badge {
        position: absolute;
        top: 1rem;
        right: 1rem;
        background: rgba(255, 255, 255, 0.95);
        padding: 0.5rem 1rem;
        border-radius: 8px;
        font-weight: 700;
        font-size: 0.75rem;
        backdrop-filter: blur(10px);
    }
    
    .property-badge.residential {
        color: #1d313f;
    }
    
    .property-badge.commercial {
        color: #6b8980;
    }
    
    .property-content {
        padding: 1.5rem;
    }
    
    .property-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: #1F2937;
        margin-bottom: 0.75rem;
    }
    
    .property-location {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: #6B7280;
        font-size: 0.9rem;
        margin-bottom: 1rem;
    }
    
    .property-details {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-top: 1rem;
        border-top: 1px solid #F3F4F6;
    }
    
    .property-price {
        font-size: 1.5rem;
        font-weight: 800;
        color: #1d313f;
    }
    
    .property-price-type {
        font-size: 0.875rem;
        color: #6B7280;
    }
    
    .btn-view {
        background: linear-gradient(135deg, #1d313f 0%, #6b8980 100%);
        color: white;
        font-weight: 700;
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        text-decoration: none;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .btn-view:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(29, 49, 63, 0.3);
    }
    
    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
        background: white;
        border-radius: 16px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    }
    
    .empty-state-icon {
        font-size: 4rem;
        color: #9CA3AF;
        margin-bottom: 1rem;
    }
    
    .empty-state-text {
        font-size: 1.25rem;
        font-weight: 600;
        color: #6B7280;
        margin-bottom: 0.5rem;
    }
    
    .pagination {
        display: flex;
        justify-content: center;
        gap: 0.5rem;
        margin-top: 2rem;
    }
    
    .pagination-link {
        padding: 0.75rem 1rem;
        border-radius: 8px;
        text-decoration: none;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    
    .pagination-link.active {
        background: linear-gradient(135deg, #1d313f 0%, #6b8980 100%);
        color: white;
    }
    
    .pagination-link:not(.active) {
        background: #F3F4F6;
        color: #374151;
    }
    
    .pagination-link:not(.active):hover {
        background: #E5E7EB;
    }
    
    .price-slider {
        flex: 1;
        height: 8px;
        border-radius: 5px;
        background: #E5E7EB;
        outline: none;
        -webkit-appearance: none;
        cursor: pointer;
    }
    
    .price-slider::-webkit-slider-thumb {
        -webkit-appearance: none;
        appearance: none;
        width: 20px;
        height: 20px;
        border-radius: 50%;
        background: linear-gradient(135deg, #1d313f 0%, #6b8980 100%);
        cursor: pointer;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);
        transition: transform 0.2s ease;
    }
    
    .price-slider::-webkit-slider-thumb:active {
        transform: scale(1.2);
    }
    
    .price-slider::-moz-range-thumb {
        width: 20px;
        height: 20px;
        border-radius: 50%;
        background: linear-gradient(135deg, #1d313f 0%, #6b8980 100%);
        cursor: pointer;
        border: none;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);
        transition: transform 0.2s ease;
    }
    
    .price-slider::-moz-range-thumb:active {
        transform: scale(1.2);
    }
    
    /* Touch-friendly improvements for mobile */
    @media (max-width: 768px) {
        .filter-input,
        .filter-select {
            -webkit-appearance: none;
            appearance: none;
            touch-action: manipulation;
        }
        
        .filter-select {
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%231d313f' d='M6 9L1 4h10z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: left 0.75rem center;
            padding-left: 2.5rem;
            padding-right: 0.75rem;
        }
        
        /* Prevent zoom on input focus (iOS) */
        .filter-input,
        .filter-select {
            font-size: 16px !important;
        }
    }
    
    @media (max-width: 768px) {
        /* Ensure full width on mobile */
        .max-w-7xl {
            width: 100%;
            padding-left: 0.75rem;
            padding-right: 0.75rem;
        }
        
        .filter-section {
            padding: 1.25rem;
            border-radius: 16px;
            margin-bottom: 1.5rem;
        }
        
        .filter-title {
            font-size: 1.125rem;
            margin-bottom: 1rem;
        }
        
        .filter-grid {
            grid-template-columns: 1fr;
            gap: 1rem;
        }
        
        .filter-group {
            margin-bottom: 0;
        }
        
        .filter-group[style*="grid-column"] {
            grid-column: span 1 !important;
        }
        
        .filter-label {
            font-size: 0.875rem;
            margin-bottom: 0.5rem;
        }
        
        .filter-input,
        .filter-select {
            padding: 0.75rem;
            font-size: 0.9rem;
            border-radius: 8px;
        }
        
        .filter-buttons {
            flex-direction: column;
            width: 100%;
            margin-top: 1rem;
            gap: 0.75rem;
        }
        
        .btn-filter,
        .btn-reset {
            width: 100%;
            justify-content: center;
            padding: 0.875rem 1.5rem;
            font-size: 0.95rem;
        }
        
        /* Price slider mobile improvements */
        .price-range-group {
            grid-column: span 1 !important;
        }
        
        .price-slider-container {
            flex-direction: column !important;
            gap: 0.75rem !important;
            align-items: stretch !important;
        }
        
        .price-slider {
            width: 100%;
            margin: 0;
        }
        
        .price-display {
            min-width: auto !important;
            width: 100%;
            justify-content: center;
            padding: 0.5rem;
            background: #F9FAFB;
            border-radius: 8px;
        }
        
        .properties-grid {
            grid-template-columns: 1fr;
            gap: 1.5rem;
        }
        
        .property-card {
            border-radius: 12px;
        }
        
        .property-image {
            height: 200px;
        }
        
        .property-content {
            padding: 1.25rem;
        }
        
        .property-title {
            font-size: 1.1rem;
        }
        
        .property-price {
            font-size: 1.25rem;
        }
        
        .results-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }
        
        .results-count {
            font-size: 1.1rem;
        }
    }
    
    @media (max-width: 480px) {
        .filter-section {
            padding: 1rem;
            border-radius: 12px;
        }
        
        .filter-title {
            font-size: 1rem;
            margin-bottom: 0.875rem;
        }
        
        .filter-grid {
            gap: 0.875rem;
        }
        
        .filter-input,
        .filter-select {
            padding: 0.625rem;
            font-size: 0.875rem;
        }
        
        .filter-label {
            font-size: 0.8rem;
        }
        
        .price-slider {
            height: 6px;
        }
        
        .price-slider::-webkit-slider-thumb {
            width: 18px;
            height: 18px;
        }
        
        .price-slider::-moz-range-thumb {
            width: 18px;
            height: 18px;
        }
        
        .property-image {
            height: 180px;
        }
        
        .results-count {
            font-size: 1rem;
        }
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const filterForm = document.getElementById('filterForm');
    const searchAddress = document.getElementById('searchAddress');
    const searchStatus = document.getElementById('searchStatus');
    const searchPriceType = document.getElementById('searchPriceType');
    const searchPropertyType = document.getElementById('searchPropertyType');
    const priceRange = document.getElementById('priceRange');
    const priceRangeValue = document.getElementById('priceRangeValue');
    const maxPrice = document.getElementById('maxPrice');
    const searchResults = document.getElementById('searchResults');
    const resultsCountText = document.getElementById('resultsCountText');
    
    if (!filterForm || !searchAddress || !searchResults || !resultsCountText) {
        console.error('Missing required elements');
        return;
    }
    
    let isLoading = false;
    
    // Search function using AJAX
    function performSearch() {
        if (isLoading) return;
        
            isLoading = true;
            
            // Show loading indicator
            if (searchResults) {
                searchResults.innerHTML = '<div style="text-align: center; padding: 3rem;"><i class="fas fa-spinner fa-spin" style="font-size: 2rem; color: #6b8980;"></i><p style="margin-top: 1rem; color: #6B7280;">جاري البحث...</p></div>';
            }
            
            // Get form data
            const formData = new FormData(filterForm);
            const params = new URLSearchParams();
            
            for (const [key, value] of formData.entries()) {
                if (value && value.trim() !== '') {
                    params.append(key, value);
                }
            }
            
            const url = '{{ route("search.ajax") }}?' + params.toString();
            console.log('Searching:', url);
            
            // Make AJAX request
            fetch(url, {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                console.log('Search results:', data);
                if (searchResults && data.html) {
                    searchResults.innerHTML = data.html;
                }
                if (resultsCountText && data.count !== undefined) {
                    resultsCountText.textContent = data.count + ' عقار متاح';
                }
                isLoading = false;
                
                // Update URL without reload
                const newUrl = '{{ route("search") }}?' + params.toString();
                window.history.pushState({path: newUrl}, '', newUrl);
            })
            .catch(error => {
                console.error('Search Error:', error);
                if (searchResults) {
                    searchResults.innerHTML = '<div class="empty-state"><div class="empty-state-text">حدث خطأ أثناء البحث. يرجى المحاولة مرة أخرى.</div></div>';
                }
                isLoading = false;
            });
    }
    
    // Price slider - only update display, don't search
    if (priceRange) {
        priceRange.addEventListener('input', function() {
            const value = parseInt(this.value);
            if (priceRangeValue) {
                priceRangeValue.textContent = new Intl.NumberFormat('ar-EG').format(value);
            }
            if (maxPrice) {
                maxPrice.value = value;
            }
        });
        
        // Initialize price display
        if (priceRangeValue) {
            const initialPrice = parseInt(priceRange.value) || 50000;
            priceRangeValue.textContent = new Intl.NumberFormat('ar-EG').format(initialPrice);
        }
    }
    
    // Form submission - perform search on button click
    if (filterForm) {
        filterForm.addEventListener('submit', function(e) {
            e.preventDefault();
            performSearch();
        });
    }
    
    console.log('Search script loaded successfully');
});
</script>
@endpush

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Filter Section -->
    <div class="filter-section">
        <h2 class="filter-title">
            <i class="fas fa-filter"></i>
            فلترة البحث
        </h2>
        
        <form method="GET" action="{{ route('search') }}" id="filterForm">
            <div class="filter-grid">
                <div class="filter-group">
                    <label class="filter-label">المنطقة / العنوان</label>
                    <input type="text" name="address" id="searchAddress" class="filter-input" placeholder="ابحث عن منطقة..." value="{{ request('address') }}">
                </div>
                
                <div class="filter-group">
                    <label class="filter-label">نوع الوحدة</label>
                    <select name="property_type_id" id="searchPropertyType" class="filter-select">
                        <option value="">الكل</option>
                        @foreach(\App\Models\PropertyType::active() as $type)
                        <option value="{{ $type->id }}" {{ request('property_type_id') == $type->id ? 'selected' : '' }}>
                            {{ $type->name }}
                        </option>
                        @endforeach
                    </select>
                </div>
                
                <div class="filter-group">
                    <label class="filter-label">الحالة</label>
                    <select name="status" id="searchStatus" class="filter-select">
                        <option value="">الكل</option>
                        <option value="furnished" {{ request('status') == 'furnished' ? 'selected' : '' }}>مفروش</option>
                        <option value="unfurnished" {{ request('status') == 'unfurnished' ? 'selected' : '' }}>على البلاط</option>
                    </select>
                </div>
                
                <div class="filter-group">
                    <label class="filter-label">نوع السعر</label>
                    <select name="price_type" id="searchPriceType" class="filter-select">
                        <option value="">الكل</option>
                        <option value="daily" {{ request('price_type') == 'daily' ? 'selected' : '' }}>يومي</option>
                        <option value="monthly" {{ request('price_type') == 'monthly' ? 'selected' : '' }}>شهري</option>
                        <option value="yearly" {{ request('price_type') == 'yearly' ? 'selected' : '' }}>سنوي</option>
                    </select>
                </div>
                
                <div class="filter-group price-range-group" style="grid-column: span 2;">
                    <label class="filter-label">نطاق السعر</label>
                    <div class="price-slider-container" style="display: flex; align-items: center; gap: 1rem; margin-top: 0.5rem;">
                        <input type="range" name="price_range" id="priceRange" class="price-slider" min="0" max="100000" value="{{ request('price_range', 50000) }}" step="1000">
                        <div class="price-display" style="display: flex; align-items: center; gap: 0.5rem; min-width: 200px;">
                            <span id="priceRangeValue" style="font-weight: 700; color: #1d313f; font-size: 1.1rem;">{{ number_format(request('price_range', 50000)) }}</span>
                            <span style="color: #6B7280;">ج.م</span>
                        </div>
                    </div>
                    <input type="hidden" name="max_price" id="maxPrice" value="{{ request('max_price', 50000) }}">
                </div>
            </div>
            
            <div class="filter-buttons">
                <button type="submit" class="btn-filter">
                    <i class="fas fa-search"></i>
                    بحث
                </button>
                <a href="{{ route('search') }}" class="btn-reset">
                    <i class="fas fa-redo"></i>
                    إعادة تعيين
                </a>
            </div>
        </form>
    </div>
    
    <!-- Results Section -->
    <div class="results-header">
        <div class="results-count" id="resultsCount">
            <i class="fas fa-building ml-2"></i>
            <span id="resultsCountText">{{ $properties->total() }} عقار متاح</span>
        </div>
    </div>
    
    <div id="searchResults">
        @include('tenant.search-results', ['properties' => $properties])
    </div>
</div>
@endsection


