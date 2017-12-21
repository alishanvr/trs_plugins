<?php
	/**
	 * Created by PhpStorm.
	 * User: Ali Shan
	 * Date: 04-Sep-17
	 * Time: 5:03 PM
	 */
	
	namespace AdminInvoiceSystem;
	
	
	use CommonInvoiceSystem\InvoiceSystem;
	
	if ( ! defined( 'ABSPATH' ) ) {
		exit;
	}
	
	if ( ! class_exists( 'MenuPage' ) ) {
		class MenuPage {
			public function __construct() {
				//add_action('admin_menu', [$this, 'create_menu_page']);
				
				
			}
			
			public function create_menu_page() {
				add_menu_page(
					__( 'Invoice System', TRS_INVOICE_SYSTEM_DOMAIN ),
					__( 'Invoice System', TRS_INVOICE_SYSTEM_DOMAIN ),
					'manage_options',
					'trs-invoice-system',
					[ $this, 'menu_page_render' ],
					'dashicons-media-text',
					20
				);
			}
			
			public function menu_page_render() {
			
			}
		}
	}