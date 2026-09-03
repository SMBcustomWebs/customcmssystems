/* ============================================================
   ccs_shop.js - COMMERCE RUNTIME
   ------------------------------------------------------------
   The cart offcanvas and the wishlist. Split out of ccs_js.js so
   it can be copied between sites built from this framework as one
   unit, and so a site that sells nothing never downloads it.

   LOADED BY snippets/pb_mods/pg_frame/tail.htm, gated on the
   globals checkbox ccs_gl_shop_on. It also self-exits when the
   markup is absent, so loading it on a page with no cart is
   harmless - the gate is about not shipping it at all.

   DEPENDS ON, set by tail.htm before this file runs:
     window.cartModalUrl       -> cart-modal.php
     window.wishlistModalUrl   -> wishlist-modal.php

   PUBLISHES, for inline scripts on item pages:
     window.bumpWishlistCount( delta )
     window.refreshWishlistModal( syncCount )

   BUMP ?v= IN tail.htm ON EVERY CHANGE HERE. The query string is
   the only cache-buster this file has, and a stale cached copy is
   indistinguishable from the fix not working.
   ============================================================ */

//cart modal

document.addEventListener('DOMContentLoaded', function() {
    // Initialize the Bootstrap Offcanvas
    var cartOffcanvasEl = document.getElementById('cartOffcanvas');
    if (!cartOffcanvasEl) return; // Exit if not on a page with the cart
    
    var bsOffcanvas = new bootstrap.Offcanvas(cartOffcanvasEl);
    var cartBody = document.getElementById('cart-modal-body');

    // Re-query on every use rather than capturing once at DOMContentLoaded -
    // a stale NodeList silently stops updating the badge.
    function setCartCount(n) {
        var badges = document.querySelectorAll('.cart-qty-badge');
        if (!badges.length) {
            console.warn('cart: no .cart-qty-badge element found to update');
            return;
        }
        Array.prototype.forEach.call(badges, function (b) { b.textContent = n; });
    }

    // Function: Fetch fresh cart data and update the sidebar
    function refreshCartModal() {
        cartBody.innerHTML = '<div class="d-flex justify-content-center align-items-center h-100 p-5"><div class="spinner-border text-secondary" role="status"></div></div>';
        
        // POST, not GET: cart-modal.php only serves POST requests.
        // A GET is redirected away and would fill the sidebar with the homepage.
        fetch(window.cartModalUrl, { method: 'POST' })
            .then(response => response.text())
            .then(html => {
                cartBody.innerHTML = html;

                // Update the quantity badge
                var newCountEl = cartBody.querySelector('#ajax-cart-count');
                if (newCountEl && newCountEl.textContent !== "") {
                    setCartCount(newCountEl.textContent.trim());
                } else {
                    console.warn('cart: #ajax-cart-count missing from the rendered fragment');
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
    // NOTE: this block used to bail when #wishlistOffcanvas was absent, and
    // bound its click listener to that element. There are TWO wishlist views:
    //   snippets/e_modals/wishlist.html  -> inside #wishlistOffcanvas
    //   snippets/single/wishlist.html    -> the full "Your Saved Items" page,
    //                                       rendered in the page body
    // An offcanvas-scoped listener never sees the full-page buttons, so Remove
    // and Move to Cart were dead there. Listening on document covers both.
    //
    // The offcanvas element is still looked up - but only to hook its open
    // event for the refresh below. It is NOT used to scope the click handler,
    // and its absence must never short-circuit this block, because the
    // saved-items page has the buttons without having the panel.
    var wishlistOffcanvasEl = document.getElementById('wishlistOffcanvas');

    // Always re-query. A reference captured once at DOMContentLoaded goes
    // stale the moment any surrounding markup is re-rendered, and it is null
    // forever if the utility bar happened not to be present at that instant.
    function wishlistBadges() {
        return document.querySelectorAll('#wishlist-count-badge');
    }

    function setWishlistCount(n) {
        var badges = wishlistBadges();
        if (!badges.length) {
            console.warn('wishlist: no #wishlist-count-badge element found to update');
            return;
        }
        Array.prototype.forEach.call(badges, function (b) { b.textContent = n; });
    }

    // Function to safely subtract from the badge count
    function updateWishlistCount() {
        var badges = wishlistBadges();
        if (!badges.length) { return; }
        var currentCount = parseInt(badges[0].textContent) || 0;
        var newCount = currentCount - 1;
        if (newCount < 0) newCount = 0;
        setWishlistCount(newCount);
    }

    // Bump the badge immediately, without waiting for a server round trip.
    // The save path previously relied solely on refreshWishlistModal() to move
    // the number, which made it feel broken; remove/move always felt right
    // because they do their own local arithmetic. This restores the symmetry -
    // instant feedback, then the refresh reconciles against the real count.
    function bumpWishlistCount(delta) {
        var badges = wishlistBadges();
        if (!badges.length) { return; }
        var n = (parseInt(badges[0].textContent, 10) || 0) + delta;
        if (n < 0) { n = 0; }
        setWishlistCount(n);
    }
    window.bumpWishlistCount = bumpWishlistCount;

    // Re-render the wishlist panel from the server, the same way
    // refreshCartModal() does for the cart. Before this existed the panel
    // was server-rendered once per page load and never updated, so a save
    // bumped the badge but the list stayed stale until the user navigated.
    //
    // Also resyncs the badge from the server's real count (#ajax-wishlist-count)
    // instead of trusting the client-side +1/-1 arithmetic.
    //
    // syncCount:
    //   true  - trust the server's count and write it to the badge. Correct
    //           for panel-open and page-load, where nothing has just changed.
    //   false - refresh the LIST ONLY and leave the badge alone. Used right
    //           after a save/remove/move, because the count query can still
    //           report the pre-change value for a beat, which showed up as the
    //           badge flashing to the right number and then snapping back.
    //           After an action the client already knows the outcome, so local
    //           arithmetic wins and the server reconciles on the next open.
    window.refreshWishlistModal = function (syncCount) {
        if (syncCount === undefined) { syncCount = true; }
        var body = document.getElementById('wishlist-modal-body');
        if (!body || !window.wishlistModalUrl) { return Promise.resolve(); }

        var payload = new FormData();
        payload.append('wishlist_action', 'render');

        return fetch(window.wishlistModalUrl, { method: 'POST', body: payload })
            .then(function (r) { return r.text(); })
            .then(function (html) {
                body.innerHTML = html;

                // The fragment carries the server's real count. If it is
                // missing, say so loudly rather than silently leaving a stale
                // badge - a wrong number is worse than an obvious error.
                if (!syncCount) { return; }

                var countEl = body.querySelector('#ajax-wishlist-count');
                if (!countEl) {
                    console.warn('wishlist: #ajax-wishlist-count missing from the rendered fragment');
                    return;
                }
                setWishlistCount(countEl.textContent.trim());
            })
            .catch(function (err) { console.error('Error refreshing wishlist:', err); });
    };

    // Refresh when the panel is opened, so it is never showing stale data
    if (wishlistOffcanvasEl) {
        wishlistOffcanvasEl.addEventListener('show.bs.offcanvas', function () {
            window.refreshWishlistModal();
        });
    }

    // Both views emit id="wishlist-item-N", so on the wishlist page that id
    // exists TWICE. getElementById returns only the first match, which left a
    // ghost row in the other view. Match every element carrying the id.
    function removeWishlistRows(entryId) {
        var rows = document.querySelectorAll('[id="wishlist-item-' + entryId + '"]');
        Array.prototype.forEach.call(rows, function (el) { el.remove(); });
    }

    // Function to check if list is empty and show the empty message
    function checkEmptyState() {
        if (document.querySelectorAll('.wishlist-item-row').length > 0) { return; }

        var emptyMsgs = document.querySelectorAll('.empty-wishlist-msg');
        if (emptyMsgs.length) {
            Array.prototype.forEach.call(emptyMsgs, function (m) { m.style.display = 'block'; });
            return;
        }
        // Fallback when .empty-wishlist-msg was never rendered - cms:no_results
        // only fires at page load, so removing the LAST item leaves no message
        // to reveal. Target the scroll area, NOT the whole panel body, or the
        // "View Wishlist Page" footer button gets wiped with it.
        var emptyTarget = document.getElementById('wishlist-scroll-area')
                       || document.getElementById('wishlist-modal-body');
        if (emptyTarget) {
            emptyTarget.innerHTML = '<div class="text-center text-muted mt-5 py-4"><i class="far fa-heart fa-3x mb-3 opacity-50"></i><h5>Your wishlist is empty.</h5></div>';
        }
    }

    // Listen on document so BOTH the offcanvas and the full-page view work
    document.addEventListener('click', function(e) {
        
        // --- 1. REMOVE ITEM LOGIC ---
        var removeBtn = e.target.closest('.remove-wishlist-btn');
        if (removeBtn) {
            e.preventDefault();
            var entryId = removeBtn.getAttribute('data-entry-id');

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
                    // Success! Remove it from every view it appears in
                    removeWishlistRows(entryId);
                    updateWishlistCount();
                    checkEmptyState();
                    // List only. updateWishlistCount() already moved the badge;
                    // letting the server overwrite it here caused the flash-and-
                    // revert, because the count can lag the change by a beat.
                    window.refreshWishlistModal(false);
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
                // 3. Update the Wishlist UI (every view the row appears in)
                removeWishlistRows(entryId);
                updateWishlistCount();
                checkEmptyState();
                window.refreshWishlistModal(false);
                
                // 4. Update the Cart UI
                var cartBody = document.getElementById('cart-modal-body');
                if(cartBody && window.cartModalUrl) {
                    // POST for the same reason as refreshCartModal() above.
                    fetch(window.cartModalUrl, { method: 'POST' })
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