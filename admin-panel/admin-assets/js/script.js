// User dropdown toggle
const userInfo = document.getElementById('userInfo');
const userDropdown = document.getElementById('userDropdown');

userInfo.addEventListener('click', function() {
    userDropdown.classList.toggle('active');
});



// Logout confirmation functionality
const logoutLink = document.querySelector('.dropdown-menu a');
const confirmationModal = document.getElementById('confirmationModal');
const cancelBtn = document.getElementById('cancelBtn');
const confirmBtn = document.getElementById('confirmBtn');

logoutLink.addEventListener('click', function(e) {
    e.preventDefault(); // Prevent default link behavior
    userDropdown.classList.remove('active'); // Close the dropdown
    confirmationModal.classList.add('active'); // Show the modal
});

cancelBtn.addEventListener('click', function() {
    confirmationModal.classList.remove('active'); // Hide the modal
});

confirmBtn.addEventListener('click', function() {
    window.location.href = '../logout.php'; // Redirect to logout
});

// Close modal when clicking outside
confirmationModal.addEventListener('click', function(e) {
    if (e.target === confirmationModal) {
        confirmationModal.classList.remove('active');
    }
});