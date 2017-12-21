<?php
	/**
	 * Created by PhpStorm.
	 * User: Ali Shan
	 * Date: 04-Sep-17
	 * Time: 12:22 AM
	 */
	
	namespace CommonInvoiceSystem;
	
	
	use AdminInvoiceSystem\InvoiceDynamicFields;
	use AdminInvoiceSystem\InvoiceMetaBoxes;
	use AdminInvoiceSystem\InvoicePost;
	use AdminInvoiceSystem\MenuPage;
	use AdminInvoiceSystem\SaveInvoiceMetaBoxes;
	use InvoiceSystem\FrontendInvoiceSystem;
	use InvoiceSystem\LocateTemplates;
	use InvoiceSystem\TRS_INVOICE_ShortCodes;
	
	if ( ! defined( 'ABSPATH' ) ) {
		exit;
	}
	
	if ( ! class_exists( 'InvoiceSystem' ) ) {
		class InvoiceSystem {
			protected $invoice_system_activation_string;
			protected $invoice_system_plugin_path;
			protected $paypal_payment_success_page;
			
			
			/*
			* @todo: Nothing todo in free version.
			*  is Invoice System paid ?
			* */
			//static private $is_active;
			
			public function __construct( $plugin_path ) {
				$this->setInvoiceSystemActivationString( 'is_trs_invoicesystem_active' );
				$this->setInvoiceSystemPluginPath( $plugin_path );
				$this->setPaypalPaymentSuccessPage('trs-invoice-paypal-success-page');
 			
				register_activation_hook( $plugin_path, [ $this, 'invoice_system_activated' ] );
				register_deactivation_hook( $plugin_path, [ $this, 'invoice_system_deactivated' ] );
				
				add_action( 'init', [ $this, 'is_woocommerce_activated' ], 0);
				add_action( 'init', [ $this, 'redirect_to_paypal_payment_page' ], 10);
				
				
			}
			
			public function is_woocommerce_activated(  ) {
				if (! class_exists( 'WooCommerce' ) ){
					add_action('admin_notices', [$this, 'woocommerce_not_active']);
					
					return ''; // No need to go to next step.
				}
				
				// Separate function so that these can easily remove if required.
				
				/*
                 * @todo: Nothing todo in free version.
                 *  is Invoice System paid ?
                 * */
				//add_action( 'init', [ $this, 'load_core' ], 1 );
				
				add_action( 'init', [ $this, 'define_constants' ], 1 );
				add_action( 'init', [ $this, 'invoice_post' ], 5 );
				add_action( 'init', [ $this, 'menu_page' ], 6 ); // @todo: <-- Remove this function and class plus file if not required after fully development of v 1.0.0
				add_action( 'init', [ $this, 'invoice_meta_boxes' ], 7 );
				add_action( 'init', [ $this, 'save_invoice_meta_boxes' ], 7 );
				add_action( 'init', [ $this, 'dynamic_fields_class' ], 10 );
				add_action( 'init', [ $this, 'front_end_invoice_system' ], 1 );
				add_action( 'init', [ $this, 'front_end_load_templates' ], 2 );
				add_action( 'init', [ $this, 'load_shortcode_class' ], 10 );
				
				// @todo: Add Settings page by using Redux
				// add_action();
				
				return ""; // House Keeping
			}
			
			public function woocommerce_not_active() {
				
				$msg = 'WooCommerce plugin is not activated. Please be sure that WooCommerce is installed and activated.';
				$msg .= '<br />TRS Invoice System requires WooCommerce to work.';
				
				$html = '<div class="notice notice-error">';
				$html .= '<p>' . apply_filters('trs_invoice_woocommerce_not_active_msg', __($msg, TRS_INVOICE_SYSTEM_DOMAIN)) . '</p>';
				$html .= '</div>';
				
				echo apply_filters('trs_invoice_woocommerce_not_active_html', $html);
			}
			
			public function redirect_to_paypal_payment_page() {
				$current_url = trim(parse_url(add_query_arg(array()), PHP_URL_PATH), '/');
				$paypal_url = trim(parse_url(TRS_INVOICE_PAYPAL_PAY_URL, PHP_URL_PATH), '/');
				
				// Load Payment file if its payment request.
				if ($current_url === $paypal_url)
					require_once TRS_INVOICE_SYSTEM_PAYPAL_DIR_PATH . '/pay.php';
				 
			}
			
			public function invoice_post(  ) {
				new InvoicePost();
			}
			
			public function menu_page() {
				new MenuPage();
			}
			
			public function invoice_meta_boxes() {
				new InvoiceMetaBoxes();
			}
			
			public function save_invoice_meta_boxes() {
				new SaveInvoiceMetaBoxes();
			}
			
			public function dynamic_fields_class() {
				// @todo: Pending for Future Release
				//new InvoiceDynamicFields();
			}
			
			public function front_end_invoice_system() {
				new FrontendInvoiceSystem();
			}
			
			public function front_end_load_templates() {
				new LocateTemplates();
			}
			
			
			public function load_shortcode_class() {
				new TRS_INVOICE_ShortCodes();
			}
			
			/*
             * @todo: Nothing todo in free version.
             *  is Invoice System paid ?
             * */
			/*public function load_core() {
			public function load_core() {
				global $trs_invoice_system_settings;
				$key = $trs_invoice_system_settings['regkey'];
				
				//@todo: check from live server whether this key is ok or not.
				$query = http_build_query( [ 'reg_key'     => $key  ] );
				
				$url = 'http://www.therightsol.com/server-side/trs-invoice-system/ckg.php?' . $query;
  				$curl = curl_init();
 				curl_setopt_array( $curl, [
					CURLOPT_RETURNTRANSFER => 1,
					CURLOPT_URL            => $url,
				 
 				] );
				$response = curl_exec( $curl );
 				curl_close( $curl );
 				
 				//21c0fa2a2f24d4637860265260075adc
				 
				if (empty($response) || !$response){
					//@todo: make logic to disable invoice system in a sense that Redux should always be available.
 
					$this->unpaid_plugin_handler();

				}else {
					self::setIsActive(true);
				}
				
				
				// Every thing is ok.
			}*/
			
			
			
			/**
			 * @return mixed
			 */
			/** @todo: Nothing todo in free version.
			
			public static function getIsActive() {
				return self::$is_active;
			}*/
			
			/**
			 * @param mixed $is_active
			 */
			/** @todo: Nothing todo in free version.
			 public static function setIsActive( $is_active ) {
				self::$is_active = $is_active;
			}*/
			
			
			/** @todo: Nothing todo in free version.
			public function unpaid_plugin_handler() {
				
				//@todo: write some logic to remove pages OR do something proper that invoice system stops.
				self::setIsActive(false);
				
			}*/
			
			public function define_constants() {
				define( 'TRS_INVOICE_SYSTEM_PLUGIN_PATH', $this->getInvoiceSystemPluginPath() );
				define( 'TRS_INVOICE_SYSTEM_PLUGIN_URL', plugins_url( '', $this->getInvoiceSystemPluginPath() ) );
				define( 'TRS_INVOICE_SYSTEM_ADMIN_PATH', dirname(TRS_INVOICE_SYSTEM_PLUGIN_PATH) . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . 'admin' );
				define( 'TRS_INVOICE_SYSTEM_ADMIN_ASSETS_URL', TRS_INVOICE_SYSTEM_PLUGIN_URL . '/src/admin/assets' );
				define( 'TRS_INVOICE_SYSTEM_PAYPAL_DIR_URL', TRS_INVOICE_SYSTEM_PLUGIN_URL . '/src/paypal' );
				define( 'TRS_INVOICE_SYSTEM_PAYPAL_DIR_PATH', dirname(TRS_INVOICE_SYSTEM_PLUGIN_PATH) . '/src/paypal' );
				define( 'TRS_INVOICE_SYSTEM_FrontEnd_ASSETS_URL', TRS_INVOICE_SYSTEM_PLUGIN_URL . '/src/front-end/assets' );
				define( 'TRS_INVOICE_SYSTEM_VERSION', '1.0.0' );
				define('TRS_INVOICE_SYSTEM_PLUGIN_NAME', 'TRS Invoice System');
				define( 'TRS_INVOICE_SYSTEM_DOMAIN', 'trs_invoice_system' );
				define( 'TRS_INVOICE_SYSTEM_TEMPLATES_PATH', dirname(TRS_INVOICE_SYSTEM_PLUGIN_PATH) . '/src/templates' );
				define( 'TRS_INVOICE_PAYPAL_PAY_URL', TRS_INVOICE_SYSTEM_PAYPAL_DIR_URL. '/trs-invoice-system-pay' );
 			}
			
			/**
			 * @return mixed
			 */
			public function getInvoiceSystemActivationString() {
				return $this->invoice_system_activation_string;
			}
			
			/**
			 * @param mixed $invoice_system_activation_string
			 */
			protected function setInvoiceSystemActivationString( $invoice_system_activation_string ) {
				$this->invoice_system_activation_string = $invoice_system_activation_string;
			}
			
			/**
			 * @return mixed
			 */
			public function getInvoiceSystemPluginPath() {
				return $this->invoice_system_plugin_path;
			}
			
			/**
			 * @param mixed $invoice_system_plugin_path
			 */
			protected function setInvoiceSystemPluginPath( $invoice_system_plugin_path ) {
				$this->invoice_system_plugin_path = $invoice_system_plugin_path;
			}
			
			
			public function invoice_system_activated() {
				update_option( $this->getInvoiceSystemActivationString(), 1 );
				
				// @todo: 0051:  Disable below functionality for time bieng. May be useful in future.
				
				return "";
				
				$success_page_args = array(
					'post_title'    => wp_strip_all_tags( 'PayPal Payment Success' ),
					'post_content'  => '['.TRS_INVOICE_ShortCodes::getPayapalPaymentSuccessSlug().']',
					'post_status'   => 'publish',
					'post_author'   =>  get_current_user_id(),
					'post_type'     =>  'page'
				);

				// Insert the post into the database
				$post_id = wp_insert_post( $success_page_args );
				if(!is_wp_error($post_id)){
					update_option($this->getPaypalPaymentSuccessPage(), $post_id);
				}else{
					//there was an error in the post insertion,
					//echo $post_id->get_error_message();
					//@todo: display admin notice and display link to route to settings page to recreate.
				}
			}
			
			public function invoice_system_deactivated() {
				update_option( $this->getInvoiceSystemActivationString(), 0 );
				
				// @todo: If functionality 0051 enabled. then option should be delete at time of disabling.
				
			}
			
			/**
			 * @return mixed
			 */
			public function getPaypalPaymentSuccessPage() {
				return $this->paypal_payment_success_page;
			}
			
			/**
			 * @param mixed $paypal_payment_success_page
			 */
			protected function setPaypalPaymentSuccessPage( $paypal_payment_success_page ) {
				$this->paypal_payment_success_page = $paypal_payment_success_page;
			}
			
			
		}
	}