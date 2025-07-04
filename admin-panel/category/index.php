<?php
session_start();
require_once '../admin-includes/config.php';
require_once '../admin-includes/admin-functions.php';

if (!isset($_SESSION['admin_logged_in'])){
    header("Location: ../login.php");
    exit;
}

$categories = getCategories($conn);
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
    <link rel="stylesheet" href="../admin-assets/css/category.css">
</head>
<body>
    
    <?php require_once '../admin-includes/header.php' ?>

    <?php require_once '../admin-includes/sidebar.php' ?>

    <main>
        <div class="category-table-header">
            <h2>Categories</h2>
            <a href="create-category.php" class="btn-create"><i class="ri-add-line"></i> Create new Category</a>
        </div>
        <div class="category-table-wrapper">
            <table class="category-table">
                <thead>
                    <tr>
                        <th>CategoryID</th>
                        <th>Image</th>
                        <th style="text-align: center;">Category Name</th>
                        <th style="text-align: center;">Product Count</th>
                        <th style="text-align: center;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($categories as $category): ?>
                    <tr data-categoryid="<?= $category['categoryID'] ?>">
                        <td><?= $category['categoryID'] ?></td>
                        <td><img src="../../images/Categories/<?= $category['categoryImage'] ?>" alt="<?= $category['name'] ?>"></td>
                        <td style="text-align: center;"><?= $category['name'] ?></td>
                        <td style="text-align: center;"><?= $category['productCount'] ?></td>
                        <td>
                            <div class="action-buttons">
                                <button type="button" class="btn-update" onclick="window.location.href='update-category.php?id=<?= $category['categoryID'] ?>'">Update</button>
                                <button class="btn-delete" data-categoryid="<?= $category['categoryID'] ?>">Delete</button>
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
            <div class="delete-message" id="deleteMessage">Are you sure you want to delete this category?</div>
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
    // Delete category functionality
    const deleteButtons = document.querySelectorAll('.btn-delete');
    const deleteModal = document.getElementById('deleteModal');
    const btnCancel = document.getElementById('btnCancel');
    const btnDelete = document.getElementById('btnDelete');
    const deleteMessage = document.getElementById('deleteMessage');
    const toast = document.getElementById('toast');
    
    let currentCategoryId = null;

    deleteButtons.forEach(button => {
        button.addEventListener('click', function() {
            currentCategoryId = this.getAttribute('data-categoryid');
            const categoryName = this.closest('tr').querySelector('td:nth-child(3)').textContent;
            deleteMessage.textContent = `Are you sure you want to delete the category "${categoryName}"?`;
            deleteModal.style.display = 'flex';
        });
    });

    btnCancel.addEventListener('click', function() {
        deleteModal.style.display = 'none';
        currentCategoryId = null;
    });

    btnDelete.addEventListener('click', function() {
        if (currentCategoryId) {
            deleteCategory(currentCategoryId);
        }
    });

    function deleteCategory(categoryId) {
        fetch('delete-category.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `categoryID=${categoryId}`
        })
        .then(response => response.json())
        .then(data => {
            deleteModal.style.display = 'none';
            
            if (data.success) {
                // Remove the row from the table
                document.querySelector(`tr[data-categoryid="${categoryId}"]`).remove();
                showToast(data.message || 'Category deleted successfully', 'success');
            } else {
                showToast(data.message || 'Failed to delete category', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('An error occurred while processing your request', 'error');
        });
    }

    function showToast(message, type) {
        toast.textContent = message;
        toast.className = 'toast show ' + type;
        
        setTimeout(() => {
            toast.className = toast.className.replace('show', '');
        }, 3000);
    }

    // Close modal when clicking outside
    deleteModal.addEventListener('click', function(e) {
        if (e.target === deleteModal) {
            deleteModal.style.display = 'none';
        }
    });
});
</script>
</html>
