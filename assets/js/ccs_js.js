window.addEventListener('load', function() {
    
    // --- HERO SWIPER: Play/Pause Freeze Button ---
    const freezeBtn = document.getElementById('hero-freeze-btn');
    const swiperContainer = document.querySelector('.hero-swiper');
    
    if (freezeBtn && swiperContainer) {
        let checkSwiper = setInterval(function() {
            if (swiperContainer.swiper) {
                clearInterval(checkSwiper);
                const swiperInstance = swiperContainer.swiper;
                
                // 1. The Button Click
                freezeBtn.addEventListener('click', function(e) {
                    e.stopPropagation(); // Shield this click from other listeners
                    
                    if (swiperInstance.autoplay.running) {
                        swiperInstance.autoplay.stop();
                        freezeBtn.innerHTML = '<i class="fa-solid fa-play"></i>';
                    } else {
                        // FORCE UNLOCK Swiper's interaction block, then start
                        swiperInstance.params.autoplay.disableOnInteraction = false; 
                        swiperInstance.autoplay.start();
                        freezeBtn.innerHTML = '<i class="fa-solid fa-pause"></i>';
                    }
                });

                // 2. The Capture-Phase Override
                document.addEventListener('mousedown', function(e) {
                    // If they click our button, ignore it so it doesn't fight itself
                    if (e.target.closest('#hero-freeze-btn')) return;

                    if (e.target.closest('.swiper-pagination') || e.target.closest('.swiper-wrapper')) {
                        setTimeout(() => {
                            if (!swiperInstance.autoplay.running) {
                                freezeBtn.innerHTML = '<i class="fa-solid fa-play"></i>';
                            }
                        }, 50);
                    }
                }, true); 
                
                document.addEventListener('touchstart', function(e) {
                    if (e.target.closest('#hero-freeze-btn')) return;

                    if (e.target.closest('.swiper-pagination') || e.target.closest('.swiper-wrapper')) {
                        setTimeout(() => {
                            if (!swiperInstance.autoplay.running) {
                                freezeBtn.innerHTML = '<i class="fa-solid fa-play"></i>';
                            }
                        }, 50);
                    }
                }, {capture: true, passive: true});
            }
        }, 100);
        
        setTimeout(() => clearInterval(checkSwiper), 5000);
    }

    // --- HERO SWIPER: Custom Uploaded Video Looping ---
    const customVideos = document.querySelectorAll('video[data-loop-stop]');
    customVideos.forEach(vid => {
        const start = parseFloat(vid.getAttribute('data-loop-start')) || 0;
        const stop = parseFloat(vid.getAttribute('data-loop-stop'));
        
        // Approach A: Catch it 0.15 seconds BEFORE the browser hard-stops it
        vid.addEventListener('timeupdate', function() {
            if (this.currentTime >= (stop - 0.15)) {
                this.currentTime = start;
                this.play();
            }
        });

        // Approach B: If the browser manages to pause it anyway, hijack the pause
        vid.addEventListener('pause', function() {
            // Check if it paused near our designated stop time
            if (Math.abs(this.currentTime - stop) < 0.5) {
                this.currentTime = start;
                this.play();
            }
        });
    });
});

//cart modal

document.addEventListener('DOMContentLoaded', function() {
    // Initialize the Bootstrap Offcanvas
    var cartOffcanvasEl = document.getElementById('cartOffcanvas');
    if (!cartOffcanvasEl) return; // Exit if not on a page with the cart
    
    var bsOffcanvas = new bootstrap.Offcanvas(cartOffcanvasEl);
    var cartBody = document.getElementById('cart-modal-body');
    var cartBadges = document.querySelectorAll('.cart-qty-badge');

    // Function: Fetch fresh cart data and update the sidebar
    function refreshCartModal() {
        cartBody.innerHTML = '<div class="d-flex justify-content-center align-items-center h-100 p-5"><div class="spinner-border text-secondary" role="status"></div></div>';
        
        fetch(window.cartModalUrl)
            .then(response => response.text())
            .then(html => {
                cartBody.innerHTML = html;
                
                // Update the quantity badge
                var newCountEl = cartBody.querySelector('#ajax-cart-count');
                if (newCountEl && newCountEl.textContent !== "") {
                    cartBadges.forEach(badge => badge.textContent = newCountEl.textContent);
                }
            })
            .catch(error => console.error('Error fetching cart:', error));
    }

    // Action 1: ADD TO CART (Intercepts form submission)
    document.addEventListener('submit', function(e) {
        if (e.target.classList.contains('cart-form')) {
            e.preventDefault();
            var form = e.target;
            
            fetch(form.action, {
                method: 'POST',
                body: new FormData(form)
            }).then(() => {
                bsOffcanvas.show(); // Slide sidebar open
                refreshCartModal(); // Fetch updated items
            });
        }
    });

    // Action 2 & 3: UPDATE OR REMOVE ITEMS (Inside the modal)
    document.addEventListener('click', function(e) {
        // Remove Button
        var removeBtn = e.target.closest('.cart-remove');
        if (removeBtn) {
            e.preventDefault();
            fetch(removeBtn.href).then(() => refreshCartModal());
        }
        
        // Update Quantities Button
        var updateBtn = e.target.closest('.cart-update');
        if (updateBtn) {
            e.preventDefault();
            var form = updateBtn.closest('form');
            fetch(form.action, {
                method: 'POST',
                body: new FormData(form)
            }).then(() => refreshCartModal());
        }
    });

    // Optional: Refresh the cart when manually opened
    cartOffcanvasEl.addEventListener('show.bs.offcanvas', function () {
        refreshCartModal();
    });
});


// Wishlist Logic - Native UI Architecture
document.addEventListener('DOMContentLoaded', function() {
    var wishlistOffcanvasEl = document.getElementById('wishlistOffcanvas');
    if (!wishlistOffcanvasEl) return;

    var wishlistBadge = document.getElementById('wishlist-count-badge');

    // Function to safely subtract from the badge count
    function updateWishlistCount() {
        if (wishlistBadge) {
            let currentCount = parseInt(wishlistBadge.textContent) || 0;
            let newCount = currentCount - 1;
            if (newCount < 0) newCount = 0;
            wishlistBadge.textContent = newCount;
        }
    }

    // Function to check if list is empty and show the empty message
    function checkEmptyState() {
        var remainingItems = wishlistOffcanvasEl.querySelectorAll('.wishlist-item-row');
        if (remainingItems.length === 0) {
            var emptyMsg = wishlistOffcanvasEl.querySelector('.empty-wishlist-msg');
            if (emptyMsg) {
                emptyMsg.style.display = 'block';
            } else {
                // Fallback if the empty-wishlist-msg div isn't caught
                var offcanvasBody = document.getElementById('wishlist-modal-body');
                if (offcanvasBody) {
                    offcanvasBody.innerHTML = '<div class="text-center text-muted mt-5 py-4"><i class="far fa-heart fa-3x mb-3 opacity-50"></i><h5>Your wishlist is empty.</h5></div>';
                }
            }
        }
    }

    // Listen for all clicks inside the Wishlist Offcanvas
    wishlistOffcanvasEl.addEventListener('click', function(e) {
        
        // --- 1. REMOVE ITEM LOGIC ---
        var removeBtn = e.target.closest('.remove-wishlist-btn');
        if (removeBtn) {
            e.preventDefault();
            var entryId = removeBtn.getAttribute('data-entry-id');
            var rowElement = document.getElementById('wishlist-item-' + entryId);
            
            // Show loading state
            var originalText = removeBtn.innerHTML;
            removeBtn.disabled = true;
            removeBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>...';

            // Build the data payload for the Assassin script
            var formData = new FormData();
            formData.append('wishlist_action', 'delete_item');
            formData.append('entry_id', entryId);

            // Fire the background POST request
            fetch(window.wishlistModalUrl, {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(text => {
                if (text.includes('WISHLIST_DELETED')) {
                    // Success! Remove it from the screen
                    if (rowElement) rowElement.remove();
                    updateWishlistCount();
                    checkEmptyState();
                } else {
                    console.error('Server error:', text);
                    removeBtn.disabled = false;
                    removeBtn.innerHTML = originalText;
                }
            })
            .catch(err => {
                console.error('Network error:', err);
                removeBtn.disabled = false;
                removeBtn.innerHTML = originalText;
            });
        }

        // --- 2. MOVE TO CART LOGIC ---
        var moveBtn = e.target.closest('.move-to-cart-btn');
        if (moveBtn) {
            e.preventDefault();
            var entryId = moveBtn.getAttribute('data-entry-id');
            var productLink = moveBtn.getAttribute('data-product-link');
            var variantStr = moveBtn.getAttribute('data-variants');
            var rowElement = document.getElementById('wishlist-item-' + entryId);
            
            var originalText = moveBtn.innerHTML;
            moveBtn.disabled = true;
            moveBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Moving...';

            // 1. Fetch the product page to scrape the cart form
            fetch(productLink)
            .then(res => res.text())
            .then(html => {
                var parser = new DOMParser();
                var doc = parser.parseFromString(html, 'text/html');
                var cartForm = doc.querySelector('#add-to-cart-form') || doc.querySelector('form.cart-form'); 
                if(!cartForm) throw new Error("Could not find the cart form on product page.");

                var cartData = new FormData(cartForm);
                
                // Inject the variants
                var parts = variantStr.split('|');
                parts.forEach(part => {
                    var kv = part.split(':');
                    if(kv.length === 2) {
                        var key = kv[0].trim();
                        var val = kv[1].trim();
                        if(cartData.has(key)) cartData.set(key, val);
                        else cartData.append(key, val);
                    }
                });
                
                if(cartData.has('qty')) cartData.set('qty', '1');
                else cartData.append('qty', '1');

                var submitBtn = cartForm.querySelector('button[type="submit"], input[type="submit"]');
                if(submitBtn && submitBtn.name) cartData.append(submitBtn.name, submitBtn.value || '1');
                else cartData.append('submit', '1'); 

                // POST to the actual Cart URL
                return fetch(cartForm.getAttribute('action'), {
                    method: 'POST',
                    body: cartData
                });
            })
            .then(() => {
                // 2. Added to cart! Now tell the assassin to delete the wishlist entry
                var delData = new FormData();
                delData.append('wishlist_action', 'delete_item');
                delData.append('entry_id', entryId);

                return fetch(window.wishlistModalUrl, {
                    method: 'POST',
                    body: delData
                });
            })
            .then(() => {
                // 3. Update the Wishlist UI
                if (rowElement) rowElement.remove();
                updateWishlistCount();
                checkEmptyState();
                
                // 4. Update the Cart UI
                var cartBody = document.getElementById('cart-modal-body');
                if(cartBody && window.cartModalUrl) {
                    fetch(window.cartModalUrl)
                    .then(res => res.text())
                    .then(html => {
                        cartBody.innerHTML = html;
                        var newCountEl = cartBody.querySelector('#ajax-cart-count');
                        var cartBadges = document.querySelectorAll('.cart-qty-badge');
                        if (newCountEl) {
                            cartBadges.forEach(b => b.textContent = newCountEl.textContent);
                        }
                    });
                }
            })
            .catch(err => {
                console.error("Move to Cart Error:", err);
                moveBtn.disabled = false;
                moveBtn.innerHTML = originalText;
            });
        }
    });
});