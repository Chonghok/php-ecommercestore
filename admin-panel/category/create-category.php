<?php
session_start();
require_once '../admin-includes/config.php';
require_once '../admin-includes/admin-functions.php';

if (!isset($_SESSION['admin_logged_in'])){
    header("Location: ../login.php");
    exit;
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $categoryName = trim($_POST['name']);
    
    // Validate inputs
    if (empty($categoryName)) {
        $error = 'Category name is required';
    } elseif (!isset($_FILES['mainImage']) || $_FILES['mainImage']['error'] === UPLOAD_ERR_NO_FILE) {
        $error = 'Category image is required';
    } else {
        try {
            // Check if category already exists
            $stmt = $conn->prepare("SELECT categoryID FROM category WHERE name = :name");
            $stmt->bindParam(':name', $categoryName);
            $stmt->execute();
            
            if ($stmt->rowCount() > 0) {
                $error = 'Category already exists';
            } else {
                // Handle file upload
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
                    
                    // Move uploaded file
                    if (move_uploaded_file($imageFile['tmp_name'], $uploadPath)) {
                        // Create category folder with exact name
                        $folderResult = createCategoryFolder($categoryName);
                        if (!$folderResult['success']) {
                            $error = $folderResult['message'];
                        } else {
                            // Insert into database
                            $stmt = $conn->prepare("INSERT INTO category (name, categoryImage) VALUES (:name, :image)");
                            $stmt->bindParam(':name', $categoryName);
                            $stmt->bindParam(':image', $newFilename);
                            
                            if ($stmt->execute()) {
                                $success = 'Category created successfully!';
                                $_POST = array(); // Clear form
                            } else {
                                $error = 'Failed to create category';
                                // Remove the uploaded image if database insertion failed
                                if (file_exists($uploadPath)) {
                                    unlink($uploadPath);
                                }
                                // Remove the created folder if database insertion failed
                                $folderPath = '../../images/' . $categoryName;
                                if (file_exists($folderPath)) {
                                    rmdir($folderPath);
                                }
                            }
                        }
                    } else {
                        $error = 'Failed to upload image';
                    }
                }
            }
        } catch (PDOException $e) {
            $error = 'Database error: ' . $e->getMessage();
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
    <link rel="stylesheet" href="../admin-assets/css/create-category.css">
</head>
<body>

    <?php require_once '../admin-includes/header.php' ?>

    <?php require_once '../admin-includes/sidebar.php' ?>

    <main>
        <div class="category-create-form-wrapper">
            <div class="category-create-header">
                <h2>Create New Category</h2>
            </div>

            <?php if (!empty($error)): ?>
                <div class="error-message"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            
            <?php if (!empty($success)): ?>
                <div class="success-message"><?php echo htmlspecialchars($success); ?></div>
            <?php endif; ?>
        
            <form action="" id="categoryForm" method="POST" class="category-create-form" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="name">Category Name <span>*</span></label>
                    <input type="text" id="name" name="name" placeholder="Enter category name" 
                            value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name']) : ''; ?>" required />
                </div>

                <div class="form-group">
                    <label>Category Image  <span>*</span></label>
                    <div class="image-preview" id="main-image-preview" style="display: none;">
                    <!-- Default text or uploaded image will appear here -->
                        <img src="" alt="Category Image" id="main-image" />
                    </div>
                    <div id="no-image-message" style="display: block; color: #777;">Please upload an image</div>

                        <input type="file" id="main-image-upload" name="mainImage" accept="image/*" required />
                    <div class="form-group-buttons">
                        <button type="button" id="upload-main-image" class="btn-upload">Upload</button>
                    </div>
                </div>
                <button type="button" class="btn-back" onclick="window.location.href='index.php'">Back</button>
                <button type="button" id="submitBtn" class="btn-create-submit">Create Category</button>
            </form>
        </div>
    </main>

    <div class="confirm-modal" id="confirmModal">
        <div class="confirm-content">
            <div class="confirm-message" id="confirmMessage">Are you sure you want to create category?</div>
            <div class="confirm-buttons">
                <button class="confirming-btn cancelbtn" id="btnCancel">Cancel</button>
                <button class="confirming-btn confirmbtn" id="btnConfirm">Create</button>
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
        const confirmBtn = document.getElementById('btnConfirm');
        const submitBtn = document.getElementById('submitBtn');
        const uploadBtn = document.getElementById('upload-main-image');
        const fileInput = document.getElementById('main-image-upload');
        const imagePreview = document.getElementById('main-image-preview');
        const noImageMessage = document.getElementById('no-image-message');
        const previewImage = document.getElementById('main-image');
        const toast = document.getElementById('toast');
        
        // Show toast notification
        function showToast(message, type) {
            toast.textContent = message;
            toast.className = `toast show ${type}`;
            
            setTimeout(() => {
                toast.classList.remove('show');
            }, 2500);
        }
        
        // Image upload handling
        uploadBtn.addEventListener('click', function() {
            fileInput.click();
        });
        
        fileInput.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    previewImage.src = e.target.result;
                    imagePreview.style.display = 'block';
                    noImageMessage.style.display = 'none';
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
        
        confirmBtn.addEventListener('click', function() {
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
