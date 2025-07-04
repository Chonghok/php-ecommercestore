//Search Overlay
const searchIcon = document.getElementById('searchIcon');
const searchOverlay = document.getElementById('searchOverlay');
const searchInput = document.getElementById('searchInput');

// Show overlay when search icon clicked
searchIcon.addEventListener('click', (e) => {
    e.preventDefault(); // prevent # link
    searchOverlay.classList.add('active');
    document.body.style.overflow = 'hidden'; // prevent scroll
    searchInput.focus(); // focus on input field
});
// Hide overlay on click outside or ESC
function hideSearchOverlay() {
    searchOverlay.classList.remove('active');
    document.body.style.overflow = ''; // allow scroll again
}
// Hide overlay when clicking outside the search box
searchOverlay.addEventListener('click', (e) => {
    if (e.target === searchOverlay) { // only if clicking on the background
        hideSearchOverlay();
    }
});
document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') { // if ESC key is pressed
        hideSearchOverlay();
    }
});




//User Logout
const userInfo = document.getElementById('userInfo');
const dropdownMenu = document.getElementById('dropdownMenu');
const userDropdown = document.querySelector('.user-dropdown');

if (userInfo) {
    userInfo.addEventListener('click', () => {
        dropdownMenu.classList.toggle('show');
        userDropdown.classList.toggle('active'); // Toggle active class for color/rotation
    });
}



// Search Results
document.addEventListener("DOMContentLoaded", function () {
    const searchInput2 = document.getElementById("searchInput");
    const resultContainer = document.getElementById("resultContainer");
    const overlay = document.getElementById("searchOverlay");

    if (!searchInput2) return;

    searchInput2.addEventListener("input", async function () {
        const query = searchInput2.value.trim();
        console.log("Search query:", query);
        if (query.length === 0) {
            resultContainer.classList.remove("show");
            resultContainer.innerHTML = "";
            return;
        }

        try {
            const response = await fetch(`api/search-api.php?query=${encodeURIComponent(query)}`);
            const data = await response.json();
            resultContainer.classList.add("show");

            let html = "";

            if (data.length === 0) {
                html += `<h3>No results for "${query}"</h3>`;
            } else {
                html += `<h3>Results for "${query}":</h3>`;
                data.forEach(product => {
                    const isSoldOut = product.stock == 0;
                    const hasDiscount = product.discountedPrice !== null && parseFloat(product.discountedPrice) > 0;

                    html += `
                        <div class="result-item" data-id="${product.productID}">
                            <div class="result-image">
                                <img src="images/${product.categoryName}/${product.mainImage}" alt="${product.name}">
                                ${isSoldOut ? `
                                    <div class="result-out-of-stock">
                                        <div class="result-sold-out">SOLD<br>OUT</div>
                                    </div>` : ''
                                }
                            </div>
                            <div class="result-text">
                                <h4>${product.name}</h4>
                                ${
                                    hasDiscount 
                                    ? `<p class="result-discount">$${product.discountedPrice} <strike>$${product.price}</strike></p>` 
                                    : `<p>$${product.price}</p>`
                                }
                            </div>
                        </div>
                    `;
                });
            }

            resultContainer.innerHTML = html;

            // Add click event listeners to all result items
            document.querySelectorAll('.result-item').forEach(item => {
                item.addEventListener('click', function() {
                    const productId = this.getAttribute('data-id');
                    window.location.href = `product-detail.php?id=${productId}`;
                });
            });

        } catch (err) {
            console.error("Search error:", err);
            resultContainer.innerHTML = `<h3>Something went wrong. Try again later.</h3>`;
        }
    });

    searchInput2.addEventListener("keypress", function (event) {
        if (event.key === "Enter") {
            window.location.href = `search.php?value=${encodeURIComponent(searchInput2.value.trim())}`;
        }
    });
    
});




// User Logout
document.addEventListener('DOMContentLoaded', function() {
    // Create modal container
    const modal = document.createElement('div');
    modal.className = 'logout-modal';
    
    // Create modal content box
    const modalContent = document.createElement('div');
    modalContent.className = 'logout-modal-content';
    
    // Create text element
    const modalText = document.createElement('p');
    modalText.className = 'logout-modal-text';
    modalText.textContent = 'Are you sure you want to log out?';
    
    // Create buttons container
    const modalButtons = document.createElement('div');
    modalButtons.className = 'logout-modal-buttons';
    
    // Create Cancel button
    const cancelBtn = document.createElement('button');
    cancelBtn.className = 'logout-modal-btn logout-modal-cancel';
    cancelBtn.textContent = 'Cancel';
    
    // Create Confirm button
    const confirmBtn = document.createElement('button');
    confirmBtn.className = 'logout-modal-btn logout-modal-confirm';
    confirmBtn.textContent = 'Log Out';
    
    // Build the modal structure
    modalButtons.appendChild(cancelBtn);
    modalButtons.appendChild(confirmBtn);
    modalContent.appendChild(modalText);
    modalContent.appendChild(modalButtons);
    modal.appendChild(modalContent);
    
    // Add modal to body
    document.body.appendChild(modal);
    
    // Make the function available globally
    window.confirmLogout = function() {
        // Show modal
        modal.classList.add('active');
        
        // Button event handlers
        cancelBtn.onclick = function() {
            modal.classList.remove('active');
        };
        
        confirmBtn.onclick = function() {
            window.location.href = 'logout.php';
        };
        
        // Close when clicking outside modal
        modal.onclick = function(e) {
            if (e.target === modal) {
                modal.classList.remove('active');
            }
        };
    };
});



// Scroll Reveal for product items
const  scrollRevealOption = {
    distance: '50px',
    duration: 1000,
    origin: 'bottom',
    delay: 0
}
document.querySelectorAll('.product-row').forEach(row => {
    const items = row.querySelectorAll('.product-item');
    items.forEach((item, index) => {
        ScrollReveal().reveal(item, {
            ...scrollRevealOption,
            delay: 200 + index * 100
        });
    });
});

ScrollReveal().reveal(".category", {
    ...scrollRevealOption,
    delay: 200
});