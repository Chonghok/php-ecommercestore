<?php
session_start();
require_once '../admin-includes/config.php';
require_once '../admin-includes/admin-functions.php';

if (!isset($_SESSION['admin_logged_in'])){
    header("Location: ../login.php");
    exit;
}

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$categoryID = $_GET['id'];
$category = getCategoryByID($conn, $categoryID);
$originalName = $category['name']; // Store original name for folder comparison

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $categoryName = trim($_POST['name']);
    $currentImage = $category['categoryImage']; // Keep current image if no new one uploaded
    
    // Validate inputs
    if (empty($categoryName)) {
        $error = 'Category name is required';
    } else {
        try {
            // Check if category name already exists (excluding current category)
            $stmt = $conn->prepare("SELECT categoryID FROM category WHERE name = :name AND categoryID != :id");
            $stmt->bindParam(':name', $categoryName);
            $stmt->bindParam(':id', $categoryID);
            $stmt->execute();
            
            if ($stmt->rowCount() > 0) {
                $error = 'Category name already exists';
            } else {
                // Handle file upload if new image was provided
                if (isset($_FILES['mainImage']) && $_FILES['mainImage']['error'] !== UPLOAD_ERR_NO_FILE) {
                    $imageFile = $_FILES['mainImage'];
                    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
                    $maxSize = 2 * 1024 * 1024; // 2MB
                    
                    // Validate image
                    if (!in_array($imageFile['type'], $allowedTypes)) {
                        $error = 'Only JPG, PNG, GIF, and WEBP images are allowed';
                    } elseif ($imageFile['size'] > $maxSize) {
                        $error = 'Image size must be less than 2MB';
                    } else {
                        // Generate new filename
                        $extension = pathinfo($imageFile['name'], PATHINFO_EXTENSION);
                        $newFilename = strtolower(str_replace(' ', '-', $categoryName)) . '.' . $extension;
                        $uploadPath = '../../images/Categories/' . $newFilename;
                        
                        // Delete old image if it exists
                        if (file_exists('../../images/Categories/' . $currentImage)) {
                            unlink('../../images/Categories/' . $currentImage);
                        }
                        
                        // Move uploaded file
                        if (move_uploaded_file($imageFile['tmp_name'], $uploadPath)) {
                            $currentImage = $newFilename;
                        } else {
                            $error = 'Failed to upload image';
                        }
                    }
                }
                
                // Only proceed if no errors
                if (empty($error)) {
                    // Handle folder renaming if category name changed
                    if ($originalName !== $categoryName) {
                        $oldFolderPath = '../../images/' . $originalName;
                        $newFolderPath = '../../images/' . $categoryName;
                        
                        // Check if old folder exists
                        if (file_exists($oldFolderPath)) {
                            // Check if new folder name already exists
                            if (file_exists($newFolderPath)) {
                                $error = 'A folder with the new category name already exists';
                            } else {
                                // Rename the folder
                                if (!rename($oldFolderPath, $newFolderPath)) {
                                    $error = 'Failed to rename category folder';
                                }
                            }
                        }

                        // Rename the image file if it follows the naming pattern
                        $oldImagePath = '../../images/Categories/' . $category['categoryImage'];
                        $extension = pathinfo($category['categoryImage'], PATHINFO_EXTENSION);
                        $newImageFilename = strtolower(str_replace(' ', '-', $categoryName)) . '.' . $extension;
                        $newImagePath = '../../images/Categories/' . $newImageFilename;
                        
                        if (file_exists($oldImagePath)) {
                            if (!rename($oldImagePath, $newImagePath)) {
                                $error = 'Failed to rename category image';
                            } else {
                                $currentImage = $newImageFilename; // Update the filename for DB
                            }
                        }
                    }
                    
                    if (empty($error)) {
                        // Update category in database
                        $stmt = $conn->prepare("UPDATE category SET name = :name, categoryImage = :image WHERE categoryID = :id");
                        $stmt->bindParam(':name', $categoryName);
                        $stmt->bindParam(':image', $currentImage);
                        $stmt->bindParam(':id', $categoryID);
                        
                        if ($stmt->execute()) {
                            $success = 'Category updated successfully!';
                            // Refresh category data
                            $category = getCategoryByID($conn, $categoryID);
                        } else {
                            $error = 'Failed to update category';
                            // If folder was renamed but DB update failed, try to revert
                            if ($originalName !== $categoryName && isset($newFolderPath) && isset($oldFolderPath)) {
                                rename($newFolderPath, $oldFolderPath);
                            }
                        }
                    }
                }
            }
        } catch (PDOException $e) {
            $error = 'Database error: ' . $e->getMessage();
            // If folder was renamed but an error occurred, try to revert
            if (isset($newFolderPath) && isset($oldFolderPath) && file_exists($newFolderPath)) {
                rename($newFolderPath, $oldFolderPath);
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>LetsGear Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.0.0/fonts/remixicon.css" rel="stylesheet" />
    <link rel="preconnect" href="https://fonts.googleapis.com"/>
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin/>
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@400..700&display=swap" rel="stylesheet"/>
    <link rel="stylesheet" href="../admin-assets/css/style.css">
    <link rel="stylesheet" href="../admin-assets/css/update-category.css">
</head>
<body>

    <?php require_once '../admin-includes/header.php' ?>

    <?php require_once '../admin-includes/sidebar.php' ?>

    <main>
        <div class="category-create-form-wrapper">
            <div class="category-create-header">
                <h2>Update Category</h2>
            </div>

            <?php if (!empty($error)): ?>
                <div class="error-message"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            
            <?php if (!empty($success)): ?>
                <div class="success-message"><?php echo htmlspecialchars($success); ?></div>
            <?php endif; ?>
        
            <form action="" id="categoryForm" method="POST" class="category-create-form" enctype="multipart/form-data">
                <input type="hidden" name="categoryid" value="<?= $categoryID ?>">
                <div class="form-group">
                    <label for="categoryId">Category ID</label>
                    <input type="text" id="categoryid" value="<?= $categoryID ?>" readonly />
                </div>
            
                <div class="form-group">
                    <label for="name">Category Name <span>*</span></label>
                    <input type="text" id="name" name="name" placeholder="Enter category name" 
                            value="<?= htmlspecialchars($category['name']) ?>" required />
                </div>

                <div class="form-group">
                    <label>Category Image  <span>*</span></label>
                    <div class="image-preview" id="main-image-preview" style="display: block;">
                        <img src="../../images/Categories/<?= $category['categoryImage'] ?>" alt="Category Image" id="main-image" />
                    </div>
                        <input type="file" id="main-image-upload" name="mainImage" accept="image/*" />
                    <div class="form-group-buttons">
                        <button type="button" id="upload-main-image" class="btn-upload">Change Image</button>
                    </div>
                </div>
                <button type="button" class="btn-back" onclick="window.location.href='index.php'">Back</button>
                <button type="button" id="submitBtn" class="btn-update-submit">Update Category</button>
            </form>
        </div>
    </main>

    <div class="confirm-modal" id="confirmModal">
        <div class="confirm-content">
            <div class="confirm-message" id="confirmMessage">Are you sure you want to update this category?</div>
            <div class="confirm-buttons">
                <button class="confirming-btn cancelbtn" id="btnCancel">Cancel</button>
                <button class="confirming-btn updatebtn" id="btnUpdate">Update</button>
            </div>
        </div>
    </div>

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
        const form = document.getElementById('categoryForm');
        const confirmModal = document.getElementById('confirmModal');
        const cancelBtn = document.getElementById('btnCancel');
        const updateBtn = document.getElementById('btnUpdate');
        const submitBtn = document.getElementById('submitBtn');
        const uploadBtn = document.getElementById('upload-main-image');
        const fileInput = document.getElementById('main-image-upload');
        const imagePreview = document.getElementById('main-image-preview');
        const previewImage = document.getElementById('main-image');
        
        // Image upload handling
        uploadBtn.addEventListener('click', function() {
            fileInput.click();
        });
        
        fileInput.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    previewImage.src = e.target.result;
                }
                
                reader.readAsDataURL(this.files[0]);
            }
        });
        
        // Form submission handling
        submitBtn.addEventListener('click', function() {
            // Validate form
            if (form.checkValidity()) {
                confirmModal.classList.add('active');
            } else {
                // If form is invalid, show validation messages
                form.reportValidity();
            }
        });
        
        cancelBtn.addEventListener('click', function() {
            confirmModal.classList.remove('active');
        });
        
        updateBtn.addEventListener('click', function() {
            // Change the button type to submit temporarily
            const tempSubmit = document.createElement('button');
            tempSubmit.type = 'submit';
            tempSubmit.style.display = 'none';
            form.appendChild(tempSubmit);
            tempSubmit.click();
            form.removeChild(tempSubmit);
        });
        
        // Close modal when clicking outside
        confirmModal.addEventListener('click', function(e) {
            if (e.target === this) {
                this.classList.remove('active');
            }
        });
        
        // Close with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && confirmModal.classList.contains('active')) {
                confirmModal.classList.remove('active');
            }
        });
    });
</script>
</html>
