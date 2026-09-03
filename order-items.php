<?php require_once( 'ccs_dash/cms.php' ); ?>
<cms:template title='Order Line Items' clonable='1' hidden='1' executable='0' parent='_donottouch_' order='83'>

    <!-- ==============================================================
         ORDER LINE ITEM
         --------------------------------------------------------------
         One page per line of an order, joined to orders.php by
         item_order_id (the k_last_insert_id returned when the parent
         order was created - data-bound-form.php:299).

         WHY NOT A MOSAIC ON orders.php
         db_persist writes only keys that exist in $pg->_fields
         (data-bound-form.php:175). Mosaic tiles are separate pages
         (mosaic.php:793), so a mosaic region cannot be populated by
         db_persist in a single call. A sibling template is the only
         shape that is reliably writable from a front-end template - and
         it matches how wishlist.php already stores one page per entry.

         EVERYTHING HERE IS A SNAPSHOT. Titles, SKUs and prices are copied
         in at purchase time and must never be re-read from the product.
         The one field that is a live reference is item_product_id, which
         exists purely so inventory deduction knows which product page to
         decrement.

         hidden='1' - these are never browsed on their own; they are read
         through their parent order.
         ============================================================== -->


    <!-- ============================== -->
    <!-- LINKAGE                        -->
    <!-- ============================== -->

    <!-- Join key back to orders.php. search_type='integer' so the lookup
         custom_field="item_order_id==<id>" is an indexed positive test.
         A positive test is safe against the INNER JOIN that custom_field
         builds (tags.php:2595); this field is always written, so no row
         is ever missing. -->
    <cms:editable type='text' name='item_order_id' label='Order ID'
        desc='k_page_id of the parent order.'
        search_type='integer' width='120' order='5' />

    <!-- The only LIVE reference on this template: which product page to
         decrement when the order is paid. Everything else is frozen. -->
    <cms:editable type='text' name='item_product_id' label='Product ID'
        desc='k_page_id of the purchased product. Used for inventory deduction.'
        search_type='integer' width='120' order='10' />


    <!-- ============================== -->
    <!-- SNAPSHOT OF WHAT WAS BOUGHT    -->
    <!-- ============================== -->

    <cms:editable type='text' name='item_title' label='Product Title'
        desc='As it read at purchase time.'
        order='15' />

    <cms:editable type='text' name='item_sku' label='SKU'
        desc='As it read at purchase time.'
        search_type='text' width='200' order='20' />

    <!-- Stored in the RAW cart format (os0: 0 | on0: Size | ...), not as
         display text. assets/js/ccs_js.js:261-269 splits exactly this
         format back into cart field names when re-ordering, so rewriting
         it as friendly text would break that path. A human-readable copy
         is rendered at display time instead. -->
    <cms:editable type='textarea' name='item_variants' label='Selected Variants'
        desc='Raw cart option string. Do not reformat - the re-order path parses it.'
        order='25' />


    <!-- ============================== -->
    <!-- QUANTITY & MONEY (SNAPSHOT)    -->
    <!-- ============================== -->

    <!-- THE number inventory deduction subtracts. -->
    <cms:editable type='text' name='item_qty' label='Quantity'
        search_type='integer' validator='non_negative_integer' width='90' order='30' />

    <cms:editable type='text' name='item_unit_price' label='Unit Price'
        desc='Price actually charged per unit, including any variant adjustment.'
        search_type='decimal' validator='non_negative_decimal' width='120' order='35' />

    <cms:editable type='text' name='item_line_total' label='Line Total'
        search_type='decimal' validator='non_negative_decimal' width='120' order='40' />

    <!-- Per-line tax class, captured from cart_ex.php's cart_alter_custom_fields
         listener (cart_ex.php:496-521), which already resolves each product's
         pp_tax_class relation into tax_class + tax_rate on the cart item.
         Stored per line because different products can carry different classes. -->
    <cms:editable type='text' name='item_tax_class' label='Tax Class'
        desc='Name of the tax class applied to this line.'
        search_type='text' width='160' order='45' />

    <!-- RESTOCKING
         How many of this line have been put back on the shelf. Written by
         _tools/restock.php, never by the checkout.

         A COUNT rather than a yes/no, because a customer can return 1 of 3.
         It is also what makes restocking idempotent: the tool only ever offers
         (item_qty - item_restocked_qty), so a reloaded page or a double click
         cannot inflate inventory.

         A refund does NOT set this. Money going back and goods coming back are
         separate events, and only one of them is something a gateway can tell
         us about. -->
    <cms:editable type='text' name='item_restocked_qty' label='Qty Returned To Stock'
        desc='Set by the restock tool. 0 means nothing has been put back.'
        search_type='integer' validator='non_negative_integer'
        width='120' order='96' />

    <cms:editable type='text' name='item_tax_rate' label='Tax Rate %'
        search_type='decimal' validator='non_negative_decimal' width='90' order='50' />


    <!-- ============================== -->
    <!-- ADMIN VIEWS                    -->
    <!-- ============================== -->
    <cms:config_list_view exclude='default-page' searchable='1' orderby='publish_date' order='desc'>
        <cms:field 'k_page_title' label='Line' sortable='0' />
        <cms:field 'item_order_id' header='Order ID' />
        <cms:field 'item_title' header='Product' />
        <cms:field 'item_qty' header='Qty' />
        <cms:field 'item_line_total' header='Line Total' />
        <cms:field 'item_restocked_qty' header='Restocked' />
        <cms:field 'k_actions' />
        <cms:field 'k_selector_checkbox' />
    </cms:config_list_view>

    <cms:config_form_view>
        <cms:field 'k_page_title' label='Line Reference' group='_custom_fields_' />
        <cms:field 'k_page_name' hide='1' />
    </cms:config_form_view>

</cms:template>
<?php COUCH::invoke(); ?>
