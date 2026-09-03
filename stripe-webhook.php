<?php require_once( 'ccs_dash/cms.php' ); ?>
<cms:template title='Stripe Webhook' hidden='1' executable='1' parent='_donottouch_' order='9990'>
    <cms:editable type='message' name='sw_msg' order='1'>
        <p>Stripe's server-to-server payment confirmation. Never opened by a person.
        Add the endpoint in the Stripe dashboard and put its signing secret in
        <code>K_STRIPE_TEST_WEBHOOK_SECRET</code>.</p>
    </cms:editable>
</cms:template>
<cms:no_cache />
<cms:ignore>
    ================================================================
    STRIPE WEBHOOK - the authoritative confirmation.

    The browser is never trusted to tell us a payment succeeded. It can
    be closed, lose signal, or be forged. Stripe posts here directly and
    keeps retrying for days until it gets a 2xx, so a payment taken
    while the customer's phone died still lands as a paid order.

    WHY THE SIGNATURE CHECK IS NOT OPTIONAL
    This URL is public. Without verification, anyone who guesses it
    could POST a fabricated "payment succeeded" event and walk off with
    stock. The signing secret is shared only between Stripe and this
    server, so an HMAC over the exact raw body is what separates a real
    event from a forged one. If the secret is missing we refuse
    outright rather than degrade to trusting the payload.

    Read the RAW body, never $_POST - the signature is computed over
    the exact bytes Stripe sent, so any reserialisation breaks it.
    ================================================================
</cms:ignore>
<cms:php>
    global $CTX;

    $CTX->set( 'sw_order_id', '', 'global' );
    $CTX->set( 'sw_txn_id',   '', 'global' );
    $CTX->set( 'sw_refund_order_id', '', 'global' );
    $CTX->set( 'sw_refund_total',    '', 'global' );
    $CTX->set( 'sw_note',     '', 'global' );
    $CTX->set( 'sw_http',     '400', 'global' );

    $test   = ( defined('K_STRIPE_TEST_MODE') && K_STRIPE_TEST_MODE ) ? 1 : 0;
    $secret = $test
        ? ( defined('K_STRIPE_TEST_WEBHOOK_SECRET') ? K_STRIPE_TEST_WEBHOOK_SECRET : '' )
        : ( defined('K_STRIPE_LIVE_WEBHOOK_SECRET') ? K_STRIPE_LIVE_WEBHOOK_SECRET : '' );

    $payload = @file_get_contents('php://input');
    $sig_hdr = isset($_SERVER['HTTP_STRIPE_SIGNATURE']) ? $_SERVER['HTTP_STRIPE_SIGNATURE'] : '';

    if( $_SERVER['REQUEST_METHOD'] !== 'POST' ){
        $CTX->set('sw_note', 'not a POST', 'global');
        $CTX->set('sw_http', '405', 'global');
    }
    elseif( $secret === '' ){
        // Refuse rather than accept unverifiable events.
        $CTX->set('sw_note', 'webhook secret not configured', 'global');
        $CTX->set('sw_http', '500', 'global');
    }
    elseif( $payload === '' || $sig_hdr === '' ){
        $CTX->set('sw_note', 'missing body or signature', 'global');
    }
    else{
        // Stripe-Signature: t=<unix>,v1=<hex>[,v1=<hex>...]
        $ts = ''; $sigs = array();
        foreach( explode(',', $sig_hdr) as $part ){
            $kv = explode('=', trim($part), 2);
            if( count($kv) != 2 ) continue;
            if( $kv[0] === 't' )  { $ts = $kv[1]; }
            if( $kv[0] === 'v1' ) { $sigs[] = $kv[1]; }
        }

        $expected = hash_hmac( 'sha256', $ts . '.' . $payload, $secret );

        $match = false;
        foreach( $sigs as $s ){
            // hash_equals, not ==, so a wrong guess cannot be narrowed by timing.
            if( hash_equals($expected, $s) ){ $match = true; break; }
        }

        // Replay window. A captured event cannot be re-posted days later.
        $fresh = ( $ts !== '' && abs(time() - (int)$ts) < 300 );

        if( !$match ){
            $CTX->set('sw_note', 'signature mismatch', 'global');
            $CTX->set('sw_http', '400', 'global');
        }
        elseif( !$fresh ){
            $CTX->set('sw_note', 'timestamp outside tolerance', 'global');
            $CTX->set('sw_http', '400', 'global');
        }
        else{
            $event = json_decode( $payload, true );
            $type  = isset($event['type']) ? $event['type'] : '';
            $obj   = isset($event['data']['object']) ? $event['data']['object'] : array();

            // Verified. Answer 200 from here on, whatever the event type -
            // an unrecognised event is not an error, and a non-2xx would make
            // Stripe retry it forever.
            $CTX->set('sw_http', '200', 'global');

            $oid = isset($obj['metadata']['order_id']) ? $obj['metadata']['order_id'] : '';

            if( $type === 'payment_intent.succeeded' ){
                if( $oid !== '' ){
                    $CTX->set('sw_order_id', $oid, 'global');
                    $CTX->set('sw_txn_id', isset($obj['id']) ? $obj['id'] : '', 'global');
                    $CTX->set('sw_note', 'fulfilling order ' . $oid, 'global');
                } else {
                    $CTX->set('sw_note', 'succeeded event carried no order_id metadata', 'global');
                }
            }
            elseif( $type === 'charge.refunded' ){
                // charge.refunded is used rather than refund.created because the
                // CHARGE object carries three things in one payload: the order_id
                // metadata inherited from the PaymentIntent, the original amount,
                // and amount_refunded. A refund object carries its own metadata
                // (empty) and would need a second API call to find the order.
                //
                // amount_refunded is CUMULATIVE across partial refunds, which is
                // exactly what order_refund.htm wants - see the note in that file
                // about why storing a total is what makes replays harmless.
                if( $oid !== '' ){
                    $cents = isset($obj['amount_refunded']) ? (int)$obj['amount_refunded'] : 0;
                    $CTX->set('sw_refund_order_id', $oid, 'global');
                    $CTX->set('sw_refund_total', number_format($cents / 100, 2, '.', ''), 'global');
                    $CTX->set('sw_note', 'refund on order ' . $oid, 'global');
                } else {
                    $CTX->set('sw_note', 'refund event carried no order_id metadata', 'global');
                }
            }
            else{
                $CTX->set('sw_note', 'ignored event type ' . $type, 'global');
            }
        }
    }

    http_response_code( (int) $CTX->get('sw_http') );
    header( 'Content-Type: text/plain' );
</cms:php>
<cms:if sw_order_id>
    <cms:set ccs_fulfil_order_id = sw_order_id />
    <cms:set ccs_fulfil_txn_id = sw_txn_id />
    <cms:embed 'utils/order_fulfil.htm' />
</cms:if>

<cms:if sw_refund_order_id>
    <cms:ignore>
        'total', stated rather than left to the default. amount_refunded is
        cumulative across partial refunds, so the figure REPLACES what is
        stored and a replayed event changes nothing. PayPal is the opposite
        and passes 'increment' - see the note in utils/order_refund.htm.
    </cms:ignore>
    <cms:set ccs_refund_order_id = sw_refund_order_id />
    <cms:set ccs_refund_total    = sw_refund_total />
    <cms:set ccs_refund_mode     = 'total' />
    <cms:embed 'utils/order_refund.htm' />
</cms:if>

<cms:show sw_note /> <cms:show ccs_fulfil_result /><cms:show ccs_refund_result />
<?php COUCH::invoke(); ?>
