@extends('layouts.owner')

@section('title', 'المدفوعات - منصة دوميرا')
@section('page-title', 'المدفوعات والعمولات')

@section('content')
<!-- Stats -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
    <div style="background: white; border-radius: 16px; padding: 1.5rem; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);">
        <div style="font-size: 2rem; font-weight: 800; color: #6b8980; margin-bottom: 0.5rem;">{{ number_format($stats['total_earnings'], 2) }}</div>
        <div style="color: #6B7280; font-weight: 600;">إجمالي الأرباح (ج.م)</div>
    </div>
    <div style="background: white; border-radius: 16px; padding: 1.5rem; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);">
        <div style="font-size: 2rem; font-weight: 800; color: #F59E0B; margin-bottom: 0.5rem;">{{ $stats['pending'] }}</div>
        <div style="color: #6B7280; font-weight: 600;">قيد الانتظار</div>
    </div>
    <div style="background: white; border-radius: 16px; padding: 1.5rem; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);">
        <div style="font-size: 2rem; font-weight: 800; color: #6b8980; margin-bottom: 0.5rem;">{{ $stats['completed'] }}</div>
        <div style="color: #6B7280; font-weight: 600;">مكتملة</div>
    </div>
</div>

@if($payments->count() > 0)
<div style="background: white; border-radius: 16px; padding: 1.5rem; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);">
    <table style="width: 100%; border-collapse: collapse;">
        <thead>
            <tr style="border-bottom: 2px solid #E5E7EB;">
                <th style="padding: 1rem; text-align: right; font-weight: 700; color: #374151;">المبلغ</th>
                <th style="padding: 1rem; text-align: right; font-weight: 700; color: #374151;">الوحدة</th>
                <th style="padding: 1rem; text-align: right; font-weight: 700; color: #374151;">المستأجر</th>
                <th style="padding: 1rem; text-align: right; font-weight: 700; color: #374151;">التاريخ</th>
                <th style="padding: 1rem; text-align: right; font-weight: 700; color: #374151;">الحالة</th>
            </tr>
        </thead>
        <tbody>
            @foreach($payments as $payment)
            <tr style="border-bottom: 1px solid #E5E7EB;">
                <td style="padding: 1rem; color: #1F2937; font-weight: 700;">{{ number_format($payment->amount, 2) }} ج.م</td>
                <td style="padding: 1rem; color: #6B7280;">{{ $payment->booking->property->address ?? 'غير محدد' }}</td>
                <td style="padding: 1rem; color: #6B7280;">{{ $payment->user->name ?? 'غير محدد' }}</td>
                <td style="padding: 1rem; color: #6B7280;">{{ $payment->created_at->format('Y-m-d') }}</td>
                <td style="padding: 1rem;">
                    @if($payment->status == 'completed')
                    <span style="background: #D1FAE5; color: #536b63; padding: 0.5rem 1rem; border-radius: 8px; font-weight: 600; font-size: 0.875rem;">مكتمل</span>
                    @else
                    <span style="background: #FEF3C7; color: #D97706; padding: 0.5rem 1rem; border-radius: 8px; font-weight: 600; font-size: 0.875rem;">قيد الانتظار</span>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

{{ $payments->links() }}
@else
<div style="background: white; border-radius: 16px; padding: 4rem 2rem; text-align: center; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);">
    <i class="fas fa-money-bill-wave" style="font-size: 4rem; color: #9CA3AF; margin-bottom: 1.5rem; opacity: 0.5;"></i>
    <h3 style="font-size: 1.5rem; font-weight: 700; color: #1F2937; margin-bottom: 0.5rem;">لا توجد مدفوعات</h3>
    <p style="color: #6B7280;">لم يتم استلام أي مدفوعات بعد</p>
</div>
@endif
@endsection



