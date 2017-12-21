<?php
	
	namespace AdminInvoiceSystem;
	
	
	use CommonInvoiceSystem\InvoiceSystem;
	use InvoiceSystem\TRS_INVOICE_ShortCodes;
	
	if ( ! defined( 'ABSPATH' ) ) {
		exit;
	}
	
	if ( ! class_exists( 'InvoiceDynamicFields' ) ) {
		class InvoiceDynamicFields {
			
			public function __construct() {
				add_action( 'admin_menu', [ $this, 'add_dynamic_page' ] );
			}
			
			public function add_dynamic_page() {
				
				add_submenu_page(
					'edit.php?post_type=' . InvoicePost::getInvoicePostSlug(),
		            __( 'Dynamic Fields Generation', TRS_INVOICE_SYSTEM_DOMAIN ),
		            __( 'Dynamic Fields', TRS_INVOICE_SYSTEM_DOMAIN ),
					'manage_options',
					'trs-invoice-dynamic-fields-generator',
		            [$this, 'add_dynamic_page_render']
				);
				
				
				add_action('admin_enqueue_scripts', [$this, 'register_dynamic_fields_scripts']);
				add_action('admin_enqueue_scripts', [$this, 'enqueue_dynamic_fields_scripts']);
			}
			
			public function add_dynamic_page_render() {
				
				ob_start();
				
				include_once (TRS_INVOICE_SYSTEM_ADMIN_PATH . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'dynamic-fields.inc.php');
				
				echo ob_get_clean();
				
				
			}
			
			public function register_dynamic_fields_scripts() {
			
				
				wp_register_style(
					'trs-invoice-dynamic-fields-style',
					trailingslashit(TRS_INVOICE_SYSTEM_ADMIN_ASSETS_URL) . 'css/dynamic-fields.css'
				);
				wp_register_script(
					'trs-invoice-dynamic-fields-script',
					trailingslashit(TRS_INVOICE_SYSTEM_ADMIN_ASSETS_URL) . 'js/dynamic-fields.js',
					['jquery']
				);
			
			}
			
			public function enqueue_dynamic_fields_scripts() {
				wp_enqueue_style('trs-invoice-dynamic-fields-style');
				wp_enqueue_script('trs-invoice-dynamic-fields-script');
			}
		}
	}