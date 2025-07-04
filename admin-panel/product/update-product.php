<?php
session_start();
require_once '../admin-includes/config.php';
require_once '../admin-includes/admin-functions.php';

if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: ../login.php");
    exit;
}

// Get product ID from URL
if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$productId = $_GET['id'];
// Get product data
$product = getProductByID($conn, $productId);
$subImages = getSubImages($conn, $productId);


// Get categories for dropdown
$categorySelect = getCategories($conn);

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $categoryID = $_POST['categoryid'];
    $name = trim($_POST['name']);
    $shortDesc = trim($_POST['shortdesc']);
    $price = (float)$_POST['price'];
    $discount = (float)$_POST['discount'];
    $productDetail = trim($_POST['productdetail']);
    $stock = 0; // Default stock to 0 as requested
    
    // Get images to delete
    $imagesToDelete = isset($_POST['delete_images']) ? $_POST['delete_images'] : [];
    
    // Validate required fields
    if (empty($name) || empty($shortDesc) || empty($productDetail) || $price <= 0) {
        $error = 'Please fill in all required fields with valid values';
    } else {
        try {
            // Check for duplicate product name (excluding current product)
            $stmt = $conn->prepare("SELECT productID FROM product WHERE name = ? AND productID != ?");
            $stmt->execute([$name, $productId]);
            
            if ($stmt->rowCount() > 0) {
                $error = 'Product with this name already exists';
            } else {
                // Get current category name for old image path
                $stmt = $conn->prepare("SELECT name FROM category WHERE categoryID = ?");
                $stmt->execute([$product['categoryID']]);
                $oldCategory = $stmt->fetch(PDO::FETCH_ASSOC);
                $oldCategoryName = $oldCategory['name'];
                
                // Get new category name for new image path
                $stmt = $conn->prepare("SELECT name FROM category WHERE categoryID = ?");
                $stmt->execute([$categoryID]);
                $newCategory = $stmt->fetch(PDO::FETCH_ASSOC);
                $newCategoryName = $newCategory['name'];
                
                $mainImageName = $product['mainImage'];
                $mainImageChanged = false;
                
                // Process main image if uploaded
                if (isset($_FILES['mainImage']) && $_FILES['mainImage']['error'] !== UPLOAD_ERR_NO_FILE) {
                    $mainImage = $_FILES['mainImage'];
                    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
                    
                    if (!in_array($mainImage['type'], $allowedTypes)) {
                        $error = 'Only JPG, PNG, GIF, and WEBP images are allowed';
                    } else {
                        // Delete old main image
                        $oldImagePath = "../../images/{$oldCategoryName}/{$mainImageName}";
                        if (file_exists($oldImagePath)) {
                            unlink($oldImagePath);
                        }
                        
                        // Create new filename
                        $extension = pathinfo($mainImage['name'], PATHINFO_EXTENSION);
                        $mainImageName = strtolower(str_replace(' ', '-', $name)) . '.' . $extension;
                        $uploadPath = "../../images/{$newCategoryName}/{$mainImageName}";
                        
                        // Create directory if it doesn't exist
                        if (!file_exists("../../images/{$newCategoryName}")) {
                            mkdir("../../images/{$newCategoryName}", 0755, true);
                        }
                        
                        // Move uploaded file
                        if (!move_uploaded_file($mainImage['tmp_name'], $uploadPath)) {
                            throw new Exception('Failed to upload main image');
                        }
                        
                        $mainImageChanged = true;
                    }
                }
                
                // If category changed but main image not changed, move the file
                if ($oldCategoryName !== $newCategoryName && !$mainImageChanged) {
                    $oldPath = "../../images/{$oldCategoryName}/{$mainImageName}";
                    $newPath = "../../images/{$newCategoryName}/{$mainImageName}";
                    
                    // Create new directory if needed
                    if (!file_exists("../../images/{$newCategoryName}")) {
                        mkdir("../../images/{$newCategoryName}", 0755, true);
                    }
                    
                    if (file_exists($oldPath)) {
                        rename($oldPath, $newPath);
                    }
                }
                
                // Process sub images to delete
                foreach ($imagesToDelete as $imageId) {
                    $stmt = $conn->prepare("SELECT subImage FROM product_image WHERE imageID = ?");
                    $stmt->execute([$imageId]);
                    $image = $stmt->fetch(PDO::FETCH_ASSOC);
                    
                    if ($image) {
                        $imagePath = "../../images/{$oldCategoryName}/{$image['subImage']}";
                        if (file_exists($imagePath)) {
                            unlink($imagePath);
                        }
                        
                        $stmt = $conn->prepare("DELETE FROM product_image WHERE imageID = ?");
                        $stmt->execute([$imageId]);
                    }
                }
                
                // Process new sub images
                if (!empty($_FILES['subImages']['name'][0])) {
                    $subImageCount = 1;
                    
                    // Find highest existing subimage number
                    $stmt = $conn->prepare("SELECT subImage FROM product_image WHERE productID = ?");
                    $stmt->execute([$productId]);
                    $existingSubImages = $stmt->fetchAll(PDO::FETCH_COLUMN);
                    
                    foreach ($existingSubImages as $img) {
                        if (preg_match('/-(\d+)\./', $img, $matches)) {
                            $num = intval($matches[1]);
                            if ($num > $subImageCount) {
                                $subImageCount = $num;
                            }
                        }
                    }
                    
                    foreach ($_FILES['subImages']['tmp_name'] as $key => $tmpName) {
                        if ($_FILES['subImages']['error'][$key] === UPLOAD_ERR_OK) {
                            $subExtension = pathinfo($_FILES['subImages']['name'][$key], PATHINFO_EXTENSION);
                            $subImageName = strtolower(str_replace(' ', '-', $name)) . '-' . (++$subImageCount) . '.' . $subExtension;
                            $subUploadPath = "../../images/{$newCategoryName}/{$subImageName}";
                            
                            if (move_uploaded_file($tmpName, $subUploadPath)) {
                                $stmt = $conn->prepare("INSERT INTO product_image (productID, subImage) VALUES (?, ?)");
                                $stmt->execute([$productId, $subImageName]);
                            }
                        }
                    }
                }
                
                // Move any remaining sub images if category changed
                if ($oldCategoryName !== $newCategoryName) {
                    $stmt = $conn->prepare("SELECT imageID, subImage FROM product_image WHERE productID = ?");
                    $stmt->execute([$productId]);
                    $remainingImages = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    
                    foreach ($remainingImages as $image) {
                        $oldPath = "../../images/{$oldCategoryName}/{$image['subImage']}";
                        $newPath = "../../images/{$newCategoryName}/{$image['subImage']}";
                        
                        if (file_exists($oldPath)) {
                            rename($oldPath, $newPath);
                        }
                    }
                }
                
                // Update product in database
                $stmt = $conn->prepare("UPDATE product SET 
                    categoryID = ?, 
                    name = ?, 
                    shortDesc = ?, 
                    price = ?, 
                    discount = ?, 
                    mainImage = ?, 
                    productDetail = ? 
                    WHERE productID = ?");
                
                $stmt->execute([
                    $categoryID,
                    $name,
                    $shortDesc,
                    $price,
                    $discount,
                    $mainImageName,
                    $productDetail,
                    $productId
                ]);
                
                $product = getProductByID($conn, $productId);
                $subImages = getSubImages($conn, $productId);
                $success = 'Product updated successfully!';
            }
        } catch (PDOException $e) {
            $error = 'Database error: ' . $e->getMessage();
        } catch (Exception $e) {
            $error = 'Error: ' . $e->getMessage();
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
    <link rel="stylesheet" href="../admin-assets/css/create-product.css">
    <style>
        /* Add checkbox styles for subimage deletion */
        .subimage-checkbox {
            margin-right: 10px;
        }
        
        .subimage-item {
            display: flex;
            align-items: center;
            padding: 10px;
            border: 1px solid #eee;
            border-radius: 6px;
            margin-bottom: 10px;
            background: #fafafa;
        }
        
        .subimage-item img {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 4px;
            margin-right: 15px;
        }
        
        .subimage-item-info {
            flex: 1;
        }
        
        .existing-subimages {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

    <?php require_once '../admin-includes/header.php' ?>

    <?php require_once '../admin-includes/sidebar.php' ?>

    <main>
        <div class="product-create-form-wrapper">
            <div class="product-create-header">
                <h2>Update Product</h2>
                <a href="index.php" class="btn-back">← Back to Product List</a>
            </div>

            <?php if (!empty($error)): ?>
                <div class="error-message"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            
            <?php if (!empty($success)): ?>
                <div class="success-message"><?php echo htmlspecialchars($success); ?></div>
            <?php endif; ?>
        
            <form action="" id="productForm" method="POST" enctype="multipart/form-data" class="product-create-form">
                <input type="hidden" name="productId" value="<?= $productId ?>">

                <div class="form-group">
                    <label for="productId">Product ID</label>
                    <input type="text" id="productid" value="<?= $productId ?>" readonly />
                </div>
                
                <div class="form-group">
                    <label for="categoryid">Category <span>*</span></label>
                    <select id="categoryid" name="categoryid">
                        <?php foreach ($categorySelect as $category): ?>
                        <option value="<?= $category['categoryID'] ?>" <?= $category['categoryID'] == $product['categoryID'] ? 'selected' : '' ?>>
                            <?= $category['name'] ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="name">Product Name <span>*</span></label>
                    <input type="text" id="name" name="name" placeholder="Enter product name" 
                           value="<?= htmlspecialchars($product['name']) ?>" required />
                </div>

                <div class="form-group">
                    <label for="shortdesc">Short Description <span>*</span></label>
                    <textarea id="shortdesc" name="shortdesc" rows="3" 
                              placeholder="Enter short description of product"><?= htmlspecialchars($product['shortDesc']) ?></textarea>
                </div>

                <div class="form-group">
                    <label for="price">Price ($) <span>*</span></label>
                    <input type="text" id="price" name="price" placeholder="Enter product price" 
                           value="<?= htmlspecialchars($product['price']) ?>" required />
                </div>

                <div class="form-group">
                    <label for="discount">Discount (%) <span>*</span></label>
                    <input type="text" id="discount" name="discount" placeholder="Enter product discount" 
                           value="<?= htmlspecialchars($product['discount']) ?>" step="0.1" required />
                </div>

                <div class="form-group">
                    <label>Main Image <span>*</span></label>
                    <div class="image-preview" id="main-image-preview" style="display: block;">
                        <img src="../../images/<?= $product['categoryName'] ?>/<?= $product['mainImage'] ?>" 
                             alt="Main Image" id="main-image" />
                    </div>
                    <div id="no-image-message" style="display: none; color: #777;">Please upload an image</div>
                    <input type="file" id="main-image-upload" name="mainImage" style="display: none;" accept="image/*" onchange="uploadMainImage(event)" />
                    
                    <div class="form-group-buttons">
                        <button type="button" id="upload-main-image" class="btn-upload" onclick="triggerMainImageInput()">Replace Image</button>
                    </div>
                </div>    

                <div class="form-group">
                    <label for="productdetail">Product Detail <span>*</span><span class="label-tip"> (Please use ' | ' to separate each detail)</span></label>
                    <textarea id="productdetail" name="productdetail" rows="6" 
                              placeholder="Enter full product detail here"><?= htmlspecialchars($product['productDetail']) ?></textarea>
                </div>

                <div class="form-group">
                    <label>Existing Sub Images</label>
                    <div class="existing-subimages" id="existingSubimages">
                        <?php if (count($subImages) > 0): ?>
                            <?php foreach ($subImages as $subImage): ?>
                                <div class="subimage-item">
                                    <input type="checkbox" class="subimage-checkbox" name="delete_images[]" value="<?= $subImage['imageID'] ?>">
                                    <img src="../../images/<?= $product['categoryName'] ?>/<?= $subImage['subImage'] ?>" 
                                         alt="Sub Image">
                                    <div class="subimage-item-info">
                                        <div><?= $subImage['subImage'] ?></div>
                                        <small>Click checkbox to delete</small>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p>No sub-images for this product</p>
                        <?php endif; ?>
                    </div>
                    
                    <label>Add/Replace Sub Images <span class="label-tip">(Optional)</span></label>
                    <div class="subimage-upload-area">
                        <!-- Drop zone for drag & drop -->
                        <div class="subimage-dropzone" id="subimageDropzone">
                            <div class="dropzone-content">
                                <i class="ri-image-add-line"></i>
                                <p>Drag & drop images here or click to browse</p>
                                <button type="button" class="btn-browse">Browse Files</button>
                            </div>
                            <input type="file" id="subimageUpload" name="subImages[]" accept="image/*" multiple style="display: none;">
                        </div>
                        
                        <!-- Preview area -->
                        <div class="subimage-previews" id="subimagePreviews">
                            <div class="empty-message" id="emptyMessage">
                                No new sub-images selected
                            </div>
                        </div>
                    </div>
                </div>

                <button type="button" class="btnBack" onclick="window.location.href='index.php'">Back</button>
                <button type="submit" id="submitBtn" class="btn-create-submit updatebtn">Update Product</button>
            </form>
        </div>
    </main>

    <div class="confirm-modal" id="confirmModal">
        <div class="confirm-content">
            <div class="confirm-message" id="confirmMessage">Are you sure you want to update this product?</div>
            <div class="confirm-buttons">
                <button class="confirming-btn cancelbtn" id="btnCancel">Cancel</button>
                <button class="confirming-btn confirmbtn updatebtn" id="btnConfirm">Update</button>
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

    <script src="../admin-assets/js/script.js"></script>
    <script>
        // Main image handling
        function triggerMainImageInput() {
            document.getElementById('main-image-upload').click();
        }

        function uploadMainImage(event) {
            const file = event.target.files[0];
            
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const imagePreview = document.getElementById('main-image-preview');
                    const mainImage = document.getElementById('main-image');
                    const noImageMessage = document.getElementById('no-image-message');
                    
                    imagePreview.style.display = 'block';
                    mainImage.src = e.target.result;
                    noImageMessage.style.display = 'none';
                }
                reader.readAsDataURL(file);
            }
        }

        // Sub-image handling
        document.addEventListener('DOMContentLoaded', function() {
            const subimageDropzone = document.getElementById('subimageDropzone');
            const subimageUpload = document.getElementById('subimageUpload');
            const subimagePreviews = document.getElementById('subimagePreviews');
            const emptyMessage = document.getElementById('emptyMessage');
            
            // Array to store all selected files
            let subImages = [];
            
            // Click handler for dropzone
            subimageDropzone.addEventListener('click', function() {
                subimageUpload.click();
            });
            
            // Browse button handler
            const browseBtn = document.querySelector('.btn-browse');
            browseBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                subimageUpload.click();
            });
            
            // File input change handler
            subimageUpload.addEventListener('change', function(e) {
                if (this.files && this.files.length > 0) {
                    handleFiles(this.files);
                }
            });
            
            // Drag and drop handlers
            ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                subimageDropzone.addEventListener(eventName, preventDefaults, false);
            });
            
            function preventDefaults(e) {
                e.preventDefault();
                e.stopPropagation();
            }
            
            ['dragenter', 'dragover'].forEach(eventName => {
                subimageDropzone.addEventListener(eventName, highlight, false);
            });
            
            ['dragleave', 'drop'].forEach(eventName => {
                subimageDropzone.addEventListener(eventName, unhighlight, false);
            });
            
            function highlight() {
                subimageDropzone.classList.add('highlight');
            }
            
            function unhighlight() {
                subimageDropzone.classList.remove('highlight');
            }
            
            // Handle dropped files
            subimageDropzone.addEventListener('drop', function(e) {
                const dt = e.dataTransfer;
                const files = dt.files;
                handleFiles(files);
            });
            
            // Process selected files
            function handleFiles(files) {
                subImages = [...subImages, ...Array.from(files)];
                updatePreviews();
                emptyMessage.style.display = 'none';
            }
            
            // Update previews display
            function updatePreviews() {
                subimagePreviews.innerHTML = '';
                
                if (subImages.length === 0) {
                    emptyMessage.style.display = 'block';
                    return;
                }
                
                subImages.forEach((file, index) => {
                    const previewItem = document.createElement('div');
                    previewItem.className = 'preview-item';
                    
                    const img = document.createElement('img');
                    if (file.type.startsWith('image/')) {
                        img.src = URL.createObjectURL(file);
                    } else {
                        img.src = '../admin-assets/images/file-icon.png';
                    }
                    
                    const fileInfo = document.createElement('div');
                    fileInfo.className = 'file-info';
                    
                    const fileName = document.createElement('div');
                    fileName.className = 'file-name';
                    fileName.textContent = file.name;
                    
                    const fileSize = document.createElement('div');
                    fileSize.className = 'file-size';
                    fileSize.textContent = formatFileSize(file.size);
                    
                    fileInfo.appendChild(fileName);
                    fileInfo.appendChild(fileSize);
                    
                    const removeBtn = document.createElement('button');
                    removeBtn.className = 'btn-remove';
                    removeBtn.innerHTML = '&times;';
                    removeBtn.addEventListener('click', function(e) {
                        e.stopPropagation();
                        removeFile(index);
                    });
                    
                    previewItem.appendChild(img);
                    previewItem.appendChild(fileInfo);
                    previewItem.appendChild(removeBtn);
                    
                    subimagePreviews.appendChild(previewItem);
                });
            }
            
            // Remove file from array
            function removeFile(index) {
                subImages.splice(index, 1);
                updatePreviews();
                
                if (subImages.length === 0) {
                    emptyMessage.style.display = 'block';
                }
            }
            
            // Format file size
            function formatFileSize(bytes) {
                if (bytes === 0) return '0 Bytes';
                const k = 1024;
                const sizes = ['Bytes', 'KB', 'MB', 'GB'];
                const i = Math.floor(Math.log(bytes) / Math.log(k));
                return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
            }
            
            // Form submission handling
            const form = document.getElementById('productForm');
            const confirmModal = document.getElementById('confirmModal');
            const btnCancel = document.getElementById('btnCancel');
            const btnConfirm = document.getElementById('btnConfirm');
            const submitBtn = document.getElementById('submitBtn');
            
            // Allow decimal numbers in price and discount
            document.getElementById('price').addEventListener('input', function(e) {
                this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');
            });
            
            document.getElementById('discount').addEventListener('input', function(e) {
                this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');
            });

            // Handle form submission button click
            submitBtn.addEventListener('click', function(e) {
                e.preventDefault();
                
                const requiredFields = ['name', 'shortdesc', 'price', 'discount', 'productdetail'];
                let isValid = true;
                
                // Validate required fields
                requiredFields.forEach(field => {
                    const element = document.querySelector(`[name="${field}"]`);
                    if (!element.value.trim()) {
                        isValid = false;
                        element.style.borderColor = 'red';
                    } else {
                        element.style.borderColor = '';
                    }
                });
                
                // Validate price is positive number
                const price = parseFloat(document.getElementById('price').value);
                if (isNaN(price) || price <= 0) {
                    isValid = false;
                    document.getElementById('price').style.borderColor = 'red';
                }
                
                if (isValid) {
                    confirmModal.style.display = 'flex';
                } else {
                    // Scroll to first error
                    const firstError = document.querySelector('[style*="border-color: red"]');
                    if (firstError) {
                        firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    }
                }
            });
            
            // Confirm button in modal
            btnConfirm.addEventListener('click', function() {
                // First scroll to top
                window.scrollTo({
                    top: 0
                });
    
                // Then hide modal and proceed with submission
                confirmModal.style.display = 'none';
                
                // Create a new FormData object
                const formData = new FormData(form);
                
                // Clear any existing subImages from FormData
                formData.delete('subImages[]');
                
                // Add each sub-image to FormData
                subImages.forEach((file, index) => {
                    formData.append('subImages[]', file);
                });
                
                // Submit the form with all data
                fetch(form.action, {
                    method: 'POST',
                    body: formData
                })
                .then(response => {
                    if (response.redirected) {
                        window.location.href = response.url;
                    } else {
                        return response.text();
                    }
                })
                .then(data => {
                    if (data) {
                        document.body.innerHTML = data;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
            });
            
            // Cancel button in modal
            btnCancel.addEventListener('click', function() {
                confirmModal.style.display = 'none';
            });
            
            // Close modal when clicking outside
            confirmModal.addEventListener('click', function(e) {
                if (e.target === confirmModal) {
                    confirmModal.style.display = 'none';
                }
            });
            
            // Close with Escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && confirmModal.style.display === 'flex') {
                    confirmModal.style.display = 'none';
                }
            });
        });
    </script>
</body>
</html>