const decreaseBtn = document.getElementById("decreaseBtn");
const increaseBtn = document.getElementById("increaseBtn");
const quantityInput = document.getElementById("quantityInput");

function validateQuantity() {
    const max = parseInt(quantityInput.max);
    let value = parseInt(quantityInput.value);

    if (isNaN(value) || value < 1) {
        quantityInput.value = 1;
    } else if (value > max) {
        quantityInput.value = max;
    }
    updateButtons();
}

function updateButtons() {
    const max = parseInt(quantityInput.max);
    let quantity = parseInt(quantityInput.value) || 1;
    decreaseBtn.disabled = quantity <= 1;
    increaseBtn.disabled = quantity >= max;
}

// Allow typing freely, but validate when the user finishes
quantityInput.addEventListener("blur", validateQuantity);
quantityInput.addEventListener("change", validateQuantity);

increaseBtn.addEventListener("click", function () {
    const max = parseInt(quantityInput.max);
    let quantity = parseInt(quantityInput.value);

    if (quantity < max) {
        quantityInput.value = quantity + 1;
    }
    updateButtons();
});
decreaseBtn.addEventListener("click", function () {
    let quantity = parseInt(quantityInput.value);
    if (quantity > 1) {
        quantityInput.value = quantity - 1;
    }
    updateButtons();
});
updateButtons();








const smallProductSwiper = new Swiper(".small-product-swiper", {
    slidesPerView: 4, 
    spaceBetween: 10,
    allowTouchMove: true, 
});
const bigProductSwiper = new Swiper(".big-product-swiper", {
    slidesPerView: 1,
    loop: true,
    navigation: {
        nextEl: ".swiper-button-next",
        prevEl: ".swiper-button-prev",
    },
    on: {
        slideChange: function () {
            updateSmallImageHighlight(this.realIndex);
            adjustSmallSwiperPosition(this.realIndex);
        },
    },
});
function updateSmallImageHighlight(index) {
    document.querySelectorAll(".small-image").forEach((img, i) => {
        img.classList.toggle("active", i === index);
    });
}
function adjustSmallSwiperPosition(index){ 
    const slidesPerView = smallProductSwiper.params.slidesPerView;
    const activeIndex = smallProductSwiper.activeIndex;

    if (index == 0) {
        smallProductSwiper.slideTo(0); 
    }
    else if (index == smallProductSwiper.slides.length - 1){
        smallProductSwiper.slideTo(index - slidesPerView + 1); 
    }
    else if (index >= activeIndex + slidesPerView) {
        smallProductSwiper.slideTo(activeIndex + 1);
    }
    else if (index < activeIndex) {
        smallProductSwiper.slideTo(activeIndex - 1);
    }
}
document.querySelectorAll(".small-image").forEach((img, index) => {
    img.addEventListener("click", () => {
        bigProductSwiper.slideToLoop(index);
    });
});





// Initialize all wishlist buttons
document.querySelectorAll('.wishlist').forEach(button => {
    // Only proceed if button has product ID
    if (!button.dataset.productId) return;
    
    // Check initial wishlist status when page loads (only if logged in)
    if (document.querySelector('.user-dropdown .username')) { // Check if user is logged in
        checkWishlistStatus(button);
    }

    // Click handler
    button.addEventListener('click', async function() {
        const productId = this.dataset.productId;
        const isFavorited = this.classList.contains('favorited');

        if (isFavorited) {
            try {
                const confirmed = await showConfirmation('Remove from wishlist?');
                if (!confirmed) return;
                
                const success = await updateWishlist(productId, 'remove');
                if (success) {
                    this.classList.remove('favorited');
                    showToast('Removed from wishlist');
                    updateWishlistCount();
                }
            } catch (error) {
                console.error('Error:', error);
                showToast('An error occurred');
            }
        } else {
            try {
                const success = await updateWishlist(productId, 'add');
                if (success) {
                    this.classList.add('favorited');
                    showToast('Added to wishlist');
                    updateWishlistCount();
                }
            } catch (error) {
                console.error('Error:', error);
                showToast('An error occurred');
            }
        }
    });
});

// Check if product is in wishlist
function checkWishlistStatus(button) {
    if (!button.dataset.productId) return;
    
    fetch(`api/check-wishlist.php?product_id=${button.dataset.productId}`)
        .then(response => {
            if (!response.ok) throw new Error('Network response was not ok');
            return response.json();
        })
        .then(data => {
            if (data.isFavorited) {
                button.classList.add('favorited');
            }
        })
        .catch(error => console.error('Error checking wishlist:', error));
}


// Add/remove from wishlist with login redirect handling
async function updateWishlist(productId, action) {
    try {
        const response = await fetch('api/toggle-wishlist.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ product_id: productId, action: action })
        });
        
        const data = await response.json();
        
        // Handle login redirect if not authenticated
        // if (data.redirect) {
        //     window.location.href = 'login.php';
        //     return false;
        // }
        
        // if (!data.success) {
        //     throw new Error(data.message || 'Operation failed');
        // }
        

        if (!data.success) {
            // Show the message from server (like "Please login first")
            showToast(data.message || 'Operation failed');
            return false;
        }

        return true;
    } catch (error) {
        console.error('Error:', error);
        showToast(error.message || 'An error occurred');
        return false;
    }
}

// Custom confirmation modal
function showConfirmation(message) {
    return new Promise((resolve) => {
        const modal = document.getElementById('confirmationModal');
        const messageEl = document.getElementById('confirmationMessage');
        const confirmBtn = document.getElementById('confirmRemove');
        const cancelBtn = document.getElementById('cancelRemove');
        
        // Set the message
        messageEl.textContent = message;
        
        // Show the modal
        modal.classList.add('active');
        
        // Handle clicks outside modal content
        const handleOutsideClick = (e) => {
            if (e.target === modal) {
                cleanup();
                resolve(false);
            }
        };

        // Temporary event handlers
        const handleConfirm = () => {
            cleanup();
            resolve(true);
        };
        
        const handleCancel = () => {
            cleanup();
            resolve(false);
        };
        
        const cleanup = () => {
            confirmBtn.removeEventListener('click', handleConfirm);
            cancelBtn.removeEventListener('click', handleCancel);
            modal.removeEventListener('click', handleOutsideClick);
            modal.classList.remove('active');
        };
        
        confirmBtn.addEventListener('click', handleConfirm);
        cancelBtn.addEventListener('click', handleCancel);
        modal.addEventListener('click', handleOutsideClick);
    });
}

// Show toast notification
function showToast(message) {
    const toast = document.querySelector('.mini-toast');
    if (!toast) {
        console.warn('Toast element not found');
        return;
    }
    
    const toastMessage = toast.querySelector('span');
    if (toastMessage) {
        toastMessage.textContent = message;
    }
    toast.classList.add('show');
    
    setTimeout(() => {
        toast.classList.remove('show');
    }, 2500);
}

// Update wishlist count in header
function updateWishlistCount() {
    fetch('api/get-wishlist-count.php')
        .then(response => {
            if (!response.ok) throw new Error('Network response was not ok');
            return response.json();
        })
        .then(data => {
            if (data.success) {
                const countElement = document.querySelector('.wishlist-count');
                if (countElement) {
                    countElement.textContent = data.count;
                }
            }
        })
        .catch(error => console.error('Error updating wishlist count:', error));
}









// Add to cart functionality
document.querySelector('.btn-addtocart')?.addEventListener('click', async function() {
    const productId = this.dataset.productId;
    if (!productId) {
        console.error('Product ID not found');
        return;
    }
    const quantity = parseInt(document.getElementById('quantityInput').value) || 1;
    
    try {
        const response = await fetch('api/add-to-cart.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                product_id: productId,
                quantity: quantity
            })
        });
        
        const data = await response.json();
        
        if (data.success) {
            // Show success toast
            showCartToast('Added to cart');
            
            // Update cart count
            const countElement = document.querySelector('.cart-count');
            if (countElement) {
                countElement.textContent = data.count;
            }
            
            // Change button state
            this.disabled = true;
            this.innerHTML = '<i class="ri-shopping-cart-2-fill"></i> ADDED TO CART';
            this.classList.add('btn-addtocart-added');
            this.classList.remove('btn-addtocart');
        } else {
            showCartToast(data.message || 'Failed to add to cart');
        }
    } catch (error) {
        console.error('Error:', error);
        showToast('An error occurred');
    }
});

// Show cart toast notification
function showCartToast(message) {
    const toast = document.querySelector('.mini-toast-cart');
    if (!toast) {
        console.warn('Cart toast element not found');
        return;
    }
    
    const toastMessage = toast.querySelector('span');
    if (toastMessage) {
        toastMessage.textContent = message;
    }
    toast.classList.add('show');
    
    setTimeout(() => {
        toast.classList.remove('show');
    }, 2500);
}