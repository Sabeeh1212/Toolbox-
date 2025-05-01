/**
 * E-Commerce Functionality Script
 * Handles: Search, Add to Cart, Cart Management, and UI Interactions
 */

document.addEventListener('DOMContentLoaded', function() {
    // Initialize all functionality
    initSearch();
    initCart();
    initMobileMenu();
});

// ==================== SEARCH FUNCTIONALITY ====================
function initSearch() {
    const searchBtn = document.getElementById('searchBtn');
    const searchInput = document.getElementById('searchInput');
    
    if (!searchBtn || !searchInput) return;

    // Click handler for search button
    searchBtn.addEventListener('click', handleSearch);
    
    // Enter key handler for search input
    searchInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') handleSearch();
    });
    
    // Optional: Live search as user types
    searchInput.addEventListener('input', function() {
        const term = this.value.trim();
        if (term.length > 2) performSearch(term);
    });
}

function handleSearch() {
    const searchTerm = document.getElementById('searchInput').value.trim();
    
    if (!searchTerm) {
        showToast('Please enter a search term', 'warning');
        return;
    }
    
    performSearch(searchTerm);
}

async function performSearch(term) {
    try {
        const response = await fetch(`search_ajax.php?q=${encodeURIComponent(term)}`);
        
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        const html = await response.text();
        document.getElementById('productContainer').innerHTML = html;
        document.getElementById('products').scrollIntoView({ behavior: 'smooth' });
    } catch (error) {
        console.error('Search failed:', error);
        showToast('Search failed. Please try again.', 'error');
    }
}

// ==================== CART FUNCTIONALITY ====================
function initCart() {
    // Initialize cart count
    updateCartCount();
    
    // Add to cart button handlers (delegated)
    document.addEventListener('click', function(e) {
        const addToCartBtn = e.target.closest('.btn-add-to-cart');
        if (addToCartBtn) {
            e.preventDefault();
            const productId = addToCartBtn.dataset.id;
            addToCart(productId, addToCartBtn);
        }
        
        // Cart icon click handler
        if (e.target.closest('.cart-icon')) {
            window.location.href = 'cart.php';
        }
    });
}

async function addToCart(productId, button) {
    if (!productId) return;
    
    const originalText = button.innerHTML;
    button.disabled = true;
    button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Adding...';
    
    try {
        const response = await fetch('add_to_cart.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `product_id=${productId}`
        });
        
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        const data = await response.json();
        
        if (data.success) {
            updateCartCount(data.cart_count);
            showVisualFeedback(button);
            showToast('Product added to cart!', 'success');
        } else {
            showToast(data.message || 'Failed to add to cart', 'error');
        }
    } catch (error) {
        console.error('Add to cart failed:', error);
        showToast('Failed to add to cart. Please try again.', 'error');
    } finally {
        button.disabled = false;
        button.innerHTML = originalText;
    }
}

function showVisualFeedback(button) {
    const productCard = button.closest('.product-card');
    if (!productCard) return;
    
    productCard.classList.add('added-to-cart');
    setTimeout(() => {
        productCard.classList.remove('added-to-cart');
    }, 1000);
}

async function updateCartCount(count) {
    // If count is provided, use it
    if (count !== undefined) {
        document.querySelectorAll('.cart-count').forEach(el => {
            el.textContent = count;
        });
        return;
    }
    
    // Otherwise fetch from server
    try {
        const response = await fetch('get_cart_count.php');
        if (response.ok) {
            const data = await response.json();
            if (data.cart_count !== undefined) {
                document.querySelectorAll('.cart-count').forEach(el => {
                    el.textContent = data.cart_count;
                });
            }
        }
    } catch (error) {
        console.error('Failed to update cart count:', error);
    }
}

// ==================== CART PAGE FUNCTIONALITY ====================
function initCartPage() {
    if (!document.querySelector('.cart-page')) return;
    
    // Quantity adjustments
    document.querySelectorAll('.btn-quantity').forEach(button => {
        button.addEventListener('click', function() {
            const input = this.parentElement.querySelector('.quantity-input');
            let quantity = parseInt(input.value);
            
            if (this.classList.contains('minus') && quantity > 1) {
                quantity--;
            } else if (this.classList.contains('plus')) {
                quantity++;
            }
            
            input.value = quantity;
            updateCartItem(this.closest('.cart-item').dataset.id, quantity);
        });
    });
    
    // Manual quantity input
    document.querySelectorAll('.quantity-input').forEach(input => {
        input.addEventListener('change', function() {
            let quantity = parseInt(this.value) || 1;
            if (quantity < 1) quantity = 1;
            this.value = quantity;
            updateCartItem(this.closest('.cart-item').dataset.id, quantity);
        });
    });
    
    // Remove item buttons
    document.querySelectorAll('.btn-remove-item').forEach(button => {
        button.addEventListener('click', function() {
            removeFromCart(this.closest('.cart-item').dataset.id);
        });
    });
}

async function updateCartItem(productId, quantity) {
    try {
        const response = await fetch('update_cart.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `product_id=${productId}&quantity=${quantity}`
        });
        
        if (response.ok) {
            location.reload(); // Refresh to show updated totals
        }
    } catch (error) {
        console.error('Failed to update cart item:', error);
        showToast('Failed to update quantity', 'error');
    }
}

async function removeFromCart(productId) {
    if (!confirm('Remove this item from your cart?')) return;
    
    try {
        const response = await fetch('update_cart.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `product_id=${productId}&remove=true`
        });
        
        if (response.ok) {
            location.reload(); // Refresh to show updated cart
        }
    } catch (error) {
        console.error('Failed to remove item:', error);
        showToast('Failed to remove item', 'error');
    }
}

// ==================== UI HELPERS ====================
function initMobileMenu() {
    const hamburger = document.getElementById('hamburger');
    const navLinks = document.getElementById('navLinks');
    
    if (hamburger && navLinks) {
        hamburger.addEventListener('click', function() {
            this.classList.toggle('active');
            navLinks.classList.toggle('active');
        });
    }
}

function showToast(message, type = 'success') {
    const toast = document.createElement('div');
    toast.className = `toast ${type}`;
    toast.textContent = message;
    document.body.appendChild(toast);
    
    setTimeout(() => {
        toast.classList.add('show');
        setTimeout(() => {
            toast.classList.remove('show');
            setTimeout(() => toast.remove(), 300);
        }, 3000);
    }, 10);
}

// Initialize cart page if needed
if (document.querySelector('.cart-page')) {
    initCartPage();
}