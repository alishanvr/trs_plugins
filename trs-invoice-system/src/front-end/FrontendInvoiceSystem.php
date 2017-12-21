<?php
	/**
	 * Created by PhpStorm.
	 * User: Ali Shan
	 * Date: 09-Sep-17
	 * Time: 3:07 PM
	 */
	
	namespace InvoiceSystem;
	
	
	use PayPal\Auth\OAuthTokenCredential;
	use PayPal\Rest\ApiContext;
	
	if (! defined( 'ABSPATH')) exit;
	
	if (! class_exists('FrontendInvoiceSystem')){
		class FrontendInvoiceSystem {
			private static $secret_key;
			private static $encryption_type;
			private static $paypal_clientID;
			private static $paypal_clientSecret;
			private static $currency;
			private static $specialWord; // special word like N/A if value is not present in meta data.
			private static $replace_empty_value; // Do you want to replace empty values ?
			
			
			private static $paypal_payment_status_slug;
			
			/**
			 * @return mixed
			 */
			public static function getPaypalPaymentStatusSlug() {
				return self::$paypal_payment_status_slug;
			}
			
			/**
			 * @param mixed $paypal_payment_status_slug
			 */
			protected static function setPaypalPaymentStatusSlug( $paypal_payment_status_slug ) {
				self::$paypal_payment_status_slug = $paypal_payment_status_slug;
			}
			
			
			
			public function __construct() {
				
				global $woocommerce;
				
				// @todo: Remove in final Version.
				// -- Ali Shan --
				// PayPal Client ID: AZPkmQLmjxnLhjsOpHEpgN-jzdjhTe9fBXdcXRDXVQLk0ISdSnM3JVc0BCLGAkGYZ8tW_R1gtqPWIPWy
				// PayPal Client Secret: ECvX3AAy78ScanV8Bf3i3lEoMyB5HNVqKb6XFsiogeCqrIU_dwH0EDfvCNKcLd0cr4CGA8flseHdRyh_
				// Encryption Key: Rp5k:G)pnJv}RX98Yj_QyR.*:%CzY,3w~v}.ydpX<5$;E%3$*@U/k]M!9*U=>*dL}h>Ub>G++.8pTMfR!:UN%E8x?-r^ML~&K.+r83Vvs>{R{(9e9aK6eas5%.#ME/C~Bmc(fH;_vfX%4}_p}nrF.ENj}\*2`q{H%F<HUfZ&y;8mYc(WZumH:y^N74j(Ak=*8r-QdU+Ywp<h8.H`*%#8Z3p(UHfU'L2d\s.3,_YP]GCtBzJV.&bFuvTn.,S[d,^=}mRK?G42QzX>fjw;Je@e8~;f@R2rT2>g;db,Y,Q3k;tS!qbcBE;?]=2)yHSY~<Yc$`z%HQ%u2J.M/q#k.ZCaFv{gq&K!,wL=)w.qr,](LrAV8.tG/;x(JB/{!F!$>/~%!mXGfL^xv5ZczCR\>h@vprdeQP3CXL;()R,9Z.2V4E^7#!]a5Drm,#Z83W+Jmm%)aMz%Axb4S#XXZ^nxmC6-sb'Jk~GKh.*+Ux^v&~3>*R&4>`)e5/A)wUd.
				
				
				
				
				
				
				// @todo: --Done-- get these keys and settings from database. From settings.
				self::setSecretKey(get_redux_encryption_key());
				self::setEncryptionType('AES-256-CBC');
				self::setPaypalClientID(get_redux_paypal_client_id());
				self::setPaypalClientSecret(get_redux_paypal_client_secret());
				self::setCurrency(get_woocommerce_currency());
				
				self::$replace_empty_value = is_redux_empty_word_on();
				self::$specialWord = get_redux_empty_word();
				
				self::setPaypalPaymentStatusSlug('trs-invoice-paypal-status');
				
				
				//print Redux - User custom css
				add_action('wp_head', [$this, 'print_redux_css']);
			}
			
			public function print_redux_css() {
				if (empty(get_custom_user_css()))
					return;
				
				ob_start();
				
				echo '<style>' . get_custom_user_css() . '</style>';
				
				echo ob_get_clean();
			}
			
			/**
			 * @return string
			 */
			public static function getSpecialWord() {
				return self::$specialWord;
			}
			
			/**
			 * @return bool
			 */
			public static function isReplaceEmptyValue() {
				return self::$replace_empty_value;
			}
			
			
			
			/**
			 * @return mixed
			 */
			public static function getCurrency() {
				return self::$currency;
			}
			
			/**
			 * @param mixed $currency
			 */
			public static function setCurrency( $currency ) {
				self::$currency = $currency;
			}
			
			
			
			public static function getPayPalObj(){
				$cred = new OAuthTokenCredential( self::getPaypalClientID(), self::getPaypalClientSecret() );
				
				$paypal = new ApiContext(
					$cred
				);
				
				if (trs_is_live_paypal_mode() === 1){
					$paypal->setConfig(
						[
							"mode" => "live"
						]
					);
 				}
				
				return $paypal;
			}
			
			/**
			 * @return mixed
			 */
			public static function getPaypalClientID() {
				return self::$paypal_clientID;
			}
			
			/**
			 * @param mixed $paypal_clientID
			 */
			protected static function setPaypalClientID( $paypal_clientID ) {
				self::$paypal_clientID = $paypal_clientID;
			}
			
			/**
			 * @return mixed
			 */
			public static function getPaypalClientSecret() {
				return self::$paypal_clientSecret;
			}
			
			/**
			 * @param mixed $paypal_clientSecret
			 */
			protected static function setPaypalClientSecret( $paypal_clientSecret ) {
				self::$paypal_clientSecret = $paypal_clientSecret;
			}
			
			
			
			/**
			 * @return mixed
			 */
			public static function getSecretKey() {
				return self::$secret_key;
			}
			
			/**
			 * @param mixed $secret_key
			 */
			protected static function setSecretKey( $secret_key ) {
				self::$secret_key = $secret_key;
			}
			
			/**
			 * @return mixed
			 */
			public static function getEncryptionType() {
				return self::$encryption_type;
			}
			
			/**
			 * @param mixed $encryption_type
			 */
			protected static function setEncryptionType( $encryption_type ) {
				self::$encryption_type = $encryption_type;
			}
			
			
		
		}
	}