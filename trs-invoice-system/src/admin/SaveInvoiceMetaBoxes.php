<?php
	/**
	 * Created by PhpStorm.
	 * User: Ali Shan
	 * Date: 05-Sep-17
	 * Time: 1:03 AM
	 */
	
	namespace AdminInvoiceSystem;
	
	
	if ( ! defined( 'ABSPATH' ) ) {
		exit;
	}
	
	
	if ( ! class_exists( 'SaveInvoiceMetaBoxes' ) ) {
		class SaveInvoiceMetaBoxes {
			
			private $meta_key_slug;
 		
			/**
			 * @return mixed
			 */
			public function getMetaKeySlug() {
				return $this->meta_key_slug;
			}
			
			/**
			 * @param mixed $meta_key_slug
			 */
			protected function setMetaKeySlug( $meta_key_slug ) {
				$this->meta_key_slug = $meta_key_slug;
			}
			
			
			
			public function __construct() {
				$this->setMetaKeySlug('trs-invoice-info');
 			
				add_action('save_post_' . InvoicePost::getInvoicePostSlug(), [$this, 'save_invoice_meta_values']);
			}
			
			public function save_invoice_meta_values($product_id) {
				// check properly with nonce check and then save.
				
				$posted_arr = filter_input_array(INPUT_POST);
				
				if (wp_is_post_autosave($product_id))
					return;
				
				if (wp_is_post_revision($product_id))
					return;
					
				if (! current_user_can('manage_options'))
					return;
				
				if (! isset($posted_arr[InvoiceMetaBoxes::getProductNonceName()])
				    && !wp_verify_nonce($posted_arr[InvoiceMetaBoxes::getProductNonceName()], $posted_arr[InvoiceMetaBoxes::getProductNonceAction()] )){
					
					// @todo: make log about it. and notify to developer and admin.
					return;
				}
				
				$registered_sections = InvoiceMetaBoxes::get_meta_boxes_registered_sections();
				
				$data = [];
				foreach ($registered_sections as $registered_section){
					if ($registered_section === InvoiceMetaBoxes::getPostIdEncSectionSlug()){
						$data[$registered_section][InvoiceMetaBoxes::getPostIdEncFieldSlug()] = md5(($product_id . '-' . $posted_arr['post_title']));
						continue;
					}
					$data[$registered_section] = $posted_arr[$registered_section];
				}
				
				
				update_post_meta($product_id, $this->getMetaKeySlug(), maybe_serialize($data));
				
				return; // House Keeping
			}
		}
	}