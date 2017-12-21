<?php
	/**
	 * Created by PhpStorm.
	 * User: Ali Shan
	 * Date: 10-Sep-17
	 * Time: 3:26 AM
	 */
	
	namespace InvoiceSystem;
	
	
	use CommonInvoiceSystem\InvoiceSystem;
	
	if (! defined( 'ABSPATH')) exit;
	
	if (! class_exists('TRS_INVOICE_ShortCodes')){
		class TRS_INVOICE_ShortCodes  {
			
			static private $payapal_payment_success_slug;
 		
			/**
			 * @return mixed
			 */
			public static function getPayapalPaymentSuccessSlug() {
				return self::$payapal_payment_success_slug;
			}
			
			/**
			 * @param mixed $payapal_payment_success_slug
			 */
			public static function setPayapalPaymentSuccessSlug( $payapal_payment_success_slug ) {
				self::$payapal_payment_success_slug = $payapal_payment_success_slug;
			}
			
			
			
			public function __construct() {
				self::setPayapalPaymentSuccessSlug('trs-payment-success');
 			
				add_shortcode(self::getPayapalPaymentSuccessSlug(), [$this, 'paypal_pay_success']);
 			}
			
			public function paypal_pay_success() {
				echo 'Congratulations!';
			}
		}
	}