<?php
session_start();
require_once '../admin-includes/config.php';
require_once '../admin-includes/admin-functions.php';

if (!isset($_SESSION['admin_logged_in'])){
    header("Location: ../login.php");
    exit;
}

$userInfo = getUsers($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LetsGear Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.0.0/fonts/remixicon.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@400..700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../admin-assets/css/style.css">
    <link rel="stylesheet" href="../admin-assets/css/user.css">
</head>
<body>
    
    <?php require_once '../admin-includes/header.php' ?>

    <?php require_once '../admin-includes/sidebar.php' ?>

    <main>
        <div class="admin-table-header">
            <h2>Users</h2>
        </div>
        <div class="admin-table-wrapper">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>User ID</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th style="text-align: center;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($userInfo as $user): ?>
                    <tr data-userid="<?= $user['userID'] ?>">
                        <td><?= $user['userID'] ?></td>
                        <td class="truncate"><?= $user['username'] ?></td>
                        <td class="truncate"><?= $user['email'] ?></td>
                        <td>
                            <div class="action-buttons">
                                <button class="btn-update" onclick="window.location.href='update-user.php?id=<?= $user['userID'] ?>'">Update</button>
                                <button class="btn-delete" data-userid="<?= $user['userID'] ?>">Delete</button>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </main>

    <div class="delete-modal" id="deleteModal">
        <div class="delete-content">
            <div class="delete-message" id="deleteMessage">Are you sure you want to delete this user account?</div>
            <div class="delete-buttons">
                <button class="delete-btn cancelbtn" id="btnCancel">Cancel</button>
                <button class="delete-btn deletebtn" id="btnDelete">Delete</button>
            </div>
        </div>
    </div>

    <!-- Toast Notification -->
    <div id="toast" class="toast"></div>

    <div class="confirmation-modal" id="confirmationModal">
        <div class="confirmation-content">
            <div class="confirmation-message" id="confirmationMessage">Are you sure you want to log out?</div>
            <div class="confirmation-buttons">
                <button class="confirmation-btn cancel-btn" id="cancelBtn">Cancel</button>
                <button class="confirmation-btn confirm-btn" id="confirmBtn">Log Out</button>
            </div>
        </div>
    </div>
</body>
<script src="../admin-assets/js/script.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const deleteModal = document.getElementById('deleteModal');
        const deleteButtons = document.querySelectorAll('.btn-delete');
        const cancelBtn = document.getElementById('btnCancel');
        const deleteBtn = document.getElementById('btnDelete');
        const toast = document.getElementById('toast');
        
        let currentUserId = null;

        // Show toast notification
        function showToast(message, type) {
            toast.textContent = message;
            toast.className = `toast show ${type}`;
            
            setTimeout(() => {
                toast.classList.remove('show');
            }, 2500);
        }

        // Check for toast message from PHP
        <?php if (isset($_SESSION['toast'])): ?>
            showToast('<?= $_SESSION['toast']['message'] ?>', '<?= $_SESSION['toast']['type'] ?>');
            <?php unset($_SESSION['toast']); ?>
        <?php endif; ?>

        // Add click event to all delete buttons
        deleteButtons.forEach(button => {
            button.addEventListener('click', function() {
                currentUserId = this.getAttribute('data-userid');
                deleteModal.classList.add('active');
            });
        });

        // Cancel button
        cancelBtn.addEventListener('click', function() {
            deleteModal.classList.remove('active');
            currentUserId = null;
        });

        // Delete button
deleteBtn.addEventListener('click', function() {
    if (!currentUserId) {
        console.error('No user ID selected');
        return;
    }
    
    console.log('Attempting to delete user ID:', currentUserId);
    
    fetch('delete-user.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `user_id=${currentUserId}`
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        console.log('Response data:', data);
        
        // Close the modal immediately
        deleteModal.classList.remove('active');
        
        if (data.status === 'success') {
            // Show success message
            showToast(data.message, 'success');
            
            // Remove the deleted user row from the table
            const rowToRemove = document.querySelector(`tr[data-userid="${currentUserId}"]`);
            if (rowToRemove) {
                rowToRemove.remove();
            }
        } else {
            showToast(data.message || 'Failed to delete user', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('An error occurred: ' + error.message, 'error');
        deleteModal.classList.remove('active');
    });
});

        // Close modal when clicking outside
        deleteModal.addEventListener('click', function(e) {
            if (e.target === this) {
                this.classList.remove('active');
                currentUserId = null;
            }
        });

        // Close with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && deleteModal.classList.contains('active')) {
                deleteModal.classList.remove('active');
                currentUserId = null;
            }
        });
    });
</script>
</html>
