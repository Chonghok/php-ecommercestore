document.addEventListener('DOMContentLoaded', function() {
    const loginForm = document.getElementById('loginForm');
    const emailInput = document.getElementById('emailInput');
    const emailError = document.getElementById('emailError');
    const formMessage = document.getElementById('formMessage');

    loginForm.addEventListener('submit', async function(e) {
        e.preventDefault();

        // Clear previous errors
        emailError.style.display = 'none';
        emailInput.classList.remove('input-error');
        formMessage.style.display = 'none';

        // Validate inputs before submission
        let isValid = true;
        
        if (!emailInput.value.trim()) {
            emailError.textContent = 'Email is required';
            emailError.style.display = 'block';
            emailInput.classList.add('input-error');
            isValid = false;
        }

        if (!isValid) {
            console.log('Validation failed');
            return;
        }

        try {
            // Get form data
            const formData = new FormData(loginForm);
            
            // Debugging - log form data
            for (let [key, value] of formData.entries()) {
                console.log(key, value);
            }
            
            // Send to server
            const response = await fetch('api/check-email-forgot-password.php', {
                method: 'POST',
                body: formData
            });
            
            if (!response.ok) {
                throw new Error(`Network response was not ok: ${response.status}`);
            }
            
            const data = await response.json();
            
            if (data.success) {
                window.location.href = 'reset-password.php';
            } else {
                if (data.errorField === 'identifier') {
                    emailError.textContent = data.message;
                    emailError.style.display = 'block';
                    emailInput.classList.add('input-error');
                } 
                formMessage.textContent = data.message;
                formMessage.className = 'form-message error';
            }
        } catch (error) {
            console.error('Error:', error);
            formMessage.textContent = 'An error occurred. Please try again.';
            formMessage.className = 'form-message error';
        }
    });
});