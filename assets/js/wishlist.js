// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    // Initialize remove buttons
    initRemoveButtons();
    
    // Initialize clear wishlist button
    initClearWishlistButton();
    
    // Initialize add to cart buttons
    initAddToCartButtons();
});

// Initialize remove buttons
function initRemoveButtons() {
    document.querySelectorAll('.wishlist-remove').forEach(button => {
        button.addEventListener('click', async function() {
            const productId = this.closest('.wishlist-item').querySelector('.wishlist-image').getAttribute('onclick').match(/id=(\d+)/)[1];
            
            const confirmed = await showConfirmation('Remove from wishlist?');
            if (!confirmed) return;
            
            try {
                const response = await fetch('api/remove-from-wishlist.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ product_id: productId })
                });
                
                const data = await response.json();
                
                if (data.success) {
                    // Remove item from DOM
                    this.closest('.wishlist-item').remove();
                    
                    // Update wishlist count
                    updateWishlistCount();
                    
                    // Show toast
                    showToast('Removed from wishlist');
                    
                    // Update item count in title
                    updateWishlistItemCount();
                    
                    // If no items left, show empty state
                    if (document.querySelectorAll('.wishlist-item').length === 0) {
                        showEmptyWishlist();
                    }
                } else {
                    showToast(data.message || 'Failed to remove item');
                }
            } catch (error) {
                console.error('Error:', error);
                showToast('An error occurred');
            }
        });
    });
}

// Initialize clear wishlist button
function initClearWishlistButton() {
    document.querySelector('.btn-clear')?.addEventListener('click', async function() {
        const confirmed = await showConfirmation('Clear all items from wishlist?');
        if (!confirmed) return;
        
        try {
            const response = await fetch('api/clear-wishlist.php', {
                method: 'POST'
            });
            
            const data = await response.json();
            
            if (data.success) {
                // Reload page to show empty state
                location.reload();
            } else {
                showToast(data.message || 'Failed to clear wishlist');
            }
        } catch (error) {
            console.error('Error:', error);
            showToast('An error occurred');
        }
    });
}

// Initialize add to cart buttons
function initAddToCartButtons() {
    document.querySelectorAll('.btn-atc').forEach(button => {
        button.addEventListener('click', async function() {
            const productId = this.closest('.wishlist-item').querySelector('.wishlist-image').getAttribute('onclick').match(/id=(\d+)/)[1];
            
            try {
                const response = await fetch('api/add-to-cart.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        product_id: productId,
                        quantity: 1 // Default quantity
                    })
                });
                
                const data = await response.json();
                
                if (data.success) {
                    // Change button state
                    this.innerHTML = '<i class="ri-shopping-cart-2-fill"></i> ADDED TO CART';
                    this.classList.add('btn-atc-added');
                    this.classList.remove('btn-atc');
                    this.disabled = true;
                    
                    // Update cart count
                    const countElement = document.querySelector('.cart-count');
                    if (countElement) {
                        countElement.textContent = data.count;
                    }
                    
                    // Show toast
                    showCartToast('Added to cart');
                } else {
                    showToast(data.message || 'Failed to add to cart');
                }
            } catch (error) {
                console.error('Error:', error);
                showToast('An error occurred');
            }
        });
    });
}

// Show confirmation modal
function showConfirmation(message) {
    return new Promise((resolve) => {
        const modal = document.getElementById('confirmationModal');
        const messageEl = document.getElementById('confirmationMessage');
        
        messageEl.textContent = message;
        modal.classList.add('active');
        
        const handleConfirm = () => {
            cleanup();
            resolve(true);
        };
        
        const handleCancel = () => {
            cleanup();
            resolve(false);
        };
        
        const cleanup = () => {
            document.getElementById('confirmRemove').removeEventListener('click', handleConfirm);
            document.getElementById('cancelRemove').removeEventListener('click', handleCancel);
            modal.classList.remove('active');
        };
        
        document.getElementById('confirmRemove').addEventListener('click', handleConfirm);
        document.getElementById('cancelRemove').addEventListener('click', handleCancel);
    });
}

// Update wishlist count in header
function updateWishlistCount() {
    fetch('api/get-wishlist-count.php')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const countElement = document.querySelector('.wishlist-count');
                if (countElement) {
                    countElement.textContent = data.count;
                }
            }
        })
        .catch(error => console.error('Error:', error));
}

// Update wishlist item count in title
function updateWishlistItemCount() {
    const itemCount = document.querySelectorAll('.wishlist-item').length;
    const titleSpan = document.querySelector('.small-title h1 span');
    if (titleSpan) {
        titleSpan.textContent = `(${itemCount} item${itemCount !== 1 ? 's' : ''})`;
    }
}

// Show empty wishlist state
function showEmptyWishlist() {
    document.querySelector('.wishlist-container').innerHTML = `
        <div class="no-product">
            <h1>No products in your wishlist yet</h1>
        </div>
    `;
    document.querySelector('.btn-clear').style.display = 'none';
    document.querySelector('.small-title').style.display = 'none';
    // location.reload();
}

// Show toast notification
function showToast(message) {
    const toast = document.querySelector('.mini-toast');
    if (!toast) return;
    
    toast.querySelector('span').textContent = message;
    toast.classList.add('show');
    
    setTimeout(() => {
        toast.classList.remove('show');
    }, 2500);
}

// Show cart toast notification
function showCartToast(message) {
    const toast = document.querySelector('.mini-toast-cart');
    if (!toast) return;
    
    toast.querySelector('span').textContent = message;
    toast.classList.add('show');
    
    setTimeout(() => {
        toast.classList.remove('show');
    }, 2500);
}