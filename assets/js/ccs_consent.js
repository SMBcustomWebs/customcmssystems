/* ===========================================================================
   assets/js/ccs_consent.js

   Every consent BEHAVIOUR on the site. The markup lives in
   snippets/pb_mods/pg_frame/consent_banner.htm and
   snippets/pb_mods/pg_frame/footer/legal_links.htm; neither contains script,
   and this file contains no wording that an owner should be able to edit.

   THIS FILE NO LONGER WRITES COOKIES. It used to, with document.cookie, and
   that was the bug: Safari's Intelligent Tracking Prevention caps
   script-written cookies at seven days regardless of the lifetime requested,
   so an accepted answer expired weekly instead of six-monthly. While the write
   was client-side, how long consent lasted was decided by the visitor's
   browser rather than by this site.

   The answer is now POSTed and utils/consent.htm records it server-side with
   cms:set_cookie, which is a real Set-Cookie header and is not capped. The
   policy version is stamped by the server at the same moment, so the client
   never needs to know it - which retires an entire class of version-mismatch
   bug rather than guarding against it.

   THE BANNER IS A REAL FORM AND WORKS WITHOUT THIS FILE. Submit buttons carry
   ccs_cnst_do; with JavaScript off the form posts, the server answers, the page
   comes back correct. Everything below is enhancement: post in the background
   so the page does not visibly navigate, reveal the footer control, and offer
   per-video click-to-load.

   It does four things:
     1. runs the consent bar - accept, reject, per-category save
     2. reveals and wires the footer "Cookie Settings" control, so an answer
        can be changed or withdrawn as easily as it was given
     3. fills in third-party iframes that were deliberately shipped without a
        src, but only for a visitor who allowed embedded media
     4. offers a per-video click-to-load for a visitor who did not

   LOADED WITH defer. It needs the banner markup, which sits at the very end of
   tail.htm, and defer guarantees the document is parsed first. It also
   guarantees this runs BEFORE DOMContentLoaded, and therefore before theme.js's
   bgPlayerInit, which reads the same window.ccsConsent flag.

   THE SERVER REMAINS THE GATE FOR ANYTHING THAT EXECUTES. Script slots are
   printed or not printed in PHP (head.htm, tail.htm); a tag that is absent
   cannot fire. This file only ever fills in an iframe src, which is inert
   until it is set. Do not move script-slot gating into this file: JavaScript
   deciding after the fact is exactly the pattern that does not comply.
   =========================================================================== */
(function () {
    'use strict';

    var C = window.ccsConsent || {};
    var banner = document.querySelector('[data-ccs-consent-banner]');

    /* Post the answer to the current URL. utils/consent.htm picks it up, writes
       the four cookies, and stamps the current policy version.

       location.replace rather than reload: reload on a page that was itself
       reached by POST would re-submit that original form. replace always
       navigates by GET. */
    function postConsent(fields) {
        var fd = new FormData();
        var k;
        for (k in fields) {
            if (Object.prototype.hasOwnProperty.call(fields, k)) { fd.set(k, fields[k]); }
        }
        return fetch(window.location.href, {
            method: 'POST',
            body: fd,
            credentials: 'same-origin'
        });
    }

    function refresh() {
        window.location.replace(window.location.href);
    }

    /* ----------------------------------------------------------------------
       1 + 2. THE BAR, AND REOPENING IT
       ---------------------------------------------------------------------- */

    /* reason='video' means the visitor clicked a blocked play button.

       WHY THAT NEEDS SPECIAL HANDLING. On a first visit the bar is ALREADY on
       screen, so simply un-hiding it changes nothing a person can see - the
       click appeared to do nothing at all. It cannot be fixed by scrolling
       either: the bar is position:fixed, so it is always in view.

       The answer is to respond to what was actually asked. They want to watch
       a video, so open the category panel and put focus on the Embedded media
       checkbox - the one control that governs the thing they just clicked. The
       focus ring is the visible acknowledgement, and it lands somewhere useful
       rather than on "Reject all", which would be a strange reply to "play
       this".

       Reopening from the footer passes no reason and keeps the old behaviour:
       first button, no panel, no assumptions about what they came to change. */
    function openBanner(reason) {
        if (!banner) { return; }
        banner.hidden = false;

        var target = null;

        if (reason === 'video') {
            var panel = document.getElementById('ccs-consent-prefs');
            if (panel && panel.hidden) {
                panel.hidden = false;
                var toggle = banner.querySelector('[data-ccs-consent="prefs"]');
                if (toggle) { toggle.setAttribute('aria-expanded', 'true'); }
            }
            target = document.getElementById('ccs-cat-e');
        }

        if (!target) { target = banner.querySelector('button'); }
        if (!target) { return; }

        try { target.focus({ preventScroll: true }); } catch (err) { target.focus(); }

        /* FOCUS ALONE IS NOT VISIBLE HERE, AND THAT IS NOT A BUG IN THE CSS.

           The ring is on :focus-visible, which the browser applies by heuristic
           - broadly, keyboard interaction. A programmatic .focus() that follows
           a MOUSE click is attributed to the pointer, so :focus matches and
           :focus-visible does not, and nothing appears. Styling plain :focus
           instead would put a ring on every button anyone clicks, which is the
           problem :focus-visible exists to solve.

           So the acknowledgement is made explicit rather than inferred: mark
           the whole category cell, not just the checkbox, because a 24px box
           is a small thing to notice in a four-column row. It clears the moment
           they touch it or look elsewhere, so it never becomes decoration. */
        if (reason !== 'video') { return; }
        var cell = target.closest ? target.closest('.ccs-c__cat') : null;
        if (!cell) { return; }

        /* Three 0.5s pulses in CSS, then the class comes off, so the panel is
           left looking ordinary. Removing it on blur/change instead would mean
           the marker sat there indefinitely for anyone who did not touch that
           particular checkbox - and a permanent box around one cell of four
           reads as a state rather than as a pointer. */
        cell.classList.add('ccs-c__cat--target');
        window.setTimeout(function () {
            cell.classList.remove('ccs-c__cat--target');
        }, 1600);
    }

    function closeBanner() {
        if (banner) { banner.hidden = true; }
    }

    if (banner) {
        var form = banner.querySelector('[data-ccs-consent-form]');

        /* The preferences panel is a pure display toggle and its button is
           type="button", so it never submits. Handled separately from the
           answer buttons for exactly that reason. */
        banner.addEventListener('click', function (e) {
            var btn = e.target.closest('[data-ccs-consent="prefs"]');
            if (!btn) { return; }
            var panel = document.getElementById('ccs-consent-prefs');
            if (!panel) { return; }
            /* .hidden rather than a utility class: visibility is a property of
               the element, not of a stylesheet the theme could redefine. */
            panel.hidden = !panel.hidden;
            btn.setAttribute('aria-expanded', panel.hidden ? 'false' : 'true');
        });

        if (form) {
            /* e.submitter is well supported, but a click record is kept as a
               fallback. If neither can identify the button the answer defaults
               to reject - the safe direction, never accept. */
            var lastAction = null;
            form.addEventListener('click', function (e) {
                var b = e.target.closest('button[type="submit"]');
                if (b) { lastAction = b.value; }
            });

            form.addEventListener('submit', function (e) {
                e.preventDefault();
                var fd = new FormData(form);
                fd.set('ccs_cnst_do', (e.submitter && e.submitter.value) || lastAction || 'reject');
                postConsent(collect(fd)).then(refresh)['catch'](function () {
                    /* Network failure: fall back to a real navigation submit.
                       form.submit() does not fire this listener again. */
                    form.submit();
                });
            });
        }

        /* Escape closes the bar ONLY when it was reopened from the footer. On a
           first visit there is no stored answer, so dismissing would have to be
           recorded as something - and silence is never consent. With an answer
           already stored, cancelling genuinely changes nothing. */
        banner.addEventListener('keydown', function (e) {
            if (e.key === 'Escape' && banner.getAttribute('data-ccs-answered') === '1') {
                closeBanner();
            }
        });

        /* The footer control ships hidden and is revealed here, so it never
           appears without the script that drives it - and never at all if the
           owner switched the bar off, because then there is no banner to open
           and this whole block is skipped. */
        var openers = document.querySelectorAll('[data-ccs-consent-open]');
        var seps = document.querySelectorAll('[data-ccs-consent-sep]');
        var i;
        for (i = 0; i < openers.length; i++) {
            openers[i].hidden = false;
            /* Wrapped, not passed directly: addEventListener would hand the
               click Event in as `reason`, which is not 'video' but is also not
               the `undefined` the signature expects. */
            openers[i].addEventListener('click', function () { openBanner(); });
        }
        for (i = 0; i < seps.length; i++) { seps[i].hidden = false; }

        if (banner.hidden === false) {
            var firstBtn = banner.querySelector('button');
            if (firstBtn) {
                try { firstBtn.focus({ preventScroll: true }); } catch (err2) { firstBtn.focus(); }
            }
        }
    }

    /* FormData -> plain object, so postConsent has one input shape whether it
       is called from the form or from a video placeholder. */
    function collect(fd) {
        var out = {};
        fd.forEach(function (v, k) { out[k] = v; });
        return out;
    }

    /* ----------------------------------------------------------------------
       3 + 4. THIRD-PARTY IFRAMES

       Blocks ship as <iframe data-src="..." data-consent="embed"> with no src.
       An iframe with no src makes no request, so nothing has been disclosed by
       the time this runs. The markup is identical for every visitor, which is
       what lets a PageBuilder tile carrying a video stay cacheable - see
       CLAUDE.md sec.18.
       ---------------------------------------------------------------------- */

    function providerOf(url) {
        if (/youtube(-nocookie)?\.com|youtu\.be/i.test(url)) { return 'YouTube'; }
        if (/vimeo\.com/i.test(url)) { return 'Vimeo'; }
        try { return new URL(url, window.location.href).hostname.replace(/^www\./, ''); }
        catch (err) { return 'another company'; }
    }

    function allowed(cat) {
        if (cat === 'analytics') { return !!C.analytics; }
        if (cat === 'marketing') { return !!C.marketing; }
        return !!C.embed;
    }

    function reveal(frame) {
        var src = frame.getAttribute('data-src');
        if (!src) { return; }
        frame.setAttribute('src', src);
        frame.removeAttribute('data-src');
        frame.hidden = false;
    }

    function revealAll() {
        var others = document.querySelectorAll('iframe[data-src]');
        var j;
        for (j = 0; j < others.length; j++) { reveal(others[j]); }
        var boxes = document.querySelectorAll('.ccs-consent-blocked');
        for (j = 0; j < boxes.length; j++) {
            if (boxes[j].parentNode) { boxes[j].parentNode.removeChild(boxes[j]); }
        }
    }

    /* The placeholder is built here rather than shipped in the markup so that a
       visitor who allowed video never receives it at all, and so that blocks
       built later need nothing but the data-src attribute. */
    function block(frame) {
        var provider = providerOf(frame.getAttribute('data-src') || '');
        var box = document.createElement('div');

        /* Own class only - no Bootstrap utilities and no inherited frame
           classes. Everything visual lives in assets/css/ccs_consent.css, for
           the same reason the bar does: this text names a third party and asks
           a question with legal weight, so it has to stay legible wherever it
           lands. */
        box.className = 'ccs-consent-blocked';

        var p = document.createElement('p');
        p.className = 'ccs-c__blocked-text';
        p.textContent = 'This video is hosted by ' + provider +
            '. Loading it will contact ' + provider + ', which sets its own cookies.';

        var btn = document.createElement('button');
        btn.type = 'button';
        btn.className = 'ccs-c__btn';
        btn.textContent = 'Load video';

        var lbl = document.createElement('label');
        lbl.className = 'ccs-c__remember';
        var cb = document.createElement('input');
        cb.type = 'checkbox';
        lbl.appendChild(cb);
        lbl.appendChild(document.createTextNode('Remember this and allow videos from now on'));

        btn.addEventListener('click', function () {
            /* Ticking the box is the ONLY thing that records anything, and the
               label says exactly what it does. A bare "Play" that silently
               flipped a sitewide flag would be the dark pattern this design
               exists to avoid. Unticked, the click loads this one video and
               nothing is stored anywhere.

               ccs_cnst_do='embed' tells the server to grant embedded media and
               leave analytics and marketing exactly as they already are - the
               visitor asked to watch a video, not to be advertised to. */
            if (cb.checked) {
                btn.disabled = true;
                postConsent({ ccs_cnst_do: 'embed' })
                    .then(function () { C.embed = true; revealAll(); })
                    ['catch'](function () {
                        /* Could not record it. Still honour the click for this
                           one video rather than punishing a network blip. */
                        btn.disabled = false;
                        reveal(frame);
                        if (box.parentNode) { box.parentNode.removeChild(box); }
                    });
                return;
            }
            reveal(frame);
            if (box.parentNode) { box.parentNode.removeChild(box); }
        });

        box.appendChild(p);
        box.appendChild(btn);
        box.appendChild(lbl);

        frame.hidden = true;
        if (frame.parentNode) { frame.parentNode.insertBefore(box, frame); }
    }

    /* ----------------------------------------------------------------------
       5. BIGPICTURE VIDEO TRIGGERS

       theme.js's lightboxInit hands any [data-bigpicture] element to
       BigPicture, which builds a YouTube or Vimeo player IN JAVASCRIPT when it
       is clicked. Nothing is in the markup to find, which is exactly why an
       audit that grepped for "iframe src=" missed this path and it shipped
       ungated - a visitor could press a play button and reach Google without
       ever being asked.

       So the two third-party sources ship as data-ccs-bp instead. BigPicture
       queries [data-bigpicture] and never sees them; this promotes the
       attribute only once embedded media is allowed. A local upload keeps its
       real data-bigPicture in the markup and is untouched here - it contacts
       nobody and needs no permission.

       ORDER MATTERS AND defer GUARANTEES IT. lightboxInit runs on docReady;
       this file is deferred, so it finishes before DOMContentLoaded and the
       attribute is already in place by the time BigPicture looks.

       WITHOUT CONSENT THE CLICK OPENS THE BAR rather than doing nothing. The
       visitor has just told us they want to watch a video, and the bar is where
       that permission is granted or withdrawn - so it is the honest answer to
       the click, and it doubles as the route back for someone who rejected
       earlier and changed their mind.
       ---------------------------------------------------------------------- */

    var bps = document.querySelectorAll('[data-ccs-bp]');
    for (var q = 0; q < bps.length; q++) {
        (function (el) {
            if (allowed(el.getAttribute('data-consent') || 'embed')) {
                el.setAttribute('data-bigPicture', el.getAttribute('data-ccs-bp'));
                el.removeAttribute('data-ccs-bp');
                return;
            }
            el.addEventListener('click', function (e) {
                e.preventDefault();
                e.stopPropagation();
                openBanner('video');
            });
        })(bps[q]);
    }

    var frames = document.querySelectorAll('iframe[data-src]');
    for (var n = 0; n < frames.length; n++) {
        var cat = frames[n].getAttribute('data-consent') || 'embed';
        if (allowed(cat)) { reveal(frames[n]); } else { block(frames[n]); }
    }
})();
