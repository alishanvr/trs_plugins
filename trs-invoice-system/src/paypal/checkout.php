<?php
	if ( ! defined( 'ABSPATH' ) ) {
		exit;
	}
	
	global $post;
	
	$json_value   = ( new \AdminInvoiceSystem\InvoiceMetaBoxes )->get_metafield_value( $post->ID, \AdminInvoiceSystem\InvoiceMetaBoxes::getOutputSectionSlug(), \AdminInvoiceSystem\InvoiceMetaBoxes::getProductInfoArr() );
	$row_listings = json_decode( $json_value );
	
	// isset is checked in previous. So, no need here.
	
	$secretKey       = \InvoiceSystem\FrontendInvoiceSystem::getSecretKey();
	$method          = \InvoiceSystem\FrontendInvoiceSystem::getEncryptionType();
	$iv              = base64_decode( esc_attr( $_POST['iv'] ) );
	$key             = esc_attr( $_POST['enc_key'] );
	$subtotal_amount = openssl_decrypt( $key, $method, $secretKey, false, $iv );
	
	
	$invoice_number = esc_attr( $_POST['in'] );
	$total_price    = (float) 0.0;
	
	$paypal = \InvoiceSystem\FrontendInvoiceSystem::getPayPalObj();
	
	$payer = new \PayPal\Api\Payer();
	$payer->setPaymentMethod( 'paypal' );
	
	
	$i     = 0;
	$items = [];
	
	foreach ( $row_listings as $row ) {
		$items[ $i ] = new \PayPal\Api\Item();
		
		$price = explode( ' ', $row->price );
		if ( is_array( $price ) && count( $price ) > 0 ) {
			$price      = (float) $price[ count( $price ) - 1 ];
			$qty        = ( ( $row->quantity ) < 1 ) ? 1 : $row->quantity;
			$unit_price = round( (float) ( $price / $qty ), 2 );
		} else {
			$unit_price = 0;
			$price      = 0;
		}
		$quantity = empty( $row->quantity ) ? 1 : $row->quantity;
		
		
		$items[ $i ]->setName( $row->name )
		            ->setCurrency( \InvoiceSystem\FrontendInvoiceSystem::getCurrency() )
		            ->setQuantity( $quantity )
		            ->setPrice( $unit_price )
		            ->setDescription( $row->name );
		
		$total_price += ( $unit_price * $quantity );
		/*echo 'Price: ' . $price .'<br/>';
		echo 'Unit: ' . $unit_price .'<br/>';
		echo 'Quantity: ' . $quantity .'<br/>';
		echo 'Multiplied: ' . (float) ($unit_price* $quantity) .'<br/>';
		echo 'Total Till: ' . $total_price .'<br/><br /><hr />';*/
		$i ++;
	}
	/*echo $total_price;
	exit;*/
	
	/*$item = new \PayPal\Api\Item();
	$item1 = new \PayPal\Api\Item();
	
	$item->setName( 'a' )
	     ->setCurrency( \InvoiceSystem\FrontendInvoiceSystem::getCurrency() )
	     ->setQuantity( 3 )
	     ->setPrice( 0 );*/
	
	/*$itemsArr[] = $item;
	
	$item1->setName( 'b' )
	     ->setCurrency( \InvoiceSystem\FrontendInvoiceSystem::getCurrency() )
	     ->setQuantity( 5 )
	     ->setPrice( 10 );
	
	$itemsArr[] = $item1;*/
	
	//231.99
	//25.05
	
	/*echo $total_price;
	echo '<tt><pre>' . var_export($items, true) . '</pre></tt>';exit;*/
	
	$itemList = new \PayPal\Api\ItemList();
	$itemList->setItems( $items );
	
	$details = new \PayPal\Api\Details();
	$details->setShipping( 0.0 )
	        ->setTax( 0.0 )
	        ->setSubtotal( $total_price );
	
	
	$amount_obj = new \PayPal\Api\Amount();
	$amount_obj->setCurrency( \InvoiceSystem\FrontendInvoiceSystem::getCurrency() )
	           ->setTotal( $total_price )
	           ->setDetails( $details );
	
	
	$transacton = new \PayPal\Api\Transaction();
	$transacton->setAmount( $amount_obj )
	           ->setItemList( $itemList )
	           ->setDescription( 'Pay for Invoice ' . $invoice_number )
	           ->setInvoiceNumber( $invoice_number );
	
	$redirectUrls = new \PayPal\Api\RedirectUrls();
	$redirectUrls->setReturnUrl( TRS_INVOICE_PAYPAL_PAY_URL . '?success=true&pid='.$post->ID)
	             ->setCancelUrl( TRS_INVOICE_PAYPAL_PAY_URL . '?success=false&pid='.$post->ID);
	
	$payment = new \PayPal\Api\Payment();
	$payment->setIntent( 'sale' )
	        ->setPayer( $payer )
	        ->setRedirectUrls( $redirectUrls )
	        ->setTransactions( [ $transacton ] );
	
	try {
		$payment->create( $paypal );
	} catch ( PayPal\Exception\PayPalConnectionException $ex ) {
		// @todo: -- DONE -- display proper error messages by redirecting
		
		// Saving into file if Debug mode is on.
		trs_invoice_paypal_debug_handler( $ex );
		
		
	} catch ( Exception $e ) {
		// @todo: -- DONE -- display proper error messages by redirecting
		
		// Saving into file if Debug mode is on.
		trs_invoice_paypal_debug_handler( $e );
	}
	
	$approvalURL = $payment->getApprovalLink();
	
	header( "Location: {$approvalURL}" );