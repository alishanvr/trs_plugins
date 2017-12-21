<?php
	if ( ! defined( 'ABSPATH' ) ) {
		exit;
	}
	
	$request_scheme = isset( $_SERVER['REQUEST_SCHEME'] ) ? $_SERVER['REQUEST_SCHEME'] . '://' : 'http://';
	$host           = isset( $_SERVER['HTTP_HOST'] ) ? $_SERVER['HTTP_HOST'] : '';
	
	$uri = isset( $_SERVER['REQUEST_URI'] ) ? strpos( esc_attr( $_SERVER['REQUEST_URI'] ), 'checkout' ) ? preg_replace( '/&?checkout=([^&]$|[^&]*)/i', '', esc_attr( $_SERVER['REQUEST_URI'] ) ) : esc_attr( $_SERVER['REQUEST_URI'] ) : '';
	$uri = $uri[ strlen( $uri ) - 1 ] == '&' ? substr_replace( $uri, '', - 1 ) : $uri;
	$url = $request_scheme . $host . $uri . '&checkout=true';
	
	if ( ( isset( $_GET['checkout'] ) && ( esc_attr( $_GET['checkout'] ) === 'true' )
	       && isset( $_POST['enc_key'], $_POST['iv'], $_POST['in'] ) )
	) {
		require_once trailingslashit( TRS_INVOICE_SYSTEM_PAYPAL_DIR_PATH ) . 'checkout.php';
		
		return;
	}
