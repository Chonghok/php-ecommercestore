// Function to trigger file input when clicking the "Upload" button
function triggerMainImageInput() {
  document.getElementById('main-image-upload').click();
}

// Function to upload the image
function uploadMainImage(event) {
  const file = event.target.files[0];
  
  if (file) {
    const reader = new FileReader();
    reader.onload = function(e) {
      // Show the image preview and display the uploaded image
      const imagePreview = document.getElementById('main-image-preview');
      const mainImage = document.getElementById('main-image');
      const noImageMessage = document.getElementById('no-image-message');
      
      imagePreview.style.display = 'block'; // Show the preview
      mainImage.src = e.target.result; // Set the uploaded image as the source
      noImageMessage.style.display = 'none'; // Hide the 'No image uploaded' message
    }
    reader.readAsDataURL(file);
  }
}

// Function to delete the uploaded image
function deleteMainImage() {
  const imagePreview = document.getElementById('main-image-preview');
  const mainImage = document.getElementById('main-image');
  const noImageMessage = document.getElementById('no-image-message');
  
  // Reset the image preview and input field
  imagePreview.style.display = 'none'; // Hide the preview
  mainImage.src = ''; // Clear the image source
  document.getElementById('main-image-upload').value = ''; // Reset file input
  
  // Show the 'No image uploaded' message
  noImageMessage.style.display = 'block';
}


let subimageId = 1; // Start with 1 for the first subimage

// Trigger the file input click event
function triggerFileInput() {
  document.getElementById('subimage-upload').click();
}

// Handle image upload and dynamically add to the subimage list
function uploadSubimage(event) {
  const file = event.target.files[0];
  if (file) {
    const currentId = subimageId; // Capture the current subimageId before it changes

    const imageUrl = URL.createObjectURL(file);
    const newSubimageRow = document.createElement('div');
    newSubimageRow.classList.add('subimage-row');
    newSubimageRow.id = `subimage-row-${currentId}`;

    const imagePreview = document.createElement('img');
    imagePreview.src = imageUrl;
    imagePreview.alt = `Sub Image ${currentId}`;
    imagePreview.classList.add('subimage-preview');

    const deleteButton = document.createElement('button');
    deleteButton.classList.add('btn-remove');
    deleteButton.textContent = 'Delete';

    // Use the captured ID for the delete function
    deleteButton.addEventListener('click', (e) => {
      e.preventDefault();
      removeSubimage(currentId);
    });

    newSubimageRow.appendChild(imagePreview);
    newSubimageRow.appendChild(deleteButton);
    document.getElementById('subimage-row-container').appendChild(newSubimageRow);

    subimageId++; // Increment after using the current ID
    document.getElementById('no-subimages').style.display = 'none';
  }
}

// Remove subimage by ID
function removeSubimage(id) {
  const row = document.getElementById(`subimage-row-${id}`);
  if (row) {
    row.remove();
  }

  // If no subimages left, show the "no subimages" message
  const container = document.getElementById('subimage-row-container');
  if (container.children.length === 0) {
    document.getElementById('no-subimages').style.display = 'block';
  }
}
