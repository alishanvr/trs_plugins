<?php
	
	if ( ! defined( 'ABSPATH' ) ) {
		exit( 'not defined' );
	}
	
	$url       = get_permalink( esc_attr( $_GET['pid'] ) );
	
	if ( isset( $_GET['success'] ) && $_GET['success'] !== 'true' ) {
		// @todo: -- DONE -- display proper error by redirecting
  
		// If Fail - if user cancels the payment.
		trs_invoice_paypal_cancel_url_handler( $url );
		
		 
	} else {
		
		// If Success
		
		if ( ! isset( $_GET['paymentId'], $_GET['PayerID'] ) ) {
			// @todo: if above params not exists.
            // I do not think there is any posibibility. But, it will depend on future usage and it will depend on feedback of different users.
            wp_die('some required params not exist. Please contact to developer. ');
		}
  
		$paypal = \InvoiceSystem\FrontendInvoiceSystem::getPayPalObj();
		
		$paymentId = esc_attr( $_GET['paymentId'] );
		$payerId   = esc_attr( $_GET['PayerID'] );
		
		$payment = \PayPal\Api\Payment::get( $paymentId, $paypal );
		
		$execute = new \PayPal\Api\PaymentExecution();
		$execute->setPayerId( $payerId );
		
		$result = '';
		try {
			$result = $payment->execute( $execute, $paypal );
		} catch ( PayPal\Exception\PayPalConnectionException $ex ) {
			// @todo: -- Done -- display proper error messages by redirecting
			
			// Saving into file if Debug mode is on.
			trs_invoice_paypal_debug_handler( $ex );
			
		} catch ( Exception $ex ) {
			// @todo: -- Done -- display proper error messages by redirecting
			
			// Saving into file if Debug mode is on.
			trs_invoice_paypal_debug_handler( $ex );
		}
		
		// Update Payment Status.
		update_payment_status( filter_input_array( INPUT_GET ) );
		
		//@todo: --DONE-- NOT Redirected after success / cancel. Check in both conditions.
		
		// Update into file if debug is on. else redirect.
		trs_invoice_paypal_payment_success_handler( $result );
		
		
	}
?><!--
<script>
    window.location.href = '<?php /*echo $url; */?>';
</script>-->