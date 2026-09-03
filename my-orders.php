<?php require_once( 'ccs_dash/cms.php' ); ?>
<cms:template title="My Orders" parent='_site_' icon='receipt' order="80" />

<cms:ignore>
    ============================================================
    ORDER HISTORY - the customer's own orders.

    Deliberately NOT access_level='7'. This is a customer page, the
    same shape as saved-items.php: public template, gated in the body
    on being logged in. Gating the template would lock customers out
    of their own history.

    Filters on ccs_auth_uid, never k_user_id - see
    snippets/utils/auth_uid.htm. order_create.htm writes the same
    value into order_user_id, so the two agree.

    The && ccs_auth_uid on the guard is load-bearing. Without it an
    anonymous visitor makes the filter read "order_user_id==", which
    matches every guest order ever placed.

    Each row links to order-complete.php?t=<page_name>, the same
    token-addressed receipt the confirmation email points at. No new
    view of an order is built here - one receipt, one renderer.
    ============================================================
</cms:ignore>

<cms:no_cache />

<cms:embed 'pb_mods/pg_frame/head.htm' />
<cms:set my_redirect_link = k_page_link />
<cms:embed 'pb_mods/pg_frame/main-cap.htm' />
<cms:embed 'pb_mods/pg_frame/nav/nav_emb.htm' />
<cms:embed 'utils/store_theme.htm' />

<section class="pt-0">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-10">

                <cms:ignore>
                    Personalised heading, from the same source as the utility-bar
                    greeting so the two can never disagree about what to call
                    someone. utils/user_name.htm resolves Display Name -> account
                    title -> login name, and hands back a pre-escaped copy, so
                    nothing whitespace-sensitive is needed here.
                </cms:ignore>
                <cms:embed 'utils/user_name.htm' />
                <cms:set mo_who = ccs_user_display_esc scope='global' />

                <cms:if mo_who>
                    <h1 class="pt-6 pb-3">Orders for <cms:show mo_who /></h1>
                <cms:else />
                    <h1 class="pt-6 pb-3">Your orders</h1>
                </cms:if>

                <cms:if k_logged_in && ccs_auth_uid>

                    <cms:set mo_found = '' scope='global' />

                    <cms:capture into='mo_rows' scope='global'>
                        <cms:pages masterpage='orders.php' custom_field="order_user_id==<cms:show ccs_auth_uid />" orderby='publish_date' order='desc' limit='100'>
                            <cms:set mo_found = '1' scope='global' />
                            <tr>
                                <td class="fw-bold"><cms:show order_number /></td>
                                <td class="<cms:show ccs_st_secondary />"><cms:date k_publish_date format='j M Y' /></td>
                                <td>
                                    <cms:if order_status == 'paid'>
                                        <span class="mo-pill mo-pill-ok">Paid</span>
                                    <cms:else_if order_status == 'pending' />
                                        <span class="mo-pill mo-pill-wait">Pending</span>
                                    <cms:else_if order_status == 'refunded' || order_status == 'refunded_part' />
                                        <span class="mo-pill mo-pill-back">Refunded</span>
                                    <cms:else />
                                        <span class="mo-pill mo-pill-wait"><cms:show order_status /></span>
                                    </cms:if>
                                </td>
                                <td class="text-end">$<cms:number_format order_total /></td>
                                <td class="text-end">
                                    <a href="<cms:show k_site_link />order-complete.php?t=<cms:show k_page_name />" class="btn btn-sm btn-primary">View receipt</a>
                                </td>
                            </tr>
                        </cms:pages>
                    </cms:capture>

                    <cms:if mo_found>
                        <p class="<cms:show ccs_st_secondary />" style="max-width:64ch">
                            Every order placed while signed in to this account. Open any one to see
                            what was in it and what you paid.
                        </p>
                        <div class="table-responsive">
                            <table class="table align-middle">
                                <thead>
                                    <tr>
                                        <th>Order</th>
                                        <th>Placed</th>
                                        <th>Status</th>
                                        <th class="text-end">Total</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody><cms:show mo_rows /></tbody>
                            </table>
                        </div>
                        <p class="small <cms:show ccs_st_secondary /> mt-3">
                            Orders placed without signing in are not listed here. Quote the order
                            reference from your emailed receipt if you need help with one of those.
                        </p>
                    <cms:else />
                        <div class="alert alert-info p-4">
                            <p class="mb-0">You have not placed any orders while signed in to this account yet.</p>
                        </div>
                        <a href="<cms:link 'product.php' />" class="btn btn-primary mt-3">Start shopping</a>
                    </cms:if>

                <cms:else />
                    <div class="alert alert-info p-4">
                        <p class="mb-0">Sign in to see your order history.</p>
                    </div>
                    <a href="<cms:link 'users/login.php' />" class="btn btn-primary mt-3">Sign in</a>
                </cms:if>

            </div>
        </div>
    </div>
</section>

<cms:ignore>
    Status pills use FIXED pairs. text-bg-success and friends read a theme
    variable for the background while the paired foreground is a build-time
    literal, so the two can drift apart when Site Colors change. An order
    status that can turn unreadable is worse than no pill. See the store
    colour decision in the project notes.
</cms:ignore>
<style>
 .mo-pill{display:inline-block;font-size:11px;font-weight:700;letter-spacing:.05em;
          text-transform:uppercase;padding:3px 9px;border-radius:3px;border:1px solid}
 .mo-pill-ok{background:#e4f0ea;color:#1c6045;border-color:#1c6045}
 .mo-pill-wait{background:#f6efe1;color:#7a4f0c;border-color:#7a4f0c}
 .mo-pill-back{background:#f6e6e4;color:#a8322a;border-color:#a8322a}
</style>

<cms:embed 'pb_mods/pg_frame/footer/ftr_emb.htm' />
<cms:embed 'pb_mods/pg_frame/tail.htm' />
<?php COUCH::invoke(); ?>
