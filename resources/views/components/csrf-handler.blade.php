{{-- CSRF Token Handler Script --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Refresh CSRF token before form submission if needed
    const forms = document.querySelectorAll('form[method="POST"], form[method="PUT"], form[method="PATCH"], form[method="DELETE"]');
    
    forms.forEach(function(form) {
        form.addEventListener('submit', function(e) {
            const tokenInput = form.querySelector('input[name="_token"]');
            const metaToken = document.querySelector('meta[name="csrf-token"]');
            
            if (tokenInput && metaToken) {
                // Update token from meta tag if available
                tokenInput.value = metaToken.content;
            }
            
            // If token is missing, try to get fresh one
            if (!tokenInput || !tokenInput.value) {
                e.preventDefault();
                
                fetch('/csrf-token', {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.token) {
                        if (tokenInput) {
                            tokenInput.value = data.token;
                        } else {
                            const hiddenInput = document.createElement('input');
                            hiddenInput.type = 'hidden';
                            hiddenInput.name = '_token';
                            hiddenInput.value = data.token;
                            form.appendChild(hiddenInput);
                        }
                        
                        // Update meta tag
                        if (metaToken) {
                            metaToken.content = data.token;
                        }
                        
                        // Retry form submission
                        form.submit();
                    } else {
                        alert('حدث خطأ في الجلسة. يرجى تحديث الصفحة والمحاولة مرة أخرى.');
                        window.location.reload();
                    }
                })
                .catch(error => {
                    console.error('CSRF token refresh error:', error);
                    alert('حدث خطأ في الجلسة. يرجى تحديث الصفحة والمحاولة مرة أخرى.');
                    window.location.reload();
                });
            }
        });
    });
    
    // Handle 419 errors globally
    document.addEventListener('submit', function(e) {
        const form = e.target;
        if (form.tagName === 'FORM') {
            const originalSubmit = form.onsubmit;
            
            // Intercept form submission to handle CSRF errors
            form.addEventListener('submit', function(event) {
                // This will be handled by the form's natural submission
                // If we get a 419 error, it will be caught by the error handler below
            });
        }
    });
});

// Global error handler for AJAX requests
window.addEventListener('unhandledrejection', function(event) {
    if (event.reason && event.reason.status === 419) {
        alert('انتهت صلاحية الجلسة. يرجى تحديث الصفحة والمحاولة مرة أخرى.');
        window.location.reload();
    }
});
</script>

