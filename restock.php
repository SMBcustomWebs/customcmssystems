<?php require_once( 'ccs_dash/cms.php' ); ?>
<cms:template title='Orders &amp; Returns' hidden='0' executable='1' parent='_site_' icon='rotate-left' order='8500'>
    <cms:ignore>
        This template is non-clonable, so its sidebar entry opens THIS edit form
        rather than the page itself. The form has nothing worth editing, so the
        message below is written as a doorway - a plain link straight through to
        the real page. Message fields return their content raw and unparsed, so
        static HTML works here and cms tags would not.
    </cms:ignore>
    <cms:editable type='message' name='rs_msg' order='1'>
        <div style="background:#e4efe7;border:1px solid #1f6b4a;border-left:5px solid #1f6b4a;padding:18px 20px;border-radius:5px;font-family:sans-serif">
            <h3 style="margin:0 0 6px;color:#1f6b4a">Orders &amp; Returns</h3>
            <p style="margin:0 0 14px;color:#42505a;font-size:14px">
                See what was purchased on any order, and put returned items back on the shelf.
                A refund never changes stock &mdash; that decision is yours, once the item is in front of you.
            </p>
            <p style="margin:0">
                <a href="/restock.php" style="display:inline-block;background:#1f6b4a;color:#fff;text-decoration:none;font-weight:600;padding:10px 18px;border-radius:4px;font-size:14px">Open Orders &amp; Returns &rarr;</a>
            </p>
        </div>
    </cms:editable>
</cms:template>

<cms:if k_user_access_level lt '7'><cms:redirect link=k_site_link /></cms:if>
<cms:no_cache />

<cms:ignore>
    ================================================================
    RESTOCK RETURNS

    Refunding money and restocking goods are separate events. A gateway
    can tell us about the first; only a person holding the item can
    decide the second. This is that decision, made explicit.

    IDEMPOTENT BY CONSTRUCTION: each line offers at most
    (item_qty - item_restocked_qty) units. Restock 1 of 3 and the line
    offers 2 next time. Restock all of them and the line offers nothing
    and cannot be submitted again - a reload or a double click adds
    nothing, because the ceiling is recomputed from the database on
    every render rather than trusted from the form.
    ================================================================
</cms:ignore>

<cms:set rs_oid = "<cms:gpc 'o' method='get' />" />
<cms:set rs_done = '' scope='global' />

<cms:ignore>
    ---------------- POST: resend the customer receipt -------------
    Sends order_email.htm and nothing else. That file holds no stock or
    status logic - all of it lives in order_fulfil.htm - so a resend
    cannot deduct inventory or alter the order however many times it is
    pressed. The shop notification is suppressed: the shop already knows.

    An address typed here is used for this send only. The order record
    keeps whatever the customer entered at checkout, so the receipt still
    tells the truth about where it was originally sent.
</cms:ignore>
<cms:set rs_resent = '' scope='global' />
<cms:set rs_resend_note = '' scope='global' />

<cms:if "<cms:gpc 'rs_resend' method='post' />">
    <cms:set rs_oid = "<cms:gpc 'rs_order' method='post' />" />
    <cms:set rs_alt = "<cms:gpc 'rs_alt_email' method='post' />" scope='global' />

    <cms:php>
        global $CTX;
        $alt = trim( (string)$CTX->get('rs_alt') );
        // an address is either usable or refused - never quietly ignored
        if( $alt !== '' && !filter_var($alt, FILTER_VALIDATE_EMAIL) ){
            $CTX->set( 'rs_alt', '', 'global' );
            $CTX->set( 'rs_bad_alt', $alt, 'global' );
        }
        else{
            $CTX->set( 'rs_alt', $alt, 'global' );
            $CTX->set( 'rs_bad_alt', '', 'global' );
        }
    </cms:php>

    <cms:if rs_bad_alt>
        <cms:set rs_resent = 'bad' scope='global' />
    <cms:else />
        <cms:set ccs_mail_override_to = rs_alt scope='global' />
        <cms:set ccs_mail_skip_shop   = '1'    scope='global' />
        <cms:set ccs_mail_order_id    = rs_oid scope='global' />
        <cms:embed 'utils/order_email.htm' />

        <cms:if ccs_mail_result = 'sent'>
            <cms:set rs_resent = 'ok' scope='global' />
        <cms:else />
            <cms:set rs_resent = 'noaddress' scope='global' />
        </cms:if>
    </cms:if>
</cms:if>

<cms:ignore>
    ---------------- POST: apply the ticked lines ------------------
    Each line is processed independently. A line whose box is unticked,
    whose quantity is zero, or which has already been fully restocked is
    skipped in silence.
</cms:ignore>
<cms:if "<cms:gpc 'rs_submit' method='post' />">
    <cms:set rs_oid = "<cms:gpc 'rs_order' method='post' />" />
    <cms:set rs_log = '' scope='global' />

    <cms:pages masterpage='order-items.php' custom_field="item_order_id==<cms:show rs_oid />" limit='500'>

        <cms:set rs_line_id  = k_page_id scope='global' />
        <cms:set rs_prod_id  = item_product_id scope='global' />
        <cms:set rs_title    = item_title scope='global' />
        <cms:set rs_ordered  = item_qty scope='global' />
        <cms:set rs_back     = item_restocked_qty scope='global' />

        <cms:set rs_ticked = "<cms:gpc "chk_<cms:show rs_line_id />" method='post' />" scope='global' />
        <cms:set rs_want   = "<cms:gpc "qty_<cms:show rs_line_id />" method='post' />" scope='global' />

        <cms:php>
            global $CTX;
            $ordered = (int) $CTX->get('rs_ordered');
            $back    = (int) $CTX->get('rs_back');
            $want    = (int) $CTX->get('rs_want');
            $ticked  = trim( (string) $CTX->get('rs_ticked') ) === '1';

            // The ceiling comes from the DB, never from the posted form.
            $allowed = max( 0, $ordered - $back );
            $apply   = ( $ticked && $want > 0 ) ? min( $want, $allowed ) : 0;

            $CTX->set( 'rs_apply', $apply, 'global' );
            $CTX->set( 'rs_new_back', $back + $apply, 'global' );
        </cms:php>

        <cms:if rs_apply gt '0'>

            <cms:ignore>
                Template read from the item's page id, not assumed. See
                snippets/utils/item_tpl.htm - a hardcoded product.php returns
                stock to nothing at all when the line is a service or any other
                sellable template, and reports success while doing it.
            </cms:ignore>
            <cms:set itm_tpl_id = rs_prod_id scope='global' />
            <cms:embed 'utils/item_tpl.htm' />
            <cms:set rs_item_tpl = itm_tpl scope='global' />

            <cms:pages masterpage=rs_item_tpl id=rs_prod_id limit='1'>
                <cms:if track_inventory == '1'>
                    <cms:set rs_new_stock = "<cms:add in_stock rs_apply />" scope='global' />
                    <cms:db_persist_ex
                        _masterpage=rs_item_tpl _mode='edit'
                        _page_id=k_page_id _invalidate_cache='0'
                        in_stock=rs_new_stock />
                    <cms:set rs_stock_note = "stock now <cms:show rs_new_stock />" scope='global' />
                <cms:else />
                    <cms:set rs_stock_note = "not tracked - nothing to add" scope='global' />
                </cms:if>
            </cms:pages>

            <cms:db_persist_ex
                _masterpage='order-items.php' _mode='edit'
                _page_id=rs_line_id _invalidate_cache='0'
                item_restocked_qty=rs_new_back />

            <cms:capture into='rs_log' scope='global'><cms:show rs_log /><li><strong><cms:show rs_apply /> &times;</strong> <cms:show rs_title /> &mdash; <cms:show rs_stock_note /></li></cms:capture>
        </cms:if>

    </cms:pages>

    <cms:set rs_done = '1' scope='global' />
</cms:if>

<cms:embed 'pb_mods/pg_frame/head.htm' />
<cms:embed 'pb_mods/pg_frame/main-cap.htm' />
<cms:embed 'pb_mods/pg_frame/nav/nav_emb.htm' />
<cms:embed 'utils/store_theme.htm' />

<cms:ignore>
    Dressed in the SITE FRAME, matching the storefront and the other store pages.
    This replaced the Couch admin theme on 2026-08-30, at the user's request: the
    admin tools should look like the site whichever side of it you are standing on.

    Two costs were accepted knowingly. head.htm embeds the consent snippet, so the
    cookie bar can appear on this page, and nav_emb.htm walks the folder tree to
    build the shop menu. Both are wasted work on an admin screen. Neither is
    harmful, and a consistent look was judged worth more.

    Surface and secondary-text classes come from store_theme.htm - the same pair
    the cart, checkout and order pages use - so this page follows Store Appearance
    rather than inventing colours of its own.
</cms:ignore>

<style>
 /* Only the two things Bootstrap has no opinion about. */
 .rs-qty{width:86px}
 .rs-variants{font-size:12px;display:block;margin-top:2px}

 /* Status pill: a FIXED pair, deliberately. text-bg-danger and friends take
    their background from a theme variable the site owner can change, so no
    promise can be made about the text on top of it. A warning badge that can
    become unreadable is worse than no badge. */
 .rs-pill{display:inline-block;font-size:11px;font-weight:700;letter-spacing:.05em;
          text-transform:uppercase;padding:3px 9px;border-radius:3px;
          background:#f6e6e4;color:#a8322a;border:1px solid #a8322a}

 /* Same rule as the pill: fixed pairs, so a Site Colors change cannot make
    a money figure or a refund instruction unreadable. */
 .rs-money{background:#f7f8f9;border:1px solid #d9dde1;border-radius:4px;padding:14px 16px;color:#22252a}
 .rs-money .table{--bs-table-bg:transparent;color:#22252a}
 .rs-total td{border-top:2px solid #22252a}
 .rs-note{background:#e4efe7;border:1px solid #1f6b4a;border-left:4px solid #1f6b4a;
          border-radius:4px;color:#14402e}
</style>

<section class="pt-0">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-10">

<h1 class="pt-6 pb-3">Orders &amp; Returns</h1>
<p class="<cms:show ccs_st_secondary />" style="max-width:64ch">What was purchased on each order, and where returned items go back on the shelf.
A refund puts money back; it never puts stock back. That call is yours, once the item is in front of you.</p>

<cms:if rs_resent = 'ok'>
    <div class="alert alert-success p-4"><strong>Receipt sent</strong> to <code><cms:show om_to /></code>.</div>
<cms:else_if rs_resent = 'bad' />
    <div class="alert alert-warning p-4"><strong>Nothing was sent.</strong> <code><cms:show rs_bad_alt /></code> is not a valid email address.</div>
<cms:else_if rs_resent = 'noaddress' />
    <div class="alert alert-warning p-4"><strong>Nothing was sent.</strong> This order has no email address on it, and none was typed in.</div>
</cms:if>

<cms:if rs_done>
    <cms:if rs_log>
        <div class="alert alert-success p-4"><strong>Returned to stock</strong><ul><cms:show rs_log /></ul></div>
    <cms:else />
        <div class="alert alert-warning p-4"><strong>Nothing was changed.</strong> No line was ticked with a quantity above zero.</div>
    </cms:if>
</cms:if>

<cms:if rs_oid>
    <cms:pages masterpage='orders.php' id=rs_oid limit='1'>
        <cms:set rs_num = order_number scope='global' />
        <cms:set rs_status = order_status scope='global' />
        <cms:set rs_found = '1' scope='global' />
        <cms:set rs_order_email = order_email scope='global' />

        <cms:ignore>
            The money. This page used to show none of it, which made it
            impossible to tell how much to refund without opening the order
            record in a second tab.
        </cms:ignore>
        <cms:set rs_subtotal    = order_subtotal        scope='global' />
        <cms:set rs_shipping    = order_shipping        scope='global' />
        <cms:set rs_tax         = order_tax             scope='global' />
        <cms:set rs_total       = order_total           scope='global' />
        <cms:set rs_gateway     = order_gateway         scope='global' />
        <cms:set rs_txn         = order_txn_id          scope='global' />
        <cms:set rs_paid_on     = order_paid_on         scope='global' />
        <cms:set rs_refunded    = order_refunded_amount scope='global' />
        <cms:set rs_refunded_on = order_refunded_on     scope='global' />
    </cms:pages>

    <cms:ignore>
        Where the refund is actually issued. Nothing on this site can move
        money - the gateway owns that, and the site only records what the
        gateway reports back through stripe-webhook.php / paypal-ipn.php.
        The deep link is built from the stored transaction id so the operator
        does not have to search the dashboard for it.
    </cms:ignore>
    <cms:php>
        global $CTX;
        $gw   = strtolower( trim( (string)$CTX->get('rs_gateway') ) );
        $txn  = trim( (string)$CTX->get('rs_txn') );
        $test = ( defined('K_STRIPE_TEST_MODE') && K_STRIPE_TEST_MODE ) ? 1 : 0;

        $url = '';
        $lbl = '';
        if( $gw === 'stripe' ){
            $url = 'https://dashboard.stripe.com/' . ( $test ? 'test/' : '' ) . 'payments/' . rawurlencode( $txn );
            $lbl = 'Stripe Dashboard';
        }
        elseif( $gw === 'paypal' ){
            $url = $test
                 ? 'https://www.sandbox.paypal.com/activity/'
                 : 'https://www.paypal.com/activity/';
            $lbl = 'PayPal Activity';
        }
        if( $txn === '' && $gw === 'stripe' ){ $url = ''; }

        $CTX->set( 'rs_gw_url',   $url, 'global' );
        $CTX->set( 'rs_gw_label', $lbl, 'global' );
    </cms:php>

    <cms:if rs_found>
        <div class="alert alert-info p-4">
            <strong>Order <cms:show rs_num /></strong> &mdash; status <code><cms:show rs_status /></code>
        </div>

        <cms:ignore>
            MONEY, then GOODS, then RECEIPT - in that order down the page,
            because that is the order the operator needs them in. You cannot
            decide what to put back on the shelf until you know what was
            actually refunded.
        </cms:ignore>
        <h2 style="margin-top:32px">Money</h2>
        <div class="rs-money">
            <table class="table align-middle mb-0" style="max-width:420px">
                <tr><td>Sub total</td><td class="text-end">$<cms:number_format rs_subtotal /></td></tr>
                <tr><td>Shipping</td><td class="text-end">$<cms:number_format rs_shipping /></td></tr>
                <tr><td>Tax</td><td class="text-end">$<cms:number_format rs_tax /></td></tr>
                <tr class="rs-total"><td><strong>Order total</strong></td>
                    <td class="text-end"><strong>$<cms:number_format rs_total /></strong></td></tr>
                <tr>
                    <td>Refunded so far</td>
                    <td class="text-end">
                        <cms:if rs_refunded gt '0'>
                            <span class="rs-pill">$<cms:number_format rs_refunded /></span>
                        <cms:else />
                            <span class="<cms:show ccs_st_secondary />">nothing</span>
                        </cms:if>
                    </td>
                </tr>
            </table>

            <p class="<cms:show ccs_st_secondary />" style="font-size:13px;margin:10px 0 0">
                Paid <cms:if rs_paid_on><cms:show rs_paid_on /><cms:else />&mdash;</cms:if>
                via <cms:if rs_gateway><cms:show rs_gateway /><cms:else />&mdash;</cms:if><cms:if rs_txn>,
                transaction <code><cms:show rs_txn /></code></cms:if>.<cms:if rs_refunded_on>
                Refund recorded <cms:show rs_refunded_on />.</cms:if>
            </p>
        </div>

        <cms:ignore>
            Deliberately not a button that does something. This site cannot
            issue a refund and should not pretend to - the gateway holds the
            money and owns that action. What the site does is RECORD the
            refund, automatically, when the gateway reports it back
            (stripe-webhook.php on charge.refunded, paypal-ipn.php on a
            Refunded IPN). So the honest control here is a way out to the
            right screen, not a fake one.
        </cms:ignore>
        <div class="rs-note p-4 mt-3">
            <h4 class="fw-bold mb-2" style="font-size:16px">Refunds are issued at the gateway, not here</h4>
            <p class="mb-2" style="font-size:14px">
                Refund the payment in
                <cms:if rs_gw_label><cms:show rs_gw_label /><cms:else />the payment gateway</cms:if>.
                This page updates itself within seconds &mdash; the gateway notifies the site and the
                amount above and the order status are written automatically. Do not edit them by hand.
            </p>
            <cms:if rs_gw_url>
                <a href="<cms:show rs_gw_url />" target="_blank" rel="noopener" class="btn btn-primary btn-sm">
                    Open this payment in <cms:show rs_gw_label /> &nearr;
                </a>
            <cms:else />
                <p class="mb-0" style="font-size:13px">
                    No gateway or transaction id is stored on this order, so there is no payment to open.
                </p>
            </cms:if>
            <p class="mb-0 mt-2" style="font-size:13px">
                A refund never puts stock back. Once the goods are physically in front of you,
                return them below.
            </p>
        </div>

        <h2 style="margin-top:38px">Goods</h2>
        <form method="post">
            <input type="hidden" name="rs_submit" value="1">
            <input type="hidden" name="rs_order" value="<cms:show rs_oid />">
            <table class="table align-middle">
                <tr><th style="width:44px">Back</th><th>Item</th><th style="width:80px">Ordered</th>
                    <th class="text-end" style="width:90px">Unit</th>
                    <th class="text-end" style="width:100px">Line total</th>
                    <th style="width:90px">Already back</th><th style="width:110px">Return now</th></tr>
                <cms:pages masterpage='order-items.php' custom_field="item_order_id==<cms:show rs_oid />" limit='500'>
                    <cms:set rs_left = "<cms:sub item_qty item_restocked_qty />" />
                    <tr>
                        <td><cms:if rs_left gt '0'><input type="checkbox" name="chk_<cms:show k_page_id />" value="1"><cms:else />&mdash;</cms:if></td>
                        <td><cms:show item_title /><cms:if item_variants><br><span class="<cms:show ccs_st_secondary />" style="font-size:12px"><cms:show item_variants /></span></cms:if></td>
                        <td><cms:show item_qty /></td>
                        <td class="text-end">$<cms:number_format item_unit_price /></td>
                        <td class="text-end">$<cms:number_format item_line_total /></td>
                        <td><cms:show item_restocked_qty /></td>
                        <td>
                            <cms:if rs_left gt '0'>
                                <input type="number" class="rs-qty" name="qty_<cms:show k_page_id />" value="<cms:show rs_left />" min="1" max="<cms:show rs_left />">
                            <cms:else />
                                <span class="<cms:show ccs_st_secondary />">all back</span>
                            </cms:if>
                        </td>
                    </tr>
                </cms:pages>
            </table>
            <button type="submit" class="btn btn-primary mt-3">Return ticked items to stock</button>
        </form>

        <h2 style="margin-top:38px">Receipt</h2>
        <p class="<cms:show ccs_st_secondary />" style="max-width:64ch" style="margin-bottom:14px">
            Resends the customer's receipt for this order. It does not touch stock, does not
            change the order, and does not notify the shop again &mdash; press it as often as
            you need to.
        </p>
        <form method="post">
            <input type="hidden" name="rs_resend" value="1">
            <input type="hidden" name="rs_order" value="<cms:show rs_oid />">
            <p style="margin:0 0 6px">
                <cms:if rs_order_email>
                    Goes to <strong><cms:show rs_order_email /></strong> unless you give another address below.
                <cms:else />
                    <span class="rs-pill">No address on order</span>
                    This order has no email address stored, so you must supply one below.
                </cms:if>
            </p>
            <div style="display:flex;gap:10px;align-items:center;flex-wrap:wrap;margin-top:10px">
                <input type="email" name="rs_alt_email" style="min-width:280px"
                       placeholder="Different address for this send only (optional)">
                <button type="submit" class="btn btn-primary">Resend receipt</button>
            </div>
            <p class="<cms:show ccs_st_secondary />" style="font-size:12px;margin:8px 0 0">
                Typing an address here sends this one receipt there. The order record is not changed.
            </p>
        </form>
    <cms:else />
        <div class="alert alert-warning p-4"><strong>No order with id <cms:show rs_oid />.</strong></div>
    </cms:if>

<cms:else />
    <h2>Recent orders</h2>
    <p class="<cms:show ccs_st_secondary />" style="max-width:64ch">Open any order to see exactly what was purchased. Line items live in their own
    template so they cannot appear on the order form itself &mdash; this is where you read them.
    Refunded orders are the ones that usually need restocking, and are marked.</p>
    <table class="table align-middle">
        <tr><th>Order</th><th>Placed</th><th>Status</th><th>Total</th><th>Refunded</th><th></th></tr>
        <cms:pages masterpage='orders.php' limit='40' orderby='publish_date' order='desc'>
            <tr>
                <td><strong><cms:show order_number /></strong></td>
                <td class="<cms:show ccs_st_secondary />"><cms:date k_publish_date format='j M Y' /></td>
                <td>
                    <cms:if order_status == 'refunded' || order_status == 'refunded_part'>
                        <span class="rs-pill"><cms:show order_status /></span>
                    <cms:else />
                        <code><cms:show order_status /></code>
                    </cms:if>
                </td>
                <td>$<cms:show order_total /></td>
                <td><cms:if order_refunded_amount gt '0'>$<cms:show order_refunded_amount /><cms:else /><span class="<cms:show ccs_st_secondary />">&mdash;</span></cms:if></td>
                <td><a href="<cms:show k_site_link />restock.php?o=<cms:show k_page_id />">open</a></td>
            </tr>
        </cms:pages>
    </table>
</cms:if>

            </div>
        </div>
    </div>
</section>

<cms:embed 'pb_mods/pg_frame/footer/ftr_emb.htm' />
<cms:embed 'pb_mods/pg_frame/tail.htm' />
<?php COUCH::invoke(); ?>
