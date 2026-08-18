/* ============================================================
 * ccs_inline_a11y.js
 * ------------------------------------------------------------
 * Accessibility enhancement for the inline-edit popup controls.
 *
 * WHAT IT DOES
 *   Upgrades controls that Couch has ALREADY rendered:
 *     - a.k_inline is <a href="#"> but behaves as a button, so it
 *       is given role="button" and Space activation.
 *     - the TINY2 lightbox has no role, no focus handling and no
 *       Escape key, so this adds dialog semantics, moves focus in,
 *       keeps it in, closes on Escape and returns focus to the
 *       trigger afterwards.
 *
 * WHAT IT DELIBERATELY DOES NOT DO
 *   It never creates, reveals or enables any editing affordance.
 *   It adds no buttons, no links, no menus. If an edit control is
 *   not already in the DOM, this file has nothing to act on and
 *   exits immediately. It cannot expose editing to a user who was
 *   not already served the controls by Couch.
 *
 * VISIBILITY MODEL THIS MUST PRESERVE
 *   not logged in / below level 7 -> nothing at all
 *   admin, edit OFF              -> ONLY the "Turn Edit On" button
 *   admin, edit ON               -> toggle + edit buttons
 *
 *   Enforced server-side in three places, none of which this file
 *   touches:
 *     1. inline.php:53 returns early when access_level is below
 *        K_ACCESS_LEVEL_ADMIN or when k_disable_edit is set.
 *     2. head.htm:101 fires <cms:no_edit /> when an admin has edit
 *        off, which sets k_disable_edit.
 *     3. This file is only linked from the gated block at
 *        head.htm:108, so it is never sent to anyone else.
 *
 *   The "Turn Edit On/Off" toggle is a <cms:form> submit input,
 *   NOT an a.k_inline anchor, so it is never matched or modified
 *   here. Edit-off admins keep their one way back in.
 *
 * Loaded with defer from head.htm. Uses a MutationObserver, so it
 * does not care whether tinybox.js has run yet.
 * ============================================================ */

(function () {
	'use strict';

	var TRIGGERS = 'a.k_inline';
	var WRAP_SELECTORS = '.mfp2-wrap, .tbox';   /* TINY2 responsive / TINY legacy */
	var CLOSE_SELECTORS = '.mfp2-close, .tclose';
	var MASK_SELECTORS = '.mfp2-bg, .tmask';

	var lastTrigger = null;
	var activeWrap = null;
	var hidden = [];

	/* ---------------------------------------------------------- */
	/* helpers                                                     */
	/* ---------------------------------------------------------- */

	function isVisible(el) {
		if (!el) { return false; }
		var s = window.getComputedStyle(el);
		return s.display !== 'none' && s.visibility !== 'hidden';
	}

	function focusables(root) {
		return Array.prototype.filter.call(
			root.querySelectorAll('a[href], button, input, select, textarea, iframe, [tabindex]'),
			function (el) {
				return !el.hasAttribute('disabled') &&
					el.getAttribute('tabindex') !== '-1' &&
					isVisible(el);
			}
		);
	}

	/* ---------------------------------------------------------- */
	/* 1. trigger anchors -> real buttons                          */
	/* ---------------------------------------------------------- */

	function upgradeTriggers() {
		var list = document.querySelectorAll(TRIGGERS);
		Array.prototype.forEach.call(list, function (a) {
			if (a.getAttribute('data-ccs-a11y')) { return; }
			a.setAttribute('data-ccs-a11y', '1');

			/* popup_edit emits <a href="#" onclick="...">. It performs an
			   action rather than navigating, so announce it as a button.
			   Anchors respond to Enter but not Space; buttons respond to
			   both, so Space is wired up to match the announced role. */
			a.setAttribute('role', 'button');

			a.addEventListener('keydown', function (e) {
				if (e.key === ' ' || e.key === 'Spacebar' || e.keyCode === 32) {
					e.preventDefault();
					a.click();
				}
			});

			a.addEventListener('click', function () {
				lastTrigger = a;
			});
		});
		return list.length;
	}

	/* ---------------------------------------------------------- */
	/* 2. modal semantics + focus management                       */
	/* ---------------------------------------------------------- */

	function hideBackground(wrap) {
		hidden = [];
		Array.prototype.forEach.call(document.body.children, function (el) {
			if (el === wrap) { return; }
			if (el.matches && el.matches(MASK_SELECTORS)) { return; }
			if (el.contains && el.contains(wrap)) { return; }
			if (el.hasAttribute('aria-hidden')) { return; }
			el.setAttribute('aria-hidden', 'true');
			if ('inert' in el) { el.inert = true; }
			hidden.push(el);
		});
	}

	function showBackground() {
		hidden.forEach(function (el) {
			el.removeAttribute('aria-hidden');
			if ('inert' in el) { el.inert = false; }
		});
		hidden = [];
	}

	function openModal(wrap) {
		if (activeWrap === wrap) { return; }
		activeWrap = wrap;

		wrap.setAttribute('role', 'dialog');
		wrap.setAttribute('aria-modal', 'true');
		if (!wrap.getAttribute('aria-label')) {
			/* Name it from the control that opened it, so the dialog
			   announces "Edit pricing..." rather than nothing. */
			var name = lastTrigger ? (lastTrigger.textContent || '').trim() : '';
			wrap.setAttribute('aria-label', name || 'Content editor');
		}

		/* The close control is a bare <div> with an onclick: not
		   focusable, no name, no keyboard route. Give it all three. */
		var closer = wrap.querySelector(CLOSE_SELECTORS);
		if (closer && !closer.getAttribute('data-ccs-a11y')) {
			closer.setAttribute('data-ccs-a11y', '1');
			closer.setAttribute('role', 'button');
			closer.setAttribute('tabindex', '0');
			closer.setAttribute('aria-label', 'Close editor');
			closer.addEventListener('keydown', function (e) {
				if (e.key === 'Enter' || e.key === ' ' || e.keyCode === 13 || e.keyCode === 32) {
					e.preventDefault();
					closer.click();
				}
			});
		}

		hideBackground(wrap);

		/* Move focus in. Prefer the close control so there is always a
		   known, keyboard-reachable way out before entering the iframe. */
		window.setTimeout(function () {
			var target = closer || focusables(wrap)[0] || wrap;
			if (target === wrap && !wrap.hasAttribute('tabindex')) {
				wrap.setAttribute('tabindex', '-1');
			}
			try { target.focus(); } catch (err) { /* no-op */ }
		}, 50);
	}

	function closeModal() {
		if (!activeWrap) { return; }
		var wrap = activeWrap;
		activeWrap = null;

		wrap.removeAttribute('role');
		wrap.removeAttribute('aria-modal');
		showBackground();

		if (lastTrigger && document.contains(lastTrigger)) {
			try { lastTrigger.focus(); } catch (err) { /* no-op */ }
		}
	}

	/* Escape closes. TINY2 binds no key handler of its own -
	   it only clears one on hide, which it never set. */
	document.addEventListener('keydown', function (e) {
		if (!activeWrap) { return; }
		if (e.key === 'Escape' || e.key === 'Esc' || e.keyCode === 27) {
			var closer = activeWrap.querySelector(CLOSE_SELECTORS);
			if (closer) { closer.click(); }
		}
	});

	/* Keep focus inside. Focus events inside the same-origin iframe do
	   not bubble to this document, so the iframe tabs naturally on its
	   own; this only catches focus escaping back out to the page. */
	document.addEventListener('focusin', function (e) {
		if (!activeWrap) { return; }
		if (activeWrap.contains(e.target)) { return; }
		var closer = activeWrap.querySelector(CLOSE_SELECTORS);
		var target = closer || focusables(activeWrap)[0];
		if (target) {
			try { target.focus(); } catch (err) { /* no-op */ }
		}
	});

	/* ---------------------------------------------------------- */
	/* 3. observe                                                  */
	/* ---------------------------------------------------------- */

	function scanWraps() {
		var wraps = document.querySelectorAll(WRAP_SELECTORS);
		var open = null;
		Array.prototype.forEach.call(wraps, function (w) {
			if (isVisible(w)) { open = w; }
		});
		if (open) { openModal(open); }
		else { closeModal(); }
	}

	function start() {
		upgradeTriggers();

		/* Nothing to enhance means nothing to do. Belt and braces: this
		   file is already gated server-side, but if it is ever loaded
		   for a user with no edit controls it exits inert. */
		if (!document.querySelector(TRIGGERS)) { return; }

		if (!('MutationObserver' in window)) { return; }

		/* TINY2.box.hide() does NOT remove the wrapper - it fades it and
		   sets display:none - so attribute changes must be watched too,
		   or the close would never be detected. */
		new MutationObserver(function () {
			upgradeTriggers();
			scanWraps();
		}).observe(document.body, {
			childList: true,
			subtree: true,
			attributes: true,
			attributeFilter: ['style', 'class']
		});

		scanWraps();
	}

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', start);
	} else {
		start();
	}
}());
