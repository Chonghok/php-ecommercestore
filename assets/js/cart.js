document.addEventListener('DOMContentLoaded', function() {
    // Quantity input validation and real-time update
    const cartQuantityInputs = document.querySelectorAll(".cart-quantity");
    
    cartQuantityInputs.forEach((input) => {
        function validateQuantity() {
            const max = parseInt(input.max);
            let value = parseInt(input.value);

            if (isNaN(value) || value < 1) {
                input.value = 1;
            } else if (value > max) {
                input.value = max;
            }
        }
        
        input.addEventListener("change", function() {
            validateQuantity();
            const productID = this.closest('tr').dataset.productId;
            const newQuantity = this.value;
            
            updateCartItem(productID, newQuantity);
        });
        
        input.addEventListener("blur", validateQuantity);
    });

    // Remove button functionality
    const removeButtons = document.querySelectorAll(".cart-remove");
    removeButtons.forEach(button => {
        button.addEventListener("click", function() {
            const productID = this.closest('tr').dataset.productId;
            const productName = this.closest('.cart-info').querySelector('h4').textContent;
            
            showRemoveConfirmation(productID, productName);
        });
    });

    // Clear cart button functionality
    const clearCartBtn = document.querySelector(".btn-clear");
    if (clearCartBtn) {
        clearCartBtn.addEventListener("click", showClearConfirmation);
    }

    // Confirmation modal elements
    const confirmationModal = document.getElementById("confirmationModal");
    const cancelRemoveBtn = document.getElementById("cancelRemove");
    const confirmRemoveBtn = document.getElementById("confirmRemove");
    const confirmationMessage = document.getElementById("confirmationMessage");
    
    // Show confirmation for removing single item
    function showRemoveConfirmation(productID, productName) {
        confirmationMessage.textContent = `Remove "${productName}" from cart?`;
        confirmationModal.classList.add("active");
        
        confirmRemoveBtn.onclick = function() {
            removeCartItem(productID);
            confirmationModal.classList.remove("active");
        };
    }
    
    // Show confirmation for clearing entire cart
    function showClearConfirmation() {
        confirmationMessage.textContent = "Clear all items from your cart?";
        confirmationModal.classList.add("active");
        
        confirmRemoveBtn.onclick = function() {
            clearCart();
            confirmationModal.classList.remove("active");
        };
    }
    
    // Cancel button handler
    cancelRemoveBtn.addEventListener("click", function() {
        confirmationModal.classList.remove("active");
    });

    // Close modal when clicking outside
    confirmationModal.addEventListener("click", function(e) {
        if (e.target === confirmationModal) {
            confirmationModal.classList.remove("active");
        }
    });

    // Rest of your existing functions (updateCartItem, removeCartItem, clearCart, etc.)
    // ... keep all the other functions exactly as they were ...
    function updateCartItem(productID, quantity) {
        fetch('api/update-cart.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `productID=${productID}&quantity=${quantity}&action=update`
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const row = document.querySelector(`tr[data-product-id="${productID}"]`);
                const priceElement = row.querySelector('.cart-info span:not(.cart-discount)');
                const discountElement = row.querySelector('.cart-discount');
                
                let price;
                if (discountElement) {
                    price = parseFloat(discountElement.textContent.replace('$', ''));
                } else {
                    price = parseFloat(priceElement.textContent.replace('$', ''));
                }
                
                const total = (price * quantity).toFixed(2);
                row.querySelector('.total').textContent = `$${total}`;
                
                updateGrandTotal();
                updateCartCount(data.cartCount);
            }
        })
        .catch(error => console.error('Error:', error));
    }

    function removeCartItem(productID) {
        fetch('api/update-cart.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `productID=${productID}&action=remove`
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const row = document.querySelector(`tr[data-product-id="${productID}"]`);
                if (row) row.remove();
                
                updateGrandTotal();
                updateCartCount(data.cartCount);
                showMiniToast("Removed from cart");
                
                if (data.cartCount === 0) {
                    setTimeout(() => location.reload(), 1000);
                }
            }
        })
        .catch(error => console.error('Error:', error));
    }

    function clearCart() {
        fetch('api/update-cart.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `action=clear`
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                updateCartCount(0);
                location.reload();
                // showMiniToast("Cart cleared");
            }
        })
        .catch(error => console.error('Error:', error));
    }

    function updateGrandTotal() {
        let subtotal = 0;
        document.querySelectorAll('.total:not(.cart-total-price .total)').forEach(element => {
            subtotal += parseFloat(element.textContent.replace('$', ''));
        });
        
        const shippingFee = (Math.floor(subtotal * 0.10)).toFixed(2);
        const grandTotal = (parseFloat(subtotal) + parseFloat(shippingFee)).toFixed(2);
        
        document.querySelector('.cart-total-price tr:nth-child(1) td:last-child').textContent = `$${subtotal.toFixed(2)}`;
        document.querySelector('.cart-total-price tr:nth-child(2) td:last-child').textContent = `$${shippingFee}`;
        document.querySelector('.cart-total-price .total').textContent = `$${grandTotal}`;
    }

    function updateCartCount(count) {
        const cartCountElements = document.querySelectorAll('.cart-count');
        cartCountElements.forEach(element => {
            element.textContent = count;
        });
    }

    function showMiniToast(message) {
        const miniToast = document.querySelector('.mini-toast-cart');
        if (!miniToast) return;
        
        miniToast.querySelector('span').textContent = message;
        miniToast.classList.add('show');
        
        setTimeout(() => {
            miniToast.classList.remove('show');
        }, 2500);
    }
});