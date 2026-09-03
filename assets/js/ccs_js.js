/* ============================================================
   ccs_js.js - THEME RUNTIME
   ------------------------------------------------------------
   Presentation only: the hero swiper's play/pause control and
   custom video looping. Anything a brochure site needs.

   THE SHOP IS NOT IN HERE. Cart and wishlist live in
   assets/js/ccs_shop.js, loaded by tail.htm only when the
   "This site sells things" switch in globals is on.

   The split is not tidiness. This framework is forked per site:
     - a brochure fork should not download or parse a checkout;
     - a hero change should not move the shop's cache-buster,
       or the reverse;
     - and porting shop improvements between forks should be a
       file copy, not a hand-merge of a file where two unrelated
       concerns are interleaved. That merge is exactly how the
       wishlist empty-state fix ended up on one site and not the
       other.

   Keep this file free of commerce. If a thing knows what a cart
   is, it belongs in ccs_shop.js.
   ============================================================ */

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
