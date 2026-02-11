@extends('layouts.admin')

@section('title', 'المدفوعات والفواتير')
@section('page-title', 'المدفوعات والفواتير')

@push('styles')
<style>
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
    
    .stat-icon.green {
        background: linear-gradient(135deg, #8aa69d 0%, #6b8980 100%);
        color: white;
    }
    
    .stat-icon.orange {
        background: linear-gradient(135deg, #FB923C 0%, #F97316 100%);
        color: white;
    }
    
    .stat-icon.blue {
        background: linear-gradient(135deg, #60A5FA 0%, #2a4456 100%);
        color: white;
    }
    
    .stat-icon.red {
        background: linear-gradient(135deg, #F87171 0%, #EF4444 100%);
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
        line-height: 1;
    }
    
    .payments-section {
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
    
    .badge-completed {
        background: #D1FAE5;
        color: #536b63;
    }
    
    .badge-pending {
        background: #FEF3C7;
        color: #D97706;
    }
    
    .badge-refunded {
        background: #FEE2E2;
        color: #DC2626;
    }
    
    .badge-approved {
        background: #D1FAE5;
        color: #536b63;
    }
    
    .badge-rejected {
        background: #FEE2E2;
        color: #DC2626;
    }
    
    .action-buttons {
        display: flex;
        gap: 0.5rem;
        flex-wrap: wrap;
    }
    
    .btn-action {
        padding: 0.5rem 1rem;
        border-radius: 8px;
        border: none;
        cursor: pointer;
        font-size: 0.75rem;
        font-weight: 700;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .btn-review {
        background: #DBEAFE;
        color: #1d313f;
    }
    
    .btn-review:hover {
        background: #BFDBFE;
    }
    
    .btn-refund {
        background: #FEE2E2;
        color: #DC2626;
    }
    
    .btn-refund:hover {
        background: #FECACA;
    }
    
    .btn-view {
        background: #F3F4F6;
        color: #374151;
    }
    
    .btn-view:hover {
        background: #E5E7EB;
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
        max-width: 600px;
        width: 90%;
        max-height: 90vh;
        overflow-y: auto;
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
    
    .form-group {
        margin-bottom: 1.5rem;
    }
    
    .form-label {
        display: block;
        font-weight: 600;
        color: #374151;
        margin-bottom: 0.5rem;
        font-size: 0.875rem;
    }
    
    .form-select,
    .form-textarea {
        width: 100%;
        padding: 0.75rem;
        border: 2px solid #E5E7EB;
        border-radius: 8px;
        font-size: 0.875rem;
        font-family: 'Cairo', sans-serif;
    }
    
    .form-textarea {
        min-height: 100px;
        resize: vertical;
    }
    
    .receipt-preview {
        max-width: 100%;
        max-height: 400px;
        border-radius: 8px;
        margin-top: 1rem;
    }
    
    @media (max-width: 1400px) {
        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }
    
    @media (max-width: 768px) {
        .stats-grid {
            grid-template-columns: 1fr;
        }
        
        .table-container {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }
        
        .table {
            font-size: 0.75rem;
            min-width: 1000px;
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
                <div class="stat-label">إجمالي المدفوعات المؤكدة</div>
                <div class="stat-value">{{ number_format($totalPayments, 2) }} ج.م</div>
            </div>
            <div class="stat-icon green">
                <i class="fas fa-money-bill-wave"></i>
            </div>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-header">
            <div class="stat-content">
                <div class="stat-label">قيد المراجعة</div>
                <div class="stat-value">{{ $pendingPayments }}</div>
            </div>
            <div class="stat-icon orange">
                <i class="fas fa-clock"></i>
            </div>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-header">
            <div class="stat-content">
                <div class="stat-label">مقبولة</div>
                <div class="stat-value">{{ $approvedPayments }}</div>
            </div>
            <div class="stat-icon green">
                <i class="fas fa-check-circle"></i>
            </div>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-header">
            <div class="stat-content">
                <div class="stat-label">مرفوضة</div>
                <div class="stat-value">{{ $rejectedPayments }}</div>
            </div>
            <div class="stat-icon red">
                <i class="fas fa-times-circle"></i>
            </div>
        </div>
    </div>
</div>

<!-- Filter Tabs -->
<div style="display: flex; gap: 0.5rem; margin-bottom: 1.5rem; flex-wrap: wrap;">
    <a href="{{ route('admin.payments') }}" style="padding: 0.75rem 1.5rem; border-radius: 8px; border: 2px solid #E5E7EB; background: white; color: #6B7280; font-weight: 600; text-decoration: none; transition: all 0.3s ease; {{ !request('review_status') && !request('status') ? 'background: linear-gradient(135deg, #1d313f 0%, #6b8980 100%); color: white; border-color: transparent;' : '' }}">
        الكل ({{ $totalCount ?? 0 }})
    </a>
    <a href="{{ route('admin.payments', ['review_status' => 'pending']) }}" style="padding: 0.75rem 1.5rem; border-radius: 8px; border: 2px solid #E5E7EB; background: white; color: #6B7280; font-weight: 600; text-decoration: none; transition: all 0.3s ease; {{ request('review_status') == 'pending' ? 'background: linear-gradient(135deg, #1d313f 0%, #6b8980 100%); color: white; border-color: transparent;' : '' }}">
        قيد المراجعة
    </a>
    <a href="{{ route('admin.payments', ['review_status' => 'approved']) }}" style="padding: 0.75rem 1.5rem; border-radius: 8px; border: 2px solid #E5E7EB; background: white; color: #6B7280; font-weight: 600; text-decoration: none; transition: all 0.3s ease; {{ request('review_status') == 'approved' ? 'background: linear-gradient(135deg, #1d313f 0%, #6b8980 100%); color: white; border-color: transparent;' : '' }}">
        مقبولة
    </a>
    <a href="{{ route('admin.payments', ['review_status' => 'rejected']) }}" style="padding: 0.75rem 1.5rem; border-radius: 8px; border: 2px solid #E5E7EB; background: white; color: #6B7280; font-weight: 600; text-decoration: none; transition: all 0.3s ease; {{ request('review_status') == 'rejected' ? 'background: linear-gradient(135deg, #1d313f 0%, #6b8980 100%); color: white; border-color: transparent;' : '' }}">
        مرفوضة
    </a>
</div>

<!-- Payments Table -->
<div class="payments-section">
    <div class="section-header">
        <h2 class="section-title">
            <i class="fas fa-money-bill-wave"></i>
            سجل المدفوعات والفواتير
        </h2>
    </div>
    
    <div class="table-container">
        <table class="table">
            <thead>
                <tr>
                    <th>المستخدم</th>
                    <th>النوع</th>
                    <th>المبلغ</th>
                    <th>المحفظة</th>
                    <th>حالة المراجعة</th>
                    <th>الحالة</th>
                    <th>التاريخ</th>
                    <th>الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                @forelse($payments as $payment)
                <tr>
                    <td>
                        <div style="font-weight: 600; color: #1F2937;">{{ $payment->user->name ?? 'غير محدد' }}</div>
                        @if($payment->user)
                        <div style="font-size: 0.75rem; color: #6B7280;">{{ $payment->user->email ?? '' }}</div>
                        @endif
                        @if($payment->booking)
                        <div style="font-size: 0.75rem; color: #6B7280;">حجز #{{ $payment->booking->id }}</div>
                        @endif
                    </td>
                    <td>
                        <span style="font-weight: 600;">
                            {{ $payment->payment_type === 'inspection_fee' ? 'رسوم المعاينة' : ($payment->payment_type === 'reservation_fee' ? 'رسوم الحجز' : 'استرداد') }}
                        </span>
                    </td>
                    <td style="font-weight: 600;">{{ number_format($payment->amount, 2) }} ج.م</td>
                    <td>
                        @if($payment->wallet)
                            {{ $payment->wallet->name }}
                            @if($payment->wallet->bank_name)
                                - {{ $payment->wallet->bank_name }}
                            @endif
                        @else
                            <span style="color: #9CA3AF;">غير محدد</span>
                        @endif
                    </td>
                    <td>
                        @if($payment->review_status === 'approved')
                            <span class="badge badge-approved">مقبولة</span>
                        @elseif($payment->review_status === 'rejected')
                            <span class="badge badge-rejected">مرفوضة</span>
                        @else
                            <span class="badge badge-pending">قيد المراجعة</span>
                        @endif
                    </td>
                    <td>
                        @if($payment->status === 'completed')
                            <span class="badge badge-completed">مكتمل</span>
                        @elseif($payment->status === 'pending')
                            <span class="badge badge-pending">قيد الانتظار</span>
                        @elseif($payment->status === 'refunded')
                            <span class="badge badge-refunded">مسترد</span>
                        @else
                            <span class="badge" style="background: #FEE2E2; color: #DC2626;">فاشل</span>
                        @endif
                    </td>
                    <td>{{ $payment->created_at->format('Y-m-d H:i') }}</td>
                    <td>
                        <div class="action-buttons">
                            @if($payment->receipt_path)
                            <button type="button" class="btn-action btn-view" onclick="viewReceipt('{{ \App\Helpers\StorageHelper::url($payment->receipt_path) }}')">
                                <i class="fas fa-eye"></i> عرض الإيصال
                            </button>
                            @endif
                            
                            @if($payment->review_status === 'pending')
                            <button type="button" class="btn-action btn-review" onclick="openReviewModal({{ $payment->id }})">
                                <i class="fas fa-check"></i> مراجعة
                            </button>
                            @endif
                            
                            @if($payment->status === 'completed' && $payment->payment_type !== 'refund')
                            <form method="POST" action="{{ route('admin.payments.refund', $payment) }}" style="display: inline;" onsubmit="return confirm('هل أنت متأكد من استرداد هذا المبلغ؟');">
                                @csrf
                                <button type="submit" class="btn-action btn-refund">
                                    <i class="fas fa-undo"></i> استرداد
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" style="text-align: center; padding: 2rem; color: #6B7280;">
                        لا توجد مدفوعات
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    {{ $payments->links() }}
</div>

<!-- Review Modal -->
<div id="reviewModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3 class="modal-title">مراجعة الدفعة</h3>
            <button type="button" class="close-modal" onclick="closeReviewModal()">&times;</button>
        </div>
        <form id="reviewForm" method="POST">
            @csrf
            <div class="form-group">
                <label class="form-label">قرار المراجعة *</label>
                <select name="review_status" class="form-select" required>
                    <option value="">-- اختر القرار --</option>
                    <option value="approved">موافقة</option>
                    <option value="rejected">رفض</option>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">ملاحظات المراجعة</label>
                <textarea name="review_notes" class="form-textarea" placeholder="أدخل ملاحظات المراجعة (اختياري)"></textarea>
            </div>
            <div style="display: flex; gap: 1rem; justify-content: flex-end; margin-top: 1.5rem;">
                <button type="button" onclick="closeReviewModal()" style="padding: 0.75rem 1.5rem; background: #F3F4F6; color: #374151; border: none; border-radius: 8px; cursor: pointer; font-weight: 600;">
                    إلغاء
                </button>
                <button type="submit" style="padding: 0.75rem 1.5rem; background: linear-gradient(135deg, #1d313f 0%, #6b8980 100%); color: white; border: none; border-radius: 8px; cursor: pointer; font-weight: 700;">
                    <i class="fas fa-check"></i> تأكيد
                </button>
            </div>
        </form>
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
function openReviewModal(paymentId) {
    const modal = document.getElementById('reviewModal');
    const form = document.getElementById('reviewForm');
    form.action = `/admin/payments/${paymentId}/review`;
    modal.classList.add('active');
}

function closeReviewModal() {
    document.getElementById('reviewModal').classList.remove('active');
    document.getElementById('reviewForm').reset();
}

function viewReceipt(receiptPath) {
    const modal = document.getElementById('receiptModal');
    const image = document.getElementById('receiptImage');
    const pdf = document.getElementById('receiptPdf');
    
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

// إغلاق النوافذ عند النقر خارجها
window.onclick = function(event) {
    const reviewModal = document.getElementById('reviewModal');
    const receiptModal = document.getElementById('receiptModal');
    if (event.target === reviewModal) {
        closeReviewModal();
    }
    if (event.target === receiptModal) {
        closeReceiptModal();
    }
}
</script>
@endsection


