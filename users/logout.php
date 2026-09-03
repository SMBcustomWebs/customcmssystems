<?php require_once( '../ccs_dash/cms.php' ); ?>
<cms:template title='Logout Processor' hidden='1'/>

<cms:ignore>
    ============================================================
    LOGOUT
    ------------------------------------------------------------
    Emptying the cart on logout is load-bearing, not tidiness. The
    cart lives in $_SESSION['kcart'] (addons/cart/cart.php, last
    line of serialize()), and AUTH->logout() only calls
    delete_cookie() - it never touches the session. Without
    pp_empty_cart the previous user's cart survives logout and the
    next person on that machine inherits it.

    Which is why a full cart gets an interstitial first. An empty
    cart behaves as this page always did: log out, no interruption.

    Handing off must use logout_link, not login_link.
    login_link_handler (addons/extended/extended-users.php:703)
    accepts only 'redirect' - action='logout' was silently ignored
    and returned the plain login URL, so the visitor arrived still
    authenticated. get_logout_link() (functions.php:1783) builds
    login.php?act=logout&nonce=...&redirect=... and the nonce
    matters: AUTH->logout() calls validate_nonce() before deleting
    the cookie.

    The cms:template tag stays SELF-CLOSING. Its body is a schema
    block whose output is discarded - putting page markup inside
    one is what broke this file once before.
    ============================================================
</cms:ignore>

<cms:no_cache />

<cms:if k_logged_out>
    <cms:redirect url="<cms:show k_site_link />" />
</cms:if>

<cms:set lo_action = "<cms:gpc 'lo_action' method='post' />" />

<cms:ignore>
    Saving to the wishlist deliberately does NOT log the visitor out in
    the same request. They stay signed in for one more screen so they can
    read what was saved and what was not - a line whose variants can no
    longer be rebuilt is reported while they can still do something about
    it, rather than disappearing with the session.
</cms:ignore>
<cms:set lo_saved = '0' />
<cms:if lo_action eq 'wishlist'>
    <cms:embed 'utils/cart_to_wishlist.htm' />
    <cms:set lo_saved = '1' />
</cms:if>

<cms:set lo_count = "<cms:pp_count_items />" />

<cms:ignore>
    Two ways to reach the actual logout: an empty cart, or the visitor
    pressing a log-out button. Both land here.

    Destination is built into a variable first. Inline, the url attribute
    would nest a double quote inside a double-quoted attribute
    (url="...redirect="...") and the inner quote closes the outer one.
</cms:ignore>
<cms:if lo_count lt '1' || lo_action eq 'logout'>
    <cms:pp_empty_cart />
    <cms:set lo_dest = "<cms:show k_site_link />" />
    <cms:redirect url="<cms:logout_link redirect=lo_dest />" />
</cms:if>

<cms:embed 'pb_mods/pg_frame/head.htm' />
<cms:set my_redirect_link = k_page_link />
<cms:embed 'pb_mods/pg_frame/main-cap.htm' />
<cms:embed 'pb_mods/pg_frame/nav/nav_emb.htm' />
<cms:embed 'utils/store_theme.htm' />

<section class="pt-0">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">

                <h1 class="pt-6 pb-3">Before you go</h1>

                <cms:if lo_saved>

                    <cms:ignore>
                        Post-save state. The cart is still full and the visitor is
                        still signed in; all that has changed is that the wishlist
                        now holds the same items.
                    </cms:ignore>
                    <div class="lo-note lo-note-ok p-4 mb-4">
                        <h4 class="fw-bold mb-2">
                            <i class="fas fa-check-circle me-2" aria-hidden="true"></i>Saved to your wishlist
                        </h4>
                        <p class="mb-0">
                            <cms:if c2w_saved gt '0'>
                                <strong><cms:show c2w_saved /></strong>
                                <cms:if c2w_saved eq '1'>item was<cms:else />items were</cms:if>
                                added to your wishlist.
                            </cms:if>
                            <cms:if c2w_dupe gt '0'>
                                <strong><cms:show c2w_dupe /></strong>
                                <cms:if c2w_dupe eq '1'>item was<cms:else />items were</cms:if>
                                already saved.
                            </cms:if>
                            <cms:if c2w_saved eq '0' && c2w_dupe eq '0' && c2w_skipped eq '0'>
                                Nothing needed saving.
                            </cms:if>
                        </p>
                    </div>

                    <cms:if c2w_skipped gt '0'>
                        <div class="lo-note lo-note-warn p-4 mb-4">
                            <p class="mb-0">
                                <strong><cms:show c2w_skipped /></strong>
                                <cms:if c2w_skipped eq '1'>item<cms:else />items</cms:if>
                                could not be saved, because the choices made in the cart are
                                no longer offered on the product page. Check out now to keep
                                <cms:if c2w_skipped eq '1'>it<cms:else />them</cms:if>.
                            </p>
                        </div>
                    </cms:if>

                    <p class="<cms:show ccs_st_secondary />" style="max-width:64ch">
                        Your wishlist keeps the item and its options, but not quantities.
                        Set the quantity again when you move it back into your cart.
                    </p>

                    <cms:ignore>
                        MOVE THE BADGE.

                        Every other place that changes the wishlist moves the badge
                        from the browser and lets the server reconcile when the panel
                        is next opened - product.html and service.html on save,
                        ccs_js.js on remove and move-to-cart. This page was the only
                        one relying on the utility bar's own count query picking up
                        rows written earlier in the same request, and it does not:
                        the number stays put until the next page load. The cause of
                        that is NOT established - the tile is section_caching='never'
                        and rendered with no_cache='1', so it is not the PageBuilder
                        cache - but the badge does not need to depend on it.

                        c2w_saved is the exact number of rows written, so this is a
                        count, not a guess. Duplicates are excluded because they did
                        not change the total.

                        ccs_js.js loads at the end of tail.htm, after this markup, so
                        the call waits for DOMContentLoaded. bumpWishlistCount and
                        refreshWishlistModal are both defined on window there.
                    </cms:ignore>
                    <cms:if c2w_saved gt '0'>
                        <script>
                        document.addEventListener('DOMContentLoaded', function () {
                            if (typeof window.bumpWishlistCount === 'function') {
                                window.bumpWishlistCount(<cms:show c2w_saved />);
                            }
                            // List only - the badge above is already correct, and
                            // letting the count query write it here would reintroduce
                            // exactly the stale number this is working around.
                            if (typeof window.refreshWishlistModal === 'function') {
                                window.refreshWishlistModal(false);
                            }
                        });
                        </script>
                    </cms:if>

                    <div class="d-flex flex-wrap gap-3 align-items-center mt-4">
                        <cms:ignore>
                            Goes to the CART, not straight to checkout. Someone who
                            reached this page was on their way out - dropping them
                            onto a payment form is a step further than they asked
                            for. The cart is where they can see what they have and
                            decide. Wording stays "Check out" because that is the
                            intent being offered, not the next URL.
                        </cms:ignore>
                        <a href="<cms:link 'cart.php' />" class="btn btn-primary">
                            <i class="fas fa-lock me-1" aria-hidden="true"></i> Check out instead
                        </a>

                        <a href="<cms:link 'saved-items.php' />" class="btn btn-outline-primary">
                            See my saved items
                        </a>

                        <form method="post" class="m-0">
                            <input type="hidden" name="lo_action" value="logout">
                            <button type="submit" class="btn btn-link lo-danger text-decoration-none px-0">
                                Log out now
                            </button>
                        </form>
                    </div>

                <cms:else />

                    <div class="lo-note lo-note-warn p-4">
                        <h4 class="fw-bold mb-2">
                            <i class="fas fa-exclamation-circle me-2" aria-hidden="true"></i>Your cart will be emptied
                        </h4>
                        <p class="mb-0">
                            You have <strong><cms:show lo_count /></strong>
                            <cms:if lo_count eq '1'>item<cms:else />items</cms:if> in your cart.
                            Logging out clears it, and it cannot be recovered afterwards.
                        </p>
                    </div>

                    <cms:ignore>
                        The cart is shown, not just counted. "You have 3 items" is easy to
                        dismiss; seeing what they are is what makes someone stop.
                    </cms:ignore>
                    <div class="table-responsive mb-4 mt-4">
                        <table class="table align-middle">
                            <thead>
                                <tr>
                                    <th>Item</th>
                                    <th class="text-center" style="width:70px">Qty</th>
                                    <th class="text-end" style="width:120px">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <cms:pp_cart_items>
                                    <tr>
                                        <td>
                                            <cms:show title />
                                            <cms:set lo_opts = "<cms:pp_selected_options />" />
                                            <cms:if lo_opts>
                                                <br><span class="small <cms:show ccs_st_secondary />"><cms:show lo_opts /></span>
                                            </cms:if>
                                        </td>
                                        <td class="text-center"><cms:show quantity /></td>
                                        <td class="text-end">$<cms:number_format line_total /></td>
                                    </tr>
                                </cms:pp_cart_items>
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex flex-wrap gap-3 align-items-center">
                        <cms:ignore>
                            Goes to the CART, not straight to checkout - see the note
                            on the matching button above. "Back to my cart" used to
                            sit beside this one and now pointed at the same place, so
                            it has gone: two buttons, one destination, is a choice
                            that is not a choice.
                        </cms:ignore>
                        <a href="<cms:link 'cart.php' />" class="btn btn-primary">
                            <i class="fas fa-lock me-1" aria-hidden="true"></i> Check out first
                        </a>

                        <cms:ignore>
                            Offered only to a visitor with a real user id. The wishlist is
                            keyed on ccs_auth_uid, so a blank one would write rows nobody
                            can ever see - see snippets/utils/auth_uid.htm.
                        </cms:ignore>
                        <cms:if ccs_auth_uid>
                            <form method="post" class="m-0">
                                <input type="hidden" name="lo_action" value="wishlist">
                                <button type="submit" class="btn btn-outline-primary">
                                    <i class="fa fa-shopping-basket me-1" aria-hidden="true"></i> Save these to my wishlist
                                </button>
                            </form>
                        </cms:if>

                        <form method="post" class="m-0">
                            <input type="hidden" name="lo_action" value="logout">
                            <button type="submit" class="btn btn-link lo-danger text-decoration-none px-0">
                                Log out anyway and empty my cart
                            </button>
                        </form>
                    </div>

                    <p class="small <cms:show ccs_st_secondary /> mt-4" style="max-width:64ch">
                        Staying signed in keeps your cart. It is cleared at logout so that
                        nobody else using this computer inherits it.
                    </p>

                </cms:if>

            </div>
        </div>
    </div>
</section>

<cms:ignore>
    Fixed colour pairs, not alert-warning / alert-success / text-danger.
    Those read a theme variable for one half of the pair while the other
    half is a build-time literal, so a Site Colors change can leave the
    two too close to read. A warning about losing a cart is the last
    place that should be allowed to happen. Same rule as the status
    pills on my-orders.php.
</cms:ignore>
<style>
 .lo-note{border:1px solid;border-radius:4px}
 .lo-note-warn{background:#f6efe1;color:#5c3b09;border-color:#7a4f0c}
 .lo-note-ok{background:#e4f0ea;color:#144934;border-color:#1c6045}
 .lo-danger{color:#a8322a}
 .lo-danger:hover,.lo-danger:focus{color:#7d2620}
</style>

<cms:embed 'pb_mods/pg_frame/footer/ftr_emb.htm' />
<cms:embed 'pb_mods/pg_frame/tail.htm' />
<?php COUCH::invoke(); ?>
