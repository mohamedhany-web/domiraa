{{-- CSRF Token Handler Script --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    function setSubmitButtonLoading(form, loading) {
        var btn = form.querySelector('button[type="submit"]');
        if (!btn) return;
        if (loading) {
            btn.disabled = true;
            btn.dataset.originalText = btn.innerHTML;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin" style="margin-left: 0.5rem;"></i> جاري الإرسال...';
        } else {
            btn.disabled = false;
            if (btn.dataset.originalText) btn.innerHTML = btn.dataset.originalText;
        }
    }

    function updateTokenInForm(form, token) {
        var tokenInput = form.querySelector('input[name="_token"]');
        if (tokenInput) {
            tokenInput.value = token;
        } else {
            var hiddenInput = document.createElement('input');
            hiddenInput.type = 'hidden';
            hiddenInput.name = '_token';
            hiddenInput.value = token;
            form.appendChild(hiddenInput);
        }
        var metaToken = document.querySelector('meta[name="csrf-token"]');
        if (metaToken) metaToken.content = token;
    }

    var forms = document.querySelectorAll('form[method="POST"], form[method="PUT"], form[method="PATCH"], form[method="DELETE"]');
    forms.forEach(function(form) {
        form.addEventListener('submit', function(e) {
            var refreshCsrf = form.dataset.refreshCsrf === '1' || form.id === 'guestBookingForm' || form.id === 'bookingForm' || form.id === 'roomBookingForm' || form.id === 'paymentForm';

            if (refreshCsrf && form.dataset.csrfRefreshed === '1') {
                form.removeAttribute('data-csrf-refreshed');
                return;
            }

            var tokenInput = form.querySelector('input[name="_token"]');
            var metaToken = document.querySelector('meta[name="csrf-token"]');
            if (tokenInput && metaToken) {
                tokenInput.value = metaToken.content;
            }

            if (refreshCsrf || !tokenInput || !tokenInput.value) {
                e.preventDefault();
                setSubmitButtonLoading(form, true);

                fetch('/csrf-token', {
                    method: 'GET',
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                })
                .then(function(response) { return response.json(); })
                .then(function(data) {
                    if (data.token) {
                        updateTokenInForm(form, data.token);
                        form.dataset.csrfRefreshed = '1';
                        form.submit();
                    } else {
                        setSubmitButtonLoading(form, false);
                        alert('حدث خطأ في الجلسة. يرجى تحديث الصفحة والمحاولة مرة أخرى.');
                        window.location.reload();
                    }
                })
                .catch(function(err) {
                    console.error('CSRF token refresh error:', err);
                    setSubmitButtonLoading(form, false);
                    alert('حدث خطأ في الاتصال. يرجى تحديث الصفحة والمحاولة مرة أخرى.');
                });
            }
        });
    });
});

window.addEventListener('unhandledrejection', function(event) {
    if (event.reason && event.reason.status === 419) {
        alert('انتهت صلاحية الجلسة. يرجى تحديث الصفحة والمحاولة مرة أخرى.');
        window.location.reload();
    }
});
</script>

