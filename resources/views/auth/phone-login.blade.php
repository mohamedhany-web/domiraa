@extends('layouts.app')

@section('title', 'تسجيل الدخول')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-blue-50 to-indigo-100 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8 bg-white p-8 rounded-lg shadow-xl">
        <div>
            <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                تسجيل الدخول
            </h2>
            <p class="mt-2 text-center text-sm text-gray-600">
                أدخل رقم الهاتف لتلقي رمز التحقق
            </p>
        </div>
        <form class="mt-8 space-y-6" id="loginForm">
            @csrf
            <div class="rounded-md shadow-sm -space-y-px">
                <div>
                    <label for="phone" class="sr-only">رقم الهاتف</label>
                    <input id="phone" name="phone" type="tel" required 
                           class="appearance-none rounded-md relative block w-full px-3 py-3 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm" 
                           placeholder="رقم الهاتف (مثال: 01234567890)" 
                           pattern="[0-9]{10,15}">
                </div>
            </div>

            <div id="codeSection" class="hidden">
                <div class="rounded-md shadow-sm -space-y-px mb-4">
                    <div>
                        <label for="code" class="sr-only">رمز التحقق</label>
                        <input id="code" name="code" type="text" maxlength="4" 
                               class="appearance-none rounded-md relative block w-full px-3 py-3 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm text-center text-2xl tracking-widest" 
                               placeholder="0000">
                    </div>
                </div>
            </div>

            <div>
                <button type="submit" id="submitBtn" 
                        class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <span id="btnText">إرسال رمز التحقق</span>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.getElementById('loginForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    const phone = document.getElementById('phone').value;
    const code = document.getElementById('code').value;
    const submitBtn = document.getElementById('submitBtn');
    const btnText = document.getElementById('btnText');
    const codeSection = document.getElementById('codeSection');
    
    if (!code) {
        // إرسال الكود
        submitBtn.disabled = true;
        btnText.textContent = 'جاري الإرسال...';
        
        try {
            const response = await fetch('{{ route("send-code") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}'
                },
                body: JSON.stringify({ phone })
            });
            
            const data = await response.json();
            if (response.ok) {
                codeSection.classList.remove('hidden');
                btnText.textContent = 'تأكيد الرمز';
                alert('تم إرسال رمز التحقق: ' + data.code); // في الإنتاج يجب إزالة هذا
            } else {
                alert('حدث خطأ: ' + (data.message || 'يرجى المحاولة مرة أخرى'));
            }
        } catch (error) {
            alert('حدث خطأ في الاتصال');
        } finally {
            submitBtn.disabled = false;
        }
    } else {
        // التحقق من الكود
        submitBtn.disabled = true;
        btnText.textContent = 'جاري التحقق...';
        
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route("verify-code") }}';
        form.innerHTML = `
            @csrf
            <input type="hidden" name="phone" value="${phone}">
            <input type="hidden" name="code" value="${code}">
        `;
        document.body.appendChild(form);
        form.submit();
    }
});
</script>
@endsection



