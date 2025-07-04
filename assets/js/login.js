document.addEventListener('DOMContentLoaded', function() {
    const loginForm = document.getElementById('loginForm');
    const identifierInput = document.getElementById('loginIdentifier');
    const passwordInput = document.getElementById('loginPassword');
    const identifierError = document.getElementById('identifierError');
    const passwordError = document.getElementById('passwordError');
    const formMessage = document.getElementById('formMessage');

    // Debugging - check if elements exist
    console.log('Form elements:', {
        loginForm,
        identifierInput,
        passwordInput,
        identifierError,
        passwordError,
        formMessage
    });

    loginForm.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        console.log('Form submitted'); // Debugging
        
        // Clear previous errors
        identifierError.style.display = 'none';
        passwordError.style.display = 'none';
        identifierInput.classList.remove('input-error');
        passwordInput.classList.remove('input-error');
        formMessage.style.display = 'none';

        // Validate inputs before submission
        let isValid = true;
        
        if (!identifierInput.value.trim()) {
            identifierError.textContent = 'Email or username is required';
            identifierError.style.display = 'block';
            identifierInput.classList.add('input-error');
            isValid = false;
        }

        if (!passwordInput.value) {
            passwordError.textContent = 'Password is required';
            passwordError.style.display = 'block';
            passwordInput.classList.add('input-error');
            isValid = false;
        }

        if (!isValid) {
            console.log('Validation failed');
            return;
        }

        // Show loading state
        formMessage.textContent = 'Logging in...';
        formMessage.className = 'form-message';
        formMessage.style.display = 'block';

        try {
            // Get form data
            const formData = new FormData(loginForm);
            
            // Debugging - log form data
            for (let [key, value] of formData.entries()) {
                console.log(key, value);
            }
            
            // Send to server
            const response = await fetch('api/login-process.php', {
                method: 'POST',
                body: formData
            });
            
            console.log('Response status:', response.status); // Debugging
            
            if (!response.ok) {
                throw new Error(`Network response was not ok: ${response.status}`);
            }
            
            const data = await response.json();
            console.log('Response data:', data); // Debugging
            
            if (data.success) {
                formMessage.textContent = data.message || 'Login successful! Redirecting...';
                formMessage.className = 'form-message success';
                setTimeout(() => {
                    window.location.href = data.redirect || 'index.php';
                }, 1500);
            } else {
                if (data.errorField === 'identifier') {
                    identifierError.textContent = data.message;
                    identifierError.style.display = 'block';
                    identifierInput.classList.add('input-error');
                } else if (data.errorField === 'password') {
                    passwordError.textContent = data.message;
                    passwordError.style.display = 'block';
                    passwordInput.classList.add('input-error');
                }
                formMessage.textContent = data.message || 'Login failed';
                formMessage.className = 'form-message error';
            }
        } catch (error) {
            console.error('Error:', error);
            formMessage.textContent = 'An error occurred. Please try again.';
            formMessage.className = 'form-message error';
        }
    });
});

function handleSignUp() {
    window.location.href = 'register.php';
}