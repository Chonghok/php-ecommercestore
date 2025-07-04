document.addEventListener('DOMContentLoaded', function() {
    // Payment method toggle
    const paymentDropdown = document.querySelector('select[name="paymentMethod"]');
    const qrPayment = document.getElementById('qr-payment');
    
    if (paymentDropdown && qrPayment) {
        paymentDropdown.addEventListener('change', function() {
            qrPayment.style.display = this.value === 'qr' ? 'flex' : 'none';
        });
    }

    // Order confirmation handling
    const checkoutForm = document.querySelector('.checkout-form');
    const confirmationModal = document.getElementById('confirmationModal');
    const cancelBtn = document.getElementById('cancelRemove');
    const confirmBtn = document.getElementById('confirmRemove');
    let shouldSubmit = false;
    
    if (checkoutForm && confirmationModal) {
        checkoutForm.addEventListener('submit', function(e) {
            if (!shouldSubmit) {
                e.preventDefault();
                if (checkoutForm.checkValidity()) {
                    confirmationModal.classList.add('active');
                }
            }
        });
        
        // Confirm order
        confirmBtn.addEventListener('click', function() {
            shouldSubmit = true;
            confirmBtn.disabled = true;
            confirmBtn.textContent = 'Processing...';
            
            setTimeout(() => {
                confirmationModal.classList.remove('active');
                checkoutForm.submit();
            }, 2500);
        });
        
        // Cancel order
        cancelBtn.addEventListener('click', function() {
            confirmationModal.classList.remove('active');
        });
        
        // Close modal when clicking outside
        confirmationModal.addEventListener('click', function(e) {
            if (e.target === confirmationModal) {
                confirmationModal.classList.remove('active');
            }
        });
    }
});