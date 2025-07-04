document.addEventListener('DOMContentLoaded', function() {
  const signupForm = document.getElementById('signupForm');
  const passwordInput = document.getElementById('password');
  const confirmPasswordInput = document.getElementById('confirm-password');
  const passwordError = document.getElementById('passwordError');
  const confirmationModal = document.getElementById('confirmationModal');
  const confirmSubmit = document.getElementById('confirmSubmit');
  const cancelSubmit = document.getElementById('cancelSubmit');
  const formMessage = document.getElementById('formMessage');

  // Remove real-time password matching validation
  // We'll check only on final submission

  // Form submission handler
  signupForm.addEventListener('submit', function(e) {
      e.preventDefault();
      
      // Basic validation - check if all fields are filled
      const inputs = signupForm.querySelectorAll('input[required]');
      let allFilled = true;
      
      inputs.forEach(input => {
          if (!input.value.trim()) {
              allFilled = false;
              input.style.borderColor = '#ff3333';
          } else {
              input.style.borderColor = '';
          }
      });

      if (!allFilled) {
          formMessage.textContent = 'Please fill in all required fields';
          formMessage.className = 'form-message error';
          formMessage.style.display = 'block';
          return;
      }

      // Show confirmation modal
      confirmationModal.style.display = 'block';
  });

  // Modal button handlers
  confirmSubmit.addEventListener('click', function() {
      // First check password match
      if (passwordInput.value !== confirmPasswordInput.value) {
          passwordError.textContent = 'Passwords do not match';
          passwordError.style.display = 'block';
          confirmPasswordInput.style.borderColor = '#ff3333';
          confirmationModal.style.display = 'none'; // Hide modal
          return;
      }

      // Hide modal
      confirmationModal.style.display = 'none';
      
      // Show loading state
      formMessage.textContent = 'Processing...';
      formMessage.className = 'form-message';
      formMessage.style.display = 'block';
      
      // Submit the form via AJAX
      const formData = new FormData(signupForm);
      
      fetch('api/sign-up-process.php', {
          method: 'POST',
          body: formData
      })
      .then(response => {
          if (!response.ok) {
              throw new Error('Network response was not ok');
          }
          return response.json();
      })
      .then(data => {
          if (data.success) {
              formMessage.textContent = data.message;
              formMessage.className = 'form-message success';
              
              // Reset form
              signupForm.reset();
              
              // Redirect if specified
              if (data.redirect) {
                  setTimeout(() => {
                      window.location.href = data.redirect;
                  }, 1500);
              }
          } else {
              formMessage.textContent = data.message;
              formMessage.className = 'form-message error';
          }
      })
      .catch(error => {
          console.error('Error:', error);
          formMessage.textContent = 'An error occurred. Please try again.';
          formMessage.className = 'form-message error';
      });
  });

  cancelSubmit.addEventListener('click', function() {
      confirmationModal.style.display = 'none';
  });
});

function goToLogin() {
    window.location.href = 'login.php';
}