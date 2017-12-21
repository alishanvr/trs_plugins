<?php
	/**
	 * Created by PhpStorm.
	 * User: Ali Shan
	 * Date: 09-Sep-17
	 * Time: 2:22 PM
	 */
	
	namespace InvoiceSystem;
	
	use AdminInvoiceSystem\InvoicePost;
	
	if (! defined( 'ABSPATH')) exit;
	
	if (! class_exists('')){
		class LocateTemplates {
			public function __construct() {
				add_action('wp_enqueue_scripts', [$this, 'register_scripts'], 5);
				add_action('wp_enqueue_scripts', [$this, 'enqueue_scripts'], 10);
				
				add_filter( 'single_template', [$this, 'trs_invoice_post_template'], 10, 1 );
			}
			
			public function register_scripts() {
				wp_register_style('trs-invoice-front-end', trailingslashit(TRS_INVOICE_SYSTEM_FrontEnd_ASSETS_URL) . 'css/invoice-frontend.css');
			}
			
			public function enqueue_scripts() {
				wp_enqueue_style('trs-invoice-front-end');
			}
			
			public function trs_invoice_post_template( $single_template ) {
				$object = get_queried_object();
				$file_name = "single-{$object->post_type}.php";
				
				if ($object->post_type !== InvoicePost::getInvoicePostSlug())
					return '';
				
				if ( locate_template( $file_name ) ) {
					$template = locate_template( $file_name );
				} else {
					// Template not found in theme's folder, use plugin's template as a fallback
					$template = trailingslashit(TRS_INVOICE_SYSTEM_TEMPLATES_PATH) . $file_name;
				}
				
				return $template;
			}
		
		}
	}