@if($properties->count() > 0)
<div class="properties-grid">
    @foreach($properties as $property)
    <div class="property-card">
        <div class="property-image">
            @if($property->images->first())
                <img src="{{ $property->images->first()->thumbnail_url }}" 
                     data-src="{{ $property->images->first()->url }}"
                     alt="{{ $property->address }}"
                     loading="lazy"
                     onerror="this.onerror=null; this.src='/images/placeholder.svg';">
            @else
                <img src="/images/placeholder.svg" 
                     alt="{{ $property->address }}"
                     loading="lazy">
            @endif
            <div class="property-badge {{ $property->propertyType->slug ?? '' }}">
                {{ $property->propertyType->name ?? 'غير محدد' }}
            </div>
        </div>
        
        <div class="property-content">
            <h3 class="property-title">{{ Str::limit($property->address, 40) }}</h3>
            
            <div class="property-location">
                <i class="fas fa-map-marker-alt"></i>
                <span>{{ Str::limit($property->address, 30) }}</span>
            </div>
            
            <div style="display: flex; gap: 1rem; margin-bottom: 1rem; flex-wrap: wrap;">
                <div style="display: flex; align-items: center; gap: 0.5rem; color: #6B7280; font-size: 0.875rem;">
                    <i class="fas fa-home"></i>
                    <span>{{ $property->status === 'furnished' ? 'مفروش' : 'على البلاط' }}</span>
                </div>
                @if($property->contract_duration)
                <div style="display: flex; align-items: center; gap: 0.5rem; color: #6B7280; font-size: 0.875rem;">
                    <i class="fas fa-calendar"></i>
                    <span>
                        {{ $property->contract_duration }} 
                        @if($property->contract_duration_type)
                            @if($property->contract_duration_type === 'daily') يوم
                            @elseif($property->contract_duration_type === 'weekly') أسبوع
                            @elseif($property->contract_duration_type === 'monthly') شهر
                            @elseif($property->contract_duration_type === 'yearly') سنة
                            @endif
                        @else
                            سنة
                        @endif
                    </span>
                </div>
                @endif
            </div>
            
            <div class="property-details">
                <div>
                    @if($property->is_room_rentable)
                    <div class="property-price" style="color: var(--primary, #3B82F6); font-weight: 600;">
                        <i class="fas fa-users" style="margin-left: 0.5rem;"></i>
                        قابلة للمشاركة
                    </div>
                    <div class="property-price-type" style="color: #6B7280; font-size: 0.875rem;">
                        غرف متاحة للإيجار
                    </div>
                    @else
                    <div class="property-price">{{ number_format($property->price) }}</div>
                    <div class="property-price-type">
                        {{ $property->price_type === 'monthly' ? 'شهري' : ($property->price_type === 'yearly' ? 'سنوي' : 'يومي') }}
                    </div>
                    @endif
                </div>
                <a href="{{ route('property.show', $property) }}" class="btn-view">
                    <i class="fas fa-eye"></i>
                    عرض التفاصيل
                </a>
            </div>
        </div>
    </div>
    @endforeach
</div>

<!-- Pagination -->
@if($properties->hasPages())
<div class="pagination">
    {{ $properties->links() }}
</div>
@endif
@else
<div class="empty-state">
    <div class="empty-state-icon">
        <i class="fas fa-search"></i>
    </div>
    <div class="empty-state-text">لم يتم العثور على عقارات</div>
    <p style="color: #9CA3AF; margin-top: 0.5rem;">جرب تغيير معايير البحث</p>
</div>
@endif

