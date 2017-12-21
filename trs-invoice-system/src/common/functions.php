<?php
	if ( ! defined( 'ABSPATH' ) ) {
		exit;
	}
	
	function update_payment_status( array $arr ) {
		$post_id   = esc_attr( $arr['pid'] );
		$paymentId = esc_attr( $arr['paymentId'] );
		$token     = esc_attr( $arr['token'] );
		$PayerID   = esc_attr( $arr['PayerID'] );
		
		$vals = [
			'paymentId' => $paymentId,
			'token'     => $token,
			'PayerID'   => $PayerID,
			'status'    => esc_attr( $arr['status'] )
		];
		
		update_post_meta( $post_id, \InvoiceSystem\FrontendInvoiceSystem::getPaypalPaymentStatusSlug(), $vals );
	}
	
	function is_invoice_paid( $post_id ) {
		$status = esc_attr( get_post_meta( $post_id, \InvoiceSystem\FrontendInvoiceSystem::getPaypalPaymentStatusSlug(), true ) );
		
		if ( ! empty( $status ) ) {
			return true;
		}
		
		
		return false;
		
	}
	
	function get_invoice_enc_id( $post_id ) {
		return
			( new \AdminInvoiceSystem\InvoiceMetaBoxes() )->get_metafield_value( $post_id, \AdminInvoiceSystem\InvoiceMetaBoxes::getPostIdEncSectionSlug(), \AdminInvoiceSystem\InvoiceMetaBoxes::getPostIdEncFieldSlug() );
	}
	
	function get_redux_encryption_key() {
		global $trs_invoice_system_settings;
		
		return $trs_invoice_system_settings['trs-encryption-key'];
	}
	
	function get_redux_paypal_client_secret() {
		global $trs_invoice_system_settings;
		
		return $trs_invoice_system_settings['trs-paypal-client-secret'];
	}
	
	function get_redux_paypal_client_id() {
		global $trs_invoice_system_settings;
		
		return $trs_invoice_system_settings['trs-paypal-client-id'];
		
	}
	
	function is_redux_empty_word_on() {
		global $trs_invoice_system_settings;
		
		return ( $trs_invoice_system_settings['trs-invoice-replace-empty-switch'] == 1 ? true : false );
	}
	
	function get_redux_empty_word() {
		global $trs_invoice_system_settings;
		
		return $trs_invoice_system_settings['trs-invoice-replace-empty-word'];
	}
	
	function is_paypal_debuggin_on() {
		global $trs_invoice_system_settings;
		
		return ( $trs_invoice_system_settings['trs-invoice-paypal-debugging-switch'] == 1 ? true : false );
	}
	
	function get_paypal_debugging_file_path() {
		global $trs_invoice_system_settings;
		return $trs_invoice_system_settings['trs-invoice-paypal-debugging-file'];
	}
	
	function get_custom_user_css() {
		global $trs_invoice_system_settings;
		return $trs_invoice_system_settings['trs-invoice-css-field'];
	}
	
	function trs_is_live_paypal_mode() {
		global $trs_invoice_system_settings;
		
 		return (!empty($trs_invoice_system_settings['trs-paypal-is-live-credentials']))
			? (int)$trs_invoice_system_settings['trs-paypal-is-live-credentials'] : false;
	}
	
	
	if ( ! function_exists( 'trs_invoice_paypal_debug_handler' ) ) {
		function trs_invoice_paypal_debug_handler( Exception $exception ) {
			
			
			if ( is_paypal_debuggin_on() ) {
				$file = get_paypal_debugging_file_path();
				if ( file_exists( $file ) ) {
					
					$text = "**********************************************************************************************\n\n";
					$text .= 'Time: ' . date( 'd-F-Y H:i:s' ) . "\n\n";
					$text .= 'Error Code: ' . $exception->getCode() . "\n";
					$text .= 'Error Message: ' . $exception->getMessage() . "\n";
					$text .= 'TRACE: ' . var_export( $exception->getTrace(), true ) . "\n";
					$text .= "Error Data: " . $exception->getData() . "\n\n";
					$text .= "**********************************************************************************************\n\n";
					
					
					file_put_contents( $file, $text . file_get_contents( $file ) );
					
				}
			}
			
			
			if ( isset( $_SERVER['HTTP_REFERER'] ) ) {
				$req_uri_arr = explode( '&', $_SERVER['HTTP_REFERER'] );
				$url         = $req_uri_arr[0] . '&pp-err=true'; // PayPal error
			} else {
				$get_data = filter_input_array( INPUT_GET );
				$pid      = esc_attr( $get_data['pid'] );
				$url      = get_permalink( $pid ) . '&pp-err=true'; // PayPal error
			}
			
			
			wp_redirect( $url );
			exit;
			
		}
	}
	
	if ( ! function_exists( 'trs_invoice_paypal_cancel_url_handler' ) ) {
		function trs_invoice_paypal_cancel_url_handler( $url ) {
			
			if ( is_paypal_debuggin_on() ) {
				$file = get_paypal_debugging_file_path();
				if ( file_exists( $file ) ) {
					
					$text = "**********************************************************************************************\n\n";
					$text .= 'Time: ' . date( 'd-F-Y H:i:s' ) . "\n\n";
					$text .= "Error Code: --\n";
					$text .= 'Error Message: --' . "\n";
					$text .= 'TRACE: --' . "\n";
					$text .= "Status: User cancelled the payment" . "\n\n";
					$text .= "**********************************************************************************************\n\n";
					
					
					file_put_contents( $file, $text . file_get_contents( $file ) );
					
				}
			}
			
			
			$req_uri_arr = explode( '&', $url );
			$url         = $req_uri_arr[0]; // user cancelled the payment
			
			wp_redirect( $url );
			exit;
		}
	}
	
	if ( ! function_exists( 'trs_invoice_paypal_payment_success_handler' ) ) {
		function trs_invoice_paypal_payment_success_handler( $result ) {
			
			
			if ( is_paypal_debuggin_on() ) {
				$file = get_paypal_debugging_file_path();
				if ( file_exists( $file ) ) {
					
					$text = "**********************************************************************************************\n\n";
					$text .= 'Time: ' . date( 'd-F-Y H:i:s' ) . "\n\n";
					$text .= "Status: Payment Success" . "\n";
					$text .= 'Details: ' . var_export( $result, true ) . "\n\n";
					$text .= "**********************************************************************************************\n\n";
					
					
					file_put_contents( $file, $text . file_get_contents( $file ) );
					
				}
			}
			
			if ( isset( $_SERVER['HTTP_REFERER'] ) ) {
				$req_uri_arr = explode( '&', $_SERVER['HTTP_REFERER'] );
				$url         = $req_uri_arr[0] . '&pp-status=success'; // PayPal error
			} else {
				$get_data = filter_input_array( INPUT_GET );
				$id = (new \AdminInvoiceSystem\InvoiceMetaBoxes())->get_metafield_value(
					$get_data['pid'],
					\AdminInvoiceSystem\InvoiceMetaBoxes::getPostIdEncSectionSlug(),
					\AdminInvoiceSystem\InvoiceMetaBoxes::getPostIdEncFieldSlug()
				);
				
				$pid      = esc_attr( $get_data['pid'] );
				$url      = get_permalink( $pid ) . '&pp-status=success'; // PayPal error
			}
			wp_redirect( $url );
			exit;
			
		}
	}
	