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