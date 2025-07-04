// Function to trigger file input when clicking the "Upload" button for main image
function triggerMainImageInput() {
    document.getElementById('main-image-upload').click();
}

// Function to upload the main image
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

let subImages = []; // Array to hold sub images data

// Function to trigger file input for sub-images
function triggerFileInput() {
    document.getElementById('subimage-upload').click();
}

// Initialize sub-image upload functionality
document.addEventListener('DOMContentLoaded', function() {
    // Set up sub-image upload change handler
    const subImageUpload = document.getElementById('subimage-upload');
    subImageUpload.addEventListener('change', uploadSubimage);
    
    // Make sure multiple attribute is set (just in case)
    subImageUpload.setAttribute('multiple', 'multiple');
});

function uploadSubimage(event) {
    const files = event.target.files;
    if (files && files.length > 0) {
        document.getElementById('no-subimages').style.display = 'none';
        
        // Clear existing previews if you want to replace all images
        // document.getElementById('subimage-row-container').innerHTML = '';
        
        // Add new files to the existing array (or replace if you prefer)
        subImages = Array.from(files);
        
        // Clear and recreate all previews
        const container = document.getElementById('subimage-row-container');
        container.innerHTML = '';
        
        // Create previews for all selected files
        subImages.forEach((file, i) => {
            const reader = new FileReader();
            
            reader.onload = function(e) {
                const newSubimageRow = document.createElement('div');
                newSubimageRow.classList.add('subimage-row');
                
                const imagePreview = document.createElement('img');
                imagePreview.src = e.target.result;
                imagePreview.alt = `Sub Image ${i+1}`;
                imagePreview.classList.add('subimage-preview');
                
                const deleteButton = document.createElement('button');
                deleteButton.classList.add('btn-remove');
                deleteButton.textContent = 'Remove';
                deleteButton.dataset.index = i;
                
                deleteButton.addEventListener('click', function(e) {
                    e.preventDefault();
                    removeSubimage(this.dataset.index);
                });
                
                newSubimageRow.appendChild(imagePreview);
                newSubimageRow.appendChild(deleteButton);
                container.appendChild(newSubimageRow);
            };
            
            reader.readAsDataURL(file);
        });
    }
}

function removeSubimage(index) {
    // Remove from array
    subImages.splice(index, 1);
    
    // Refresh previews
    document.getElementById('subimage-upload').value = '';
    const container = document.getElementById('subimage-row-container');
    container.innerHTML = '';
    
    if (subImages.length > 0) {
        // Recreate previews with remaining images
        subImages.forEach((file, i) => {
            const reader = new FileReader();
            reader.onload = function(e) {
                const newSubimageRow = document.createElement('div');
                newSubimageRow.classList.add('subimage-row');
                
                const imagePreview = document.createElement('img');
                imagePreview.src = e.target.result;
                imagePreview.alt = `Sub Image ${i+1}`;
                
                const deleteButton = document.createElement('button');
                deleteButton.classList.add('btn-remove');
                deleteButton.textContent = 'Remove';
                deleteButton.dataset.index = i;
                
                deleteButton.addEventListener('click', function(e) {
                    e.preventDefault();
                    removeSubimage(this.dataset.index);
                });
                
                newSubimageRow.appendChild(imagePreview);
                newSubimageRow.appendChild(deleteButton);
                container.appendChild(newSubimageRow);
            };
            reader.readAsDataURL(file);
        });
    } else {
        document.getElementById('no-subimages').style.display = 'block';
    }
}


// Form validation and confirmation
document.addEventListener('DOMContentLoaded', function() {
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
        
        // Validate main image
        if (!document.getElementById('main-image-upload').files[0]) {
            isValid = false;
            document.getElementById('no-image-message').style.color = 'red';
        } else {
            document.getElementById('no-image-message').style.color = '#777';
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
        confirmModal.style.display = 'none';
        form.submit();
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