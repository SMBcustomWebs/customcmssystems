<?php require_once( 'ccs_dash/cms.php' ); ?>
<cms:template title='Orders' clonable='1' hidden='0' executable='0' parent='_site_' icon='shopping-cart' order='82'>

    <!-- ==============================================================
         ORDER RECORD
         --------------------------------------------------------------
         One page per order. Written by checkout.php via cms:db_persist,
         never by hand.

         executable='0' is deliberate: page.php:192 refuses to serve a
         non-executable template directly in the browser to anyone below
         super admin, so orders cannot be enumerated by URL. It does NOT
         block cms:pages, so a customer "my orders" listing still works.

         Line items are NOT a cms:mosaic. db_persist only writes keys that
         exist in $pg->_fields (data-bound-form.php:175), and mosaic tiles
         are separate pages (mosaic.php:793), so a mosaic cannot be
         populated programmatically in one call. Line items therefore live
         in order-items.php, joined by item_order_id.

         EVERY money and text value here is a SNAPSHOT taken at purchase
         time. Nothing in this template should ever be recalculated from
         live product data - prices and titles change, orders must not.
         ============================================================== -->


    <!-- ============================== -->
    <!-- ZONE 1 :: ORDER IDENTITY       -->
    <!-- ============================== -->
    <cms:editable type='message' name='msg_identity' order='1'>
        <div style="background:#e8f4fd; border:1px solid #b8daff; border-left:4px solid #1cc88a; padding:15px; border-radius:4px; margin-bottom:10px;">
            <h4 style="margin:0 0 5px 0; color:#1cc88a; font-family:sans-serif;">1. Order Identity &amp; Status</h4>
            <p style="margin:0; color:#5a5c69; font-size:13px; font-family:sans-serif;">Written automatically at checkout. Change status only to record a refund or cancellation.</p>
        </div>
    </cms:editable>

    <!-- Duplicated into k_page_title for a readable admin list, and kept
         as its own indexed field so the PayPal return can look an order up
         reliably without depending on how Couch slugifies the title. -->
    <cms:editable type='text' name='order_number' label='Order Number'
        desc='System generated. Do not edit.'
        search_type='text' width='200' order='5' />

    <!-- House rule: no opt_selected anywhere. The first option is the
         default, so Pending must stay top of the list. Values are bare
         machine tokens; labels are for humans only. -->
    <cms:editable type='dropdown' name='order_status' label='Order Status'
        desc='pending = created but not paid. paid = gateway confirmed.'
        opt_values='Pending=pending | Paid=paid | Failed=failed | Cancelled=cancelled | Refunded=refunded'
        order='10' />

    <!-- IDEMPOTENCY FLAG - the whole reason inventory deduction is safe.
         Deduction runs only when this is 0, and sets it to 1 in the same
         step. A page refresh, a retried PayPal notification, or a double
         submit therefore cannot decrement stock twice.
         A radio (not a checkbox) on purpose: checkbox values are stored as
         a pipe-delimited string and read inconsistently at runtime, whereas
         a radio reads cleanly as a plain order_stock_deducted = '1' test.
         (Deliberately not written as a tag here - a cms: sequence inside an
         HTML comment is still parsed and throws.) -->
    <cms:editable type='radio' name='order_stock_deducted' label='Stock Already Deducted'
        desc='Set by the system. Reset to No ONLY to deliberately re-run a failed deduction.'
        opt_values='No=0 | Yes=1'
        order='15' />


    <!-- ============================== -->
    <!-- ZONE 2 :: CUSTOMER             -->
    <!-- ============================== -->
    <cms:editable type='message' name='msg_customer' order='20'>
        <div style="background:#fff3cd; border:1px solid #ffe69c; border-left:4px solid #f6c23e; padding:15px; border-radius:4px; margin-top:20px; margin-bottom:10px;">
            <h4 style="margin:0 0 5px 0; color:#856404; font-family:sans-serif;">2. Customer</h4>
            <p style="margin:0; color:#5a5c69; font-size:13px; font-family:sans-serif;">Captured from the checkout form. order_user_id is blank for guest checkouts.</p>
        </div>
    </cms:editable>

    <cms:editable type='group' name='order_customer_grp' label='Customer Details' order='25'>

        <cms:editable type='text' name='order_user_id' label='User ID'
            desc='Blank for a guest checkout.'
            search_type='integer' width='120' class='col-md-3' order='5' />

        <cms:editable type='text' name='order_first_name' label='First Name' class='col-md-4' order='10' />
        <cms:editable type='text' name='order_last_name'  label='Last Name'  class='col-md-4' order='15' />
        <cms:editable type='text' name='order_email'      label='Email'      class='col-md-5' order='20' />

    </cms:editable>


    <!-- ============================== -->
    <!-- ZONE 3 :: ADDRESSES            -->
    <!-- ============================== -->
    <cms:editable type='group' name='order_ship_grp' label='Shipping Address' collapsed='1' order='30'>
        <cms:editable type='text' name='order_ship_address' label='Street Address' class='col-md-12' order='5' />
        <cms:editable type='text' name='order_ship_city'    label='City'           class='col-md-4'  order='10' />
        <cms:editable type='text' name='order_ship_state'   label='State'          class='col-md-4'  order='15' />
        <cms:editable type='text' name='order_ship_zip'     label='Zip'            class='col-md-4'  order='20' />
    </cms:editable>

    <cms:editable type='group' name='order_bill_grp' label='Billing Address' collapsed='1' order='35'>
        <cms:editable type='message' name='msg_billing' order='1'>
            <p style="margin:0; color:#5a5c69; font-size:13px; font-family:sans-serif;">
                If the customer ticked &quot;same as shipping&quot;, checkout copies the shipping values in here
                so the order always carries a complete billing record of its own.
            </p>
        </cms:editable>
        <cms:editable type='text' name='order_bill_address' label='Street Address' class='col-md-12' order='5' />
        <cms:editable type='text' name='order_bill_city'    label='City'           class='col-md-4'  order='10' />
        <cms:editable type='text' name='order_bill_state'   label='State'          class='col-md-4'  order='15' />
        <cms:editable type='text' name='order_bill_zip'     label='Zip'            class='col-md-4'  order='20' />
    </cms:editable>


    <!-- ============================== -->
    <!-- ZONE 4 :: PAYMENT              -->
    <!-- ============================== -->
    <cms:editable type='message' name='msg_payment' order='40'>
        <div style="background:#fff3cd; border:1px solid #ffe69c; border-left:4px solid #f6c23e; padding:15px; border-radius:4px; margin-top:20px; margin-bottom:10px;">
            <h4 style="margin:0 0 5px 0; color:#856404; font-family:sans-serif;">3. Payment</h4>
            <p style="margin:0; color:#5a5c69; font-size:13px; font-family:sans-serif;">The gateway's own transaction reference is the audit trail. Never edit it.</p>
        </div>
    </cms:editable>

    <cms:editable type='group' name='order_payment_grp' label='Payment' order='45'>

        <cms:editable type='dropdown' name='order_gateway' label='Gateway'
            opt_values='Stripe=stripe | PayPal=paypal'
            class='col-md-4' order='5' />

        <cms:editable type='text' name='order_txn_id' label='Transaction ID'
            desc='Stripe charge id, or PayPal transaction id.'
            search_type='text' class='col-md-6' order='10' />

        <cms:editable type='text' name='order_paid_on' label='Paid On'
            desc='Written by the system when the gateway confirms.'
            class='col-md-4' order='15' />

    </cms:editable>


    <!-- ============================== -->
    <!-- ZONE 5 :: TOTALS (SNAPSHOT)    -->
    <!-- ============================== -->
    <cms:editable type='message' name='msg_totals' order='50'>
        <div style="background:#fff3cd; border:1px solid #ffe69c; border-left:4px solid #f6c23e; padding:15px; border-radius:4px; margin-top:20px; margin-bottom:10px;">
            <h4 style="margin:0 0 5px 0; color:#856404; font-family:sans-serif;">4. Totals</h4>
            <p style="margin:0; color:#5a5c69; font-size:13px; font-family:sans-serif;">Frozen at purchase time. Tax is stored as a single figure; the per-class breakdown lives on each line item.</p>
        </div>
    </cms:editable>

    <cms:editable type='group' name='order_totals_grp' label='Totals' order='55'>
        <cms:editable type='text' name='order_subtotal' label='Sub Total'
            search_type='decimal' validator='non_negative_decimal' width='120' class='col-md-3' order='5' />
        <cms:editable type='text' name='order_shipping' label='Shipping'
            search_type='decimal' validator='non_negative_decimal' width='120' class='col-md-3' order='10' />
        <cms:editable type='text' name='order_tax' label='Tax'
            search_type='decimal' validator='non_negative_decimal' width='120' class='col-md-3' order='15' />
        <cms:editable type='text' name='order_total' label='Order Total'
            search_type='decimal' validator='non_negative_decimal' width='120' class='col-md-3' order='20' />
    </cms:editable>


    <!-- ============================== -->
    <!-- ADMIN VIEWS                    -->
    <!-- ============================== -->
    <cms:config_list_view exclude='default-page' searchable='1' orderby='publish_date' order='desc'>
        <cms:style>
            .col-k_page_title { width: 18% !important; }
        </cms:style>
        <cms:field 'k_page_title' label='Order' sortable='0' class='k_page_title' />
        <cms:field 'order_status' />
        <cms:field 'order_total' />
        <cms:field 'order_gateway' />
        <cms:field 'order_stock_deducted' header='Stock Out' />
        <cms:field 'k_publish_date' label='Placed' />
        <cms:field 'k_actions' />
        <cms:field 'k_selector_checkbox' />
    </cms:config_list_view>

    <cms:config_form_view>
        <cms:field 'k_page_title' label='Order Number' group='_custom_fields_' />
        <cms:field 'k_page_name' hide='1' />
    </cms:config_form_view>

</cms:template>
<?php COUCH::invoke(); ?>
