<?php require_once( 'ccs_dash/cms.php' ); ?>
<cms:template title='PayPal IPN' hidden='1' executable='1' parent='_donottouch_' order='9991'>
    <cms:editable type='message' name='pp_msg' order='1'>
        <p>PayPal's server-to-server payment confirmation. Never opened by a person.
        checkout.php points PayPal here via <code>notify_url</code>.</p>
    </cms:editable>
</cms:template>
<cms:no_cache />
<cms:ignore>
    ================================================================
    PAYPAL IPN LISTENER

    PayPal Payments Standard has no signature. The only way to know a
    notification is genuine is to post the whole thing straight back and
    have PayPal say VERIFIED. Anything else is discarded.

    Verification alone is not enough, because a VERIFIED notification
    only proves PayPal sent it - not that it was sent to US, for the
    right amount. So four things are checked before a single unit of
    stock moves:

      1. PayPal returns VERIFIED
      2. payment_status is Completed  (not Pending, Failed, Refunded)
      3. receiver_email is our own account - otherwise someone could
         point their own PayPal button at this listener and have our
         stock deducted for a payment made to them
      4. mc_gross equals the order total we stored, and the currency
         matches - so a $1 payment cannot release a $200 order

    custom carries the order's page id; invoice carries the readable
    reference. Both were set by checkout.php.

    ALWAYS ANSWER 200. PayPal retries a non-2xx for days.
    ================================================================
</cms:ignore>
<cms:php>
    global $CTX, $CART;

    $CTX->set( 'pi_order_id', '', 'global' );
    $CTX->set( 'pi_txn_id',   '', 'global' );
    $CTX->set( 'pi_refund_order_id', '', 'global' );
    $CTX->set( 'pi_refund_total',    '', 'global' );
    $CTX->set( 'pi_refund_txn',      '', 'global' );
    $CTX->set( 'pi_note',     '', 'global' );

    http_response_code( 200 );
    header( 'Content-Type: text/plain' );

    $raw = @file_get_contents('php://input');

    if( $_SERVER['REQUEST_METHOD'] !== 'POST' || $raw === '' ){
        $CTX->set('pi_note', 'not an IPN post', 'global');
    }
    else{
        $sandbox = ( isset($CART) && $CART->get_config('paypal_use_sandbox') ) ? 1 : 0;
        // ipnpb.* is PayPal's dedicated verification host for IPN postbacks.
        $verify_url = $sandbox
            ? 'https://ipnpb.sandbox.paypal.com/cgi-bin/webscr'
            : 'https://ipnpb.paypal.com/cgi-bin/webscr';

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $verify_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, 'cmd=_notify-validate&' . $raw);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/x-www-form-urlencoded',
            'User-Agent: valasports-ipn',
            'Connection: Close',
        ));
        $verdict = trim( (string) curl_exec($ch) );
        $cerr    = curl_error($ch);
        curl_close($ch);

        if( $verdict !== 'VERIFIED' ){
            $CTX->set('pi_note', 'not verified by PayPal (' . $verdict . ') ' . $cerr, 'global');
        }
        else{
            $status   = isset($_POST['payment_status'])  ? $_POST['payment_status']  : '';
            $receiver = isset($_POST['receiver_email'])  ? strtolower(trim($_POST['receiver_email'])) : '';
            $gross    = isset($_POST['mc_gross'])        ? $_POST['mc_gross']        : '';
            $currency = isset($_POST['mc_currency'])     ? $_POST['mc_currency']     : '';
            $custom   = isset($_POST['custom'])          ? $_POST['custom']          : '';
            $txn      = isset($_POST['txn_id'])          ? $_POST['txn_id']          : '';

            $ours = isset($CART) ? strtolower(trim($CART->get_config('paypal_email'))) : '';
            $cur  = isset($CART) ? strtoupper(trim($CART->get_config('currency'))) : 'USD';

            if( $status === 'Refunded' || $status === 'Partially_Refunded' ){
                // A refund IPN echoes the ORIGINAL transaction's custom field, so
                // the order is still identifiable. mc_gross arrives NEGATIVE
                // (money leaving the merchant), hence abs() downstream.
                //
                // PayPal reports each refund on its own, not a running total,
                // so the amounts must be ADDED. order_refund.htm is told so via
                // ccs_refund_mode='increment'.
                //
                // txn_id on a refund IPN is the REFUND's own transaction id -
                // unique per refund, and identical across PayPal's retries of
                // that same refund. That makes it the idempotency key: the
                // recorder keeps the ids it has applied, so a replay adds
                // nothing while a genuine second partial adds its own amount.
                // (parent_txn_id would be the original payment, the same for
                // every refund against it, and is useless for this.)
                if( $ours === '' || $receiver !== $ours ){
                    $CTX->set('pi_note', 'refund receiver_email mismatch', 'global');
                }
                elseif( $custom === '' ){
                    $CTX->set('pi_note', 'refund carried no order id in custom', 'global');
                }
                elseif( $txn === '' ){
                    // No id means no way to tell this refund from a replay of
                    // it. Recording nothing is the safe failure.
                    $CTX->set('pi_note', 'refund carried no txn_id - not recorded', 'global');
                }
                else{
                    $CTX->set('pi_refund_order_id', $custom, 'global');
                    $CTX->set('pi_refund_total', abs((float)$gross), 'global');
                    $CTX->set('pi_refund_txn', $txn, 'global');
                    $CTX->set('pi_note', 'refund on order ' . $custom, 'global');
                }
            }
            elseif( $status !== 'Completed' ){
                $CTX->set('pi_note', 'payment_status is ' . $status . ' - not fulfilling', 'global');
            }
            elseif( $ours === '' || $receiver !== $ours ){
                $CTX->set('pi_note', 'receiver_email mismatch', 'global');
            }
            elseif( strtoupper($currency) !== $cur ){
                $CTX->set('pi_note', 'currency mismatch', 'global');
            }
            elseif( $custom === '' ){
                $CTX->set('pi_note', 'no order id in custom', 'global');
            }
            else{
                // Amount is compared against the stored order total, read
                // straight from the DB - never from anything PayPal sent.
                $CTX->set('pi_order_id', $custom, 'global');
                $CTX->set('pi_txn_id', $txn, 'global');
                $CTX->set('pi_gross', $gross, 'global');
                $CTX->set('pi_note', 'verified', 'global');
            }
        }
    }
</cms:php>

<cms:if pi_order_id>
    <cms:pages masterpage='orders.php' id=pi_order_id limit='1'>
        <cms:set pi_expected = order_total scope='global' />
    </cms:pages>

    <cms:ignore>
        Compare to the cent. Anything short - a partial payment, a
        tampered button - stops here with the order left pending for a
        human to look at.
    </cms:ignore>
    <cms:php>
        global $CTX;
        $paid = (float) $CTX->get('pi_gross');
        $want = (float) $CTX->get('pi_expected');
        $ok   = ( $want > 0 && abs($paid - $want) < 0.01 );
        $CTX->set( 'pi_amount_ok', $ok ? '1' : '', 'global' );
        if( !$ok ){
            $CTX->set( 'pi_note', 'amount mismatch: paid ' . $paid . ' expected ' . $want, 'global' );
        }
    </cms:php>

    <cms:if pi_amount_ok>
        <cms:set ccs_fulfil_order_id = pi_order_id />
        <cms:set ccs_fulfil_txn_id = pi_txn_id />
        <cms:embed 'utils/order_fulfil.htm' />
    </cms:if>
</cms:if>
<cms:if pi_refund_order_id>
    <cms:set ccs_refund_order_id = pi_refund_order_id />
    <cms:set ccs_refund_total    = pi_refund_total />
    <cms:set ccs_refund_txn      = pi_refund_txn />
    <cms:set ccs_refund_mode     = 'increment' />
    <cms:embed 'utils/order_refund.htm' />
</cms:if>

<cms:show pi_note /> <cms:show ccs_fulfil_result /><cms:show ccs_refund_result />
<?php COUCH::invoke(); ?>
