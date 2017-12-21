<?php
	/**
	 * Created by PhpStorm.
	 * User: Ali Shan
	 * Date: 04-Sep-17
	 * Time: 6:13 PM
	 */
	
	namespace AdminInvoiceSystem;
	
	use CommonInvoiceSystem\InvoiceSystem;
	use InvoiceSystem\FrontendInvoiceSystem;
	
	if ( ! defined( 'ABSPATH' ) ) {
		exit;
	}
	
	if ( ! class_exists( 'InvoiceMetaBoxes' ) ) {
		class InvoiceMetaBoxes extends InvoicePost {
			private static $customer_billing_information_section_slug;
			private static $customer_shipping_information_section_slug;
			private static $products_information_section_slug;
			private static $output_section_slug;
			
			private static $_billing_first_name;
			private static $_billing_last_name;
			private static $_billing_email;
			private static $_billing_address_line_1;
			private static $_billing_address_line_2;
			private static $_billing_city;
			private static $_billing_state;
			private static $_billing_zip_code;
			private static $_billing_phone;
			private static $_billing_country;
			private static $_billing_address;
			private static $_billing_comment;
			private static $_billing_companyName;
 		
			
			
			private static $_shipping_first_name;
			private static $_shipping_last_name;
			private static $_shipping_email;
			private static $_shipping_address_line_1;
			private static $_shipping_address_line_2;
			private static $_shipping_city;
			private static $_shipping_state;
			private static $_shipping_zip_code;
			private static $_shipping_phone;
			private static $_shipping_country;
			private static $_shipping_address;
			private static $_shipping_comment;
			private static $_shipping_companyName;
			
			
			private static $product_name;
			private static $product_quantity;
			private static $product_price;
			
			//@todo: try to write some logic to automate this process. user create inputs and so, all inputs of this files will be deleted
			/* --- custom inputs --- */
			private static $specification;
			private static $size;
			private static $stock;
			private static $printing;
			private static $finishing;
			
			
			private static $output_html;
			private static $product_info_arr;
			
			private static $product_nonce_name;
			private static $product_nonce_action;
			
			private $meta_data_arr;
			private $current_pid;
			
			private static $post_id_enc_field_slug;
			private static $post_id_enc_section_slug;
			
			private static $isReplacementOn;
			
			
			public function __construct() {
				//parent::__construct();
				
				/*
				* @todo: Nothing todo in free version.
 				* */
				/*if (!InvoiceSystem::getIsActive()){
					add_action(
						'add_meta_boxes_' . parent::getInvoicePostSlug(),
						[ $this, 'inactive_plugin' ]
					);
					
					return;
                }*/
					
				
				self::setCustomerBillingInformationSectionSlug( 'trs-invoice-billing-customer-info' );
				self::setCustomerShippingInformationSectionSlug( 'trs-invoice-shipping-customer-info' );
				self::setProductsInformationSectionSlug( 'trs-invoice-product-info' );
				self::setOutputSectionSlug( 'trs-invoice-output-html' );
				
				self::setBillingFirstName( 'trs-billing-customer-firstname' );
				self::setBillingLastName( 'trs-billing-customer-lastname' );
				self::setBillingEmail( 'trs-billing-customer-email' );
				self::setBillingAddressLine1( 'trs-billing-customer-address_line_1' );
				self::setBillingAddressLine2( 'trs-billing-customer-address_line_2' );
				self::setBillingCity( 'trs-billing-customer-city' );
				self::setBillingState( 'trs-billing-customer-state' );
				self::setBillingZipCode( 'trs-billing-customer-zipcode' );
				self::setBillingPhone( 'trs-billing-customer-phone' );
				self::setBillingCountry( 'trs-billing-customer-country' );
				self::setBillingAddress( 'trs-billing-customer-address' );
				self::setBillingComment( 'trs-billing-customer-comment' );
				self::setBillingCompanyName( 'trs-billing-customer-companyname' );
				
				
				self::setShippingFirstName( 'trs-shipping-customer-firstname' );
				self::setShippingLastName( 'trs-shipping-customer-lastname' );
				self::setShippingEmail( 'trs-shipping-customer-email' );
				self::setShippingAddressLine1( 'trs-shipping-customer-address_line_1' );
				self::setShippingAddressLine2( 'trs-shipping-customer-address_line_2' );
				self::setShippingCity( 'trs-shipping-customer-city' );
				self::setShippingState( 'trs-shipping-customer-state' );
				self::setShippingZipCode( 'trs-shipping-customer-zipcode' );
				self::setShippingPhone( 'trs-shipping-customer-phone' );
				self::setShippingCountry( 'trs-shipping-customer-country' );
				self::setShippingAddress( 'trs-shipping-customer-address' );
				self::setShippingComment( 'trs-shipping-customer-comment' );
				self::setShippingCompanyName( 'trs-shipping-customer-companyname' );
				
				self::setSpecification( 'trs-product-specification' );
				self::setSize( 'trs-product-size' );
				self::setStock( 'trs-product-stock' );
				self::setPrinting( 'trs-product-printing' );
				self::setFinishing( 'trs-product-finishing' );
				
				self::setProductName( 'trs-product-listing' );
				self::setProductQuantity( 'trs-product-quantity' );
				self::setProductPrice( 'trs-product-price' );
				
				self::setOutputHtml( 'trs-invoice-output-html' );
				self::setProductInfoArr( 'trs-invoice-product-info-arr' );
				
				self::setProductNonceAction('trs-invoice-nonce-action-777');
				self::setProductNonceName('trs-invoice-nonce-name-777');
				
				self::setPostIdEncFieldSlug('trs-invoice-encoded_id');
				self::setPostIdEncSectionSlug('trs-invoice-encoded_id_section');
				
				self::$isReplacementOn = FrontendInvoiceSystem::isReplaceEmptyValue() ;
				
				
				add_action( 'admin_enqueue_scripts', [ $this, 'register_scripts' ], 8 );
				add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
				
				add_action(
					'add_meta_boxes_' . parent::getInvoicePostSlug(),
					[ $this, 'customer_billing_info' ]
				);
				
				add_action(
					'add_meta_boxes_' . parent::getInvoicePostSlug(),
					[ $this, 'customer_shipping_info' ]
				);
				
				add_action(
					'add_meta_boxes_' . parent::getInvoicePostSlug(),
					[ $this, 'products_information' ]
				);
				
				add_action(
					'add_meta_boxes_' . parent::getInvoicePostSlug(),
					[ $this, 'invoice_result_output' ]
				);
				
				add_action('wp_ajax_trs_invoice_get_product_name', [$this, 'trs_invoice_get_product_name_callback']);
				add_action('wp_ajax_nopriv_trs_invoice_get_product_name', [$this, 'trs_invoice_get_product_name_callback']);
				
			}
			
			protected function get_input_name( $section_name, $input_name ) {
				
				return $section_name . '[' . $input_name . ']';
				
			}
			
			public function trs_invoice_get_product_name_callback() {
       
			    if (! isset($_POST['q'])){
			        echo json_encode('');
			        
			        wp_die();
                }
			    
			    $search_query = $_POST['q'];
			    
			    $args = [
                    'post_type'         =>  'product',
                    'post_status'       =>  'publish',
                     's' => $search_query,
                ];
				
 				$products = new \WP_Query( $args );
 				
				
				$output = [];
			    
			    if ($products->have_posts()):
                    
                    while ($products->have_posts()) : $products->the_post();
			    
			            if(has_post_thumbnail())
			                $img_src = get_the_post_thumbnail_url();
			            else // Fall back image
				            $img_src = trailingslashit(TRS_INVOICE_SYSTEM_ADMIN_ASSETS_URL) . 'images/no-image.jpg';
	
	
	                    $output[] = [
                            'title' => get_the_title(),
                            'image_src' => $img_src,
                            'id'    => get_the_ID()
                        ];
			    
			        endwhile;
           
			        
			        wp_reset_postdata();
                endif;
			     
                
                echo json_encode($output);
			    
			    
			    wp_die();
			}
			
			/*
            * @todo: Nothing todo in free version.
            * */
			/*public function inactive_plugin() {
				add_meta_box( 'trs-invoice-system-inactive-plugin', __( 'Registration unsuccess', 'TRS_INVOICE_SYSTEM_DOMAIN' ), [
					$this,
					'plugin_not_activated'
				], [],
				              'advanced',
				              apply_filters( 'trs_invoice_billing_customer_info_mb_priority', 'high' )
				);
			}
			
			public function plugin_not_activated() {
                echo '<h1>Sorry!</h1> your plugin is not activated. Please add serial key in registration section.';
                echo ' Double check key if you have inserted it already. Serial key is case sensitive.';
                echo '<p>Click here to register: <a href="' . admin_url('/edit.php?post_type='.InvoicePost::getInvoicePostSlug().'&page=trs_invoice_system_settings') . '">Register</a>';
                
			}*/
			
			public function register_scripts() {
			    wp_register_style('select2', 'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css');
				wp_register_style( 'trs-invoice-mb-css', TRS_INVOICE_SYSTEM_ADMIN_ASSETS_URL . '/css/metaboxes.css' );
				wp_register_style( 'font-awesome', 'https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css' );
				
				
				wp_register_script('select2', 'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js', ['jquery'], null, true);
				wp_register_script( 'trs-invoice-mb-js', TRS_INVOICE_SYSTEM_ADMIN_ASSETS_URL . '/js/metaboxes.js', [ 'jquery' ], null, true );
				
			}
			
			public function enqueue_scripts() {
				wp_enqueue_style( 'select2' );
				wp_enqueue_style( 'trs-invoice-mb-css' );
				wp_enqueue_style( 'font-awesome' );
				
				wp_enqueue_script('select2');
				wp_enqueue_script( 'trs-invoice-mb-js' );
				
				wp_localize_script(
				    'trs-invoice-mb-js',
                    'trs_invoice_mb_obj',
                    [
                        'currency_unit' => get_woocommerce_currency_symbol(get_woocommerce_currency()),
                        'ajax_url'  => admin_url('admin-ajax.php')
                    ]
                );
				
			}
			
			public function customer_billing_info() {
				add_meta_box(
					'trs_invoice_billing_customer_info',
					__( 'Billing Information',
					    TRS_INVOICE_SYSTEM_DOMAIN ),
					[ $this, 'render_billing_customer_info' ],
					[],
					'advanced',
					apply_filters( 'trs_invoice_billing_customer_info_mb_priority', 'high' )
				);
			}
			
			public function render_billing_customer_info( $post ) {
			    
				ob_start();
				?>

                <div class="input-wrapper">
                    <label for="<?php echo self::getBillingFirstName(); ?>">
                        <span><?php echo _e( 'First Name:', TRS_INVOICE_SYSTEM_DOMAIN ); ?></span>
                    </label>
                    <input type="text" placeholder="John"
                           name="<?php echo $this->get_input_name( self::getCustomerBillingInformationSectionSlug(), self::getBillingFirstName() ) ?>"
                           id="<?php echo self::getBillingFirstName(); ?>"
                           value="<?php echo $this->get_metafield_value($post->ID, self::getCustomerBillingInformationSectionSlug(), self::getBillingFirstName()); ?>"
                    />

                    <label for="<?php echo self::getBillingLastName(); ?>">
                        <span><?php echo _e( 'Last Name:', TRS_INVOICE_SYSTEM_DOMAIN ); ?></span>
                    </label>
                    <input type="text" placeholder="Doe"
                           name="<?php echo $this->get_input_name( self::getCustomerBillingInformationSectionSlug(), self::getBillingLastName() ) ?>"
                           id="<?php echo self::getBillingLastName(); ?>"
                           value="<?php echo $this->get_metafield_value($post->ID, self::getCustomerBillingInformationSectionSlug(), self::getBillingLastName()); ?>"
                    />
                </div>

                <div class="input-wrapper">
                    <label for="<?php echo self::getBillingCompanyName(); ?>">
                        <span><?php echo _e( 'Company Name:', TRS_INVOICE_SYSTEM_DOMAIN ); ?></span>
                    </label>
                    <input type="text" placeholder="Company ABC (PVT) LTD"
                           name="<?php echo $this->get_input_name( self::getCustomerBillingInformationSectionSlug(), self::getBillingCompanyName() ) ?>"
                           id="<?php echo self::getBillingCompanyName(); ?>"
                           value="<?php echo $this->get_metafield_value($post->ID, self::getCustomerBillingInformationSectionSlug(), self::getBillingCompanyName()); ?>"
                    />

                    <label for="<?php echo self::getBillingPhone(); ?>">
                        <span><?php echo _e('Phone: ', TRS_INVOICE_SYSTEM_DOMAIN); ?></span>
                    </label>
                    <input type="text" placeholder="+1-256-777-09"
                           name="<?php echo $this->get_input_name( self::getCustomerBillingInformationSectionSlug(), self::getBillingPhone() ) ?>"
                           id="<?php echo self::getBillingPhone(); ?>"
                           value="<?php echo $this->get_metafield_value($post->ID, self::getCustomerBillingInformationSectionSlug(), self::getBillingPhone()); ?>"
                    />
                </div>

                <div class="input-wrapper">
                    <label for="<?php echo self::getBillingAddressLine1(); ?>">
                        <span><?php echo _e( 'Address Line 1:', TRS_INVOICE_SYSTEM_DOMAIN ); ?></span>
                    </label>
                    <input type="text" placeholder="Some address street etc"
                           name="<?php echo $this->get_input_name( self::getCustomerBillingInformationSectionSlug(), self::getBillingAddressLine1() ) ?>"
                           id="<?php echo self::getBillingAddressLine1(); ?>"
                           value="<?php echo $this->get_metafield_value($post->ID, self::getCustomerBillingInformationSectionSlug(), self::getBillingAddressLine1()); ?>"
                    />

                    <label for="<?php echo self::getBillingAddressLine2(); ?>">
                        <span><?php echo _e( 'Address Line 2:', TRS_INVOICE_SYSTEM_DOMAIN ); ?></span>
                    </label>
                    <input type="text" placeholder="Some address street etc"
                           name="<?php echo $this->get_input_name( self::getCustomerBillingInformationSectionSlug(), self::getBillingAddressLine2() ) ?>"
                           id="<?php echo self::getBillingAddressLine2(); ?>"
                           value="<?php echo $this->get_metafield_value($post->ID, self::getCustomerBillingInformationSectionSlug(), self::getBillingAddressLine2()); ?>"
                    />
                </div>

                

                <div class="input-wrapper">
                    <label for="<?php echo self::getBillingCity(); ?>">
                        <span><?php echo _e( 'City:', TRS_INVOICE_SYSTEM_DOMAIN ); ?></span>
                    </label>
                    <input type="text" placeholder="Syracuse"
                           name="<?php echo $this->get_input_name( self::getCustomerBillingInformationSectionSlug(), self::getBillingCity() ) ?>"
                           id="<?php echo self::getBillingCity(); ?>"
                           value="<?php echo $this->get_metafield_value($post->ID, self::getCustomerBillingInformationSectionSlug(), self::getBillingCity()); ?>"
                    />

                    <label for="<?php echo self::getBillingState(); ?>">
                        <span><?php echo _e( 'State:', TRS_INVOICE_SYSTEM_DOMAIN ); ?></span>
                    </label>
                    <input type="text" placeholder="New York"
                           name="<?php echo $this->get_input_name( self::getCustomerBillingInformationSectionSlug(), self::getBillingState() ) ?>"
                           id="<?php echo self::getBillingState(); ?>"
                           value="<?php echo $this->get_metafield_value($post->ID, self::getCustomerBillingInformationSectionSlug(), self::getBillingState()); ?>"
                    />
                </div>

                <div class="input-wrapper">
                    <label for="<?php echo self::getBillingZipCode(); ?>">
                        <span><?php echo _e( 'Zip Code:', TRS_INVOICE_SYSTEM_DOMAIN ); ?></span>
                    </label>
                    <input type="text" placeholder="13204"
                           name="<?php echo $this->get_input_name( self::getCustomerBillingInformationSectionSlug(), self::getBillingZipCode() ) ?>"
                           id="<?php echo self::getBillingZipCode(); ?>"
                           value="<?php echo $this->get_metafield_value($post->ID, self::getCustomerBillingInformationSectionSlug(), self::getBillingZipCode()); ?>"
                    />

                    <label for="<?php echo self::getBillingCountry(); ?>">
                        <span><?php echo _e( 'Country:', TRS_INVOICE_SYSTEM_DOMAIN ); ?></span>
                    </label>
                    <input type="text" placeholder="USA"
                           name="<?php echo $this->get_input_name( self::getCustomerBillingInformationSectionSlug(), self::getBillingCountry() ) ?>"
                           id="<?php echo self::getBillingCountry(); ?>"
                           value="<?php echo $this->get_metafield_value($post->ID, self::getCustomerBillingInformationSectionSlug(), self::getBillingCountry()); ?>"
                    />
                </div>
				
				<?php
				echo ob_get_clean();
			}
			
			public function customer_shipping_info() {
				add_meta_box(
					'trs_invoice_shipping_customer_info',
					__( 'Shipping Information',
					    TRS_INVOICE_SYSTEM_DOMAIN ),
					[ $this, 'render_shipping_customer_info' ],
					[],
					'advanced',
					apply_filters( 'trs_invoice_shipping_customer_info_mb_priority', 'high' )
				);
			}
			
			public function render_shipping_customer_info($post) {
			 
				ob_start();
				?>

                <div class="input-wrapper">
                    <label for="<?php echo self::getShippingFirstName(); ?>">
                        <span><?php echo _e( 'First Name:', TRS_INVOICE_SYSTEM_DOMAIN ); ?></span>
                    </label>
                    <input type="text" placeholder="John"
                           name="<?php echo $this->get_input_name( self::getCustomerShippingInformationSectionSlug(), self::getShippingFirstName() ) ?>"
                           id="<?php echo self::getShippingFirstName(); ?>"
                           value="<?php echo $this->get_metafield_value($post->ID, self::getCustomerShippingInformationSectionSlug(), self::getShippingFirstName()); ?>"
                    />

                    <label for="<?php echo self::getShippingLastName(); ?>">
                        <span><?php echo _e( 'Last Name:', TRS_INVOICE_SYSTEM_DOMAIN ); ?></span>
                    </label>
                    <input type="text" placeholder="Doe"
                           name="<?php echo $this->get_input_name( self::getCustomerShippingInformationSectionSlug(), self::getShippingLastName() ) ?>"
                           id="<?php echo self::getShippingLastName(); ?>"
                           value="<?php echo $this->get_metafield_value($post->ID, self::getCustomerShippingInformationSectionSlug(), self::getShippingLastName()); ?>"
                    />
                </div>

                <div class="input-wrapper">
                    <label for="<?php echo self::getShippingCompanyName(); ?>">
                        <span><?php echo _e( 'Company Name:', TRS_INVOICE_SYSTEM_DOMAIN ); ?></span>
                    </label>
                    <input type="text" placeholder="Company ABC (PVT) LTD"
                           name="<?php echo $this->get_input_name( self::getCustomerShippingInformationSectionSlug(), self::getShippingCompanyName() ) ?>"
                           id="<?php echo self::getShippingCompanyName(); ?>"
                           value="<?php echo $this->get_metafield_value($post->ID, self::getCustomerShippingInformationSectionSlug(), self::getShippingCompanyName()); ?>"
                    />

                    <label for="<?php echo self::getShippingPhone(); ?>">
                        <span><?php echo _e('Phone: ', TRS_INVOICE_SYSTEM_DOMAIN); ?></span>
                    </label>
                    <input type="text" placeholder="+1-256-777-09"
                           name="<?php echo $this->get_input_name( self::getCustomerShippingInformationSectionSlug(), self::getShippingPhone() ) ?>"
                           id="<?php echo self::getShippingPhone(); ?>"
                           value="<?php echo $this->get_metafield_value($post->ID, self::getCustomerShippingInformationSectionSlug(), self::getShippingPhone()); ?>"
                    />
                </div>

                <div class="input-wrapper">
                    <label for="<?php echo self::getShippingAddressLine1(); ?>">
                        <span><?php echo _e( 'Address Line 1:', TRS_INVOICE_SYSTEM_DOMAIN ); ?></span>
                    </label>
                    <input type="text" placeholder="Some address street etc"
                           name="<?php echo $this->get_input_name( self::getCustomerShippingInformationSectionSlug(), self::getShippingAddressLine1() ) ?>"
                           id="<?php echo self::getShippingAddressLine1(); ?>"
                           value="<?php echo $this->get_metafield_value($post->ID, self::getCustomerShippingInformationSectionSlug(), self::getShippingAddressLine1()); ?>"
                    />

                    <label for="<?php echo self::getShippingAddressLine2(); ?>">
                        <span><?php echo _e( 'Address Line 2:', TRS_INVOICE_SYSTEM_DOMAIN ); ?></span>
                    </label>
                    <input type="text" placeholder="Some address street etc"
                           name="<?php echo $this->get_input_name( self::getCustomerShippingInformationSectionSlug(), self::getShippingAddressLine2() ) ?>"
                           id="<?php echo self::getShippingAddressLine2(); ?>"
                           value="<?php echo $this->get_metafield_value($post->ID, self::getCustomerShippingInformationSectionSlug(), self::getShippingAddressLine2()); ?>"
                    />
                </div>

                <div class="input-wrapper">
                    <label for="<?php echo self::getShippingCity(); ?>">
                        <span><?php echo _e( 'City:', TRS_INVOICE_SYSTEM_DOMAIN ); ?></span>
                    </label>
                    <input type="text" placeholder="Syracuse"
                           name="<?php echo $this->get_input_name( self::getCustomerShippingInformationSectionSlug(), self::getShippingCity() ) ?>"
                           id="<?php echo self::getShippingCity(); ?>"
                           value="<?php echo $this->get_metafield_value($post->ID, self::getCustomerShippingInformationSectionSlug(), self::getShippingCity()); ?>"
                    />

                    <label for="<?php echo self::getShippingstate(); ?>">
                        <span><?php echo _e( 'State:', TRS_INVOICE_SYSTEM_DOMAIN ); ?></span>
                    </label>
                    <input type="text" placeholder="New York"
                           name="<?php echo $this->get_input_name( self::getCustomerShippingInformationSectionSlug(), self::getShippingState() ) ?>"
                           id="<?php echo self::getShippingState(); ?>"
                           value="<?php echo $this->get_metafield_value($post->ID, self::getCustomerShippingInformationSectionSlug(), self::getShippingState()); ?>"
                    />
                </div>

                <div class="input-wrapper">
                    <label for="<?php echo self::getShippingZipCode(); ?>">
                        <span><?php echo _e( 'Zip Code:', TRS_INVOICE_SYSTEM_DOMAIN ); ?></span>
                    </label>
                    <input type="text" placeholder="13204"
                           name="<?php echo $this->get_input_name( self::getCustomerShippingInformationSectionSlug(), self::getShippingZipCode() ) ?>"
                           id="<?php echo self::getShippingZipCode(); ?>"
                           value="<?php echo $this->get_metafield_value($post->ID, self::getCustomerShippingInformationSectionSlug(), self::getShippingZipCode()); ?>"
                    />

                    <label for="<?php echo self::getShippingCountry(); ?>">
                        <span><?php echo _e( 'Country:', TRS_INVOICE_SYSTEM_DOMAIN ); ?></span>
                    </label>
                    <input type="text" placeholder="USA"
                           name="<?php echo $this->get_input_name( self::getCustomerShippingInformationSectionSlug(), self::getShippingCountry() ) ?>"
                           id="<?php echo self::getShippingCountry(); ?>"
                           value="<?php echo $this->get_metafield_value($post->ID, self::getCustomerShippingInformationSectionSlug(), self::getShippingCountry()); ?>"
                    />
                </div>
				
				<?php
				echo ob_get_clean();
			}
			
			public function products_information() {
				add_meta_box(
					'trs_invoice_products_ifno',
					__( 'Products Information',
					    TRS_INVOICE_SYSTEM_DOMAIN ),
					[ $this, 'render_products_info' ],
					[],
					'advanced',
					apply_filters( 'trs_invoice_products_info_mb_priority', 'high' )
				);
			}
			
			public function render_products_info() {
				ob_start();
				?>

                <div class="input-wrapper">
                    <div class="column trs_product_listing_wrapper">
                        <label for="<?php echo self::getProductName() ?>">
                            <span><?php _e( 'Choose Product', TRS_INVOICE_SYSTEM_DOMAIN ); ?></span>
                        </label>
                        <select name="<?php echo $this->get_input_name( self::getProductsInformationSectionSlug(), self::getProductName() ) ?>"
                                id="<?php echo self::getProductName() ?>">
                            <option value=""><?php _e( '--- Select Product ---', TRS_INVOICE_SYSTEM_DOMAIN ); ?></option>
		                    <?php echo $this->get_product_listing(); ?>
                        </select>
                    </div>

                    <div class="column">
                        <label for="<?php echo self::getSpecification(); ?>">
                            <span><?php echo _e( 'Specification:', TRS_INVOICE_SYSTEM_DOMAIN ); ?></span>
                        </label>
                        <input type="text" placeholder=""
                               name="<?php echo $this->get_input_name( self::getProductsInformationSectionSlug(), self::getSpecification() ) ?>"
                               id="<?php echo self::getSpecification(); ?>"/>
                    </div>
                </div>

                <div class="input-wrapper">
                    <div class="column">
                        <label for="<?php echo self::getSize(); ?>">
                            <span><?php echo _e( 'Size:', TRS_INVOICE_SYSTEM_DOMAIN ); ?></span>
                        </label>
                        <input type="text" placeholder=""
                               name="<?php echo $this->get_input_name( self::getProductsInformationSectionSlug(), self::getSize() ) ?>"
                               id="<?php echo self::getSize(); ?>"/>
                    </div>

                    <div class="column">
                        <label for="<?php echo self::getStock(); ?>">
                            <span><?php echo _e( 'Stock:', TRS_INVOICE_SYSTEM_DOMAIN ); ?></span>
                        </label>
                        <input type="text" placeholder=""
                               name="<?php echo $this->get_input_name( self::getProductsInformationSectionSlug(), self::getStock() ) ?>"
                               id="<?php echo self::getStock(); ?>"/>
                    </div>
                </div>
 

                <div class="input-wrapper">
                    <div class="column">
                        <label for="<?php echo self::getPrinting(); ?>">
                            <span><?php echo _e( 'Printing:', TRS_INVOICE_SYSTEM_DOMAIN ); ?></span>
                        </label>
                        <input type="text" placeholder=""
                               name="<?php echo $this->get_input_name( self::getProductsInformationSectionSlug(), self::getPrinting() ) ?>"
                               id="<?php echo self::getPrinting(); ?>"/>
                    </div>

                    <div class="column">
                        <label for="<?php echo self::getFinishing(); ?>">
                            <span><?php echo _e( 'Finishing:', TRS_INVOICE_SYSTEM_DOMAIN ); ?></span>
                        </label>
                        <input type="text" placeholder=""
                               name="<?php echo $this->get_input_name( self::getProductsInformationSectionSlug(), self::getFinishing() ) ?>"
                               id="<?php echo self::getFinishing(); ?>"/>
                    </div>
                </div>

                <div class="input-wrapper">
                    <div class="column">
                        <label for="<?php echo $this->get_input_name( self::getProductsInformationSectionSlug(), self::getProductQuantity() ) ?>">
                            <span><?php _e( 'Quantity', TRS_INVOICE_SYSTEM_DOMAIN ); ?></span>
                        </label>
                        <input type="number" step="1" min="1" placeholder="2"
                               name="<?php echo $this->get_input_name( self::getProductsInformationSectionSlug(), self::getProductQuantity() ) ?>"
                               id="<?php echo self::getProductQuantity() ?>">
                    </div>

                    <div class="column">
                        <label class="label-title" title="Price will be rounded to two decimal places." for="<?php echo $this->get_input_name( self::getProductsInformationSectionSlug(), self::getProductPrice() ) ?>">
                            <span><?php _e( 'Price / Item', TRS_INVOICE_SYSTEM_DOMAIN ); ?></span>
                        </label>
                        <input type="text" value=""
                               onkeypress='return (event.charCode === 46 || (event.charCode >= 48 && event.charCode <= 57))'
                               name="<?php echo $this->get_input_name( self::getProductsInformationSectionSlug(), self::getProductPrice() ) ?>"
                               id="<?php echo self::getProductPrice() ?>"
                               placeholder="99.85"
                               title="Price will be rounded."
                        />
                    </div>
                </div>

                <!-- Do not change ID from here -->
                <button id="trs_product_add_button"><?php _e( 'Add', TRS_INVOICE_SYSTEM_DOMAIN ); ?></button>
				
				<?php
				echo ob_get_clean();
			}
			
			
			public function invoice_result_output() {
				add_meta_box(
					'trs_invoice_result_output',
					__( 'Output',
					    TRS_INVOICE_SYSTEM_DOMAIN ),
					[ $this, 'invoice_result_output_render' ],
					[],
					'advanced',
					apply_filters( 'trs_invoice_result_output_mb_priority', 'high' )
				);
			}
			
			
			public function invoice_result_output_render() {
			    $json_value = $this->get_metafield_value($this->current_pid, self::getOutputSectionSlug(), self::getProductInfoArr());
			    $row_listings = json_decode($json_value);
			    
			    $style = '';
			    if (! empty($row_listings))
			        $style = 'style="display: table; "';
			     
				ob_start();
				?>
                
                <!-- Do not change ID from here -->
                <div id="trs-invoice-output" class="trs-invoice-output">
                    <table class="trs-invoice-output-table" <?=$style;?> id="trs-invoice-output-table">
                        <thead>
                        <tr>
                            <th><?php _e( 'Name', TRS_INVOICE_SYSTEM_DOMAIN ); ?></th>
                            <th><?php _e( 'Specific.', TRS_INVOICE_SYSTEM_DOMAIN ); ?></th>
                            <th><?php _e( 'Size', TRS_INVOICE_SYSTEM_DOMAIN ); ?></th>
                            <th><?php _e( 'Stock', TRS_INVOICE_SYSTEM_DOMAIN ); ?></th>
                            <th><?php _e( 'Printing', TRS_INVOICE_SYSTEM_DOMAIN ); ?></th>
                            <th><?php _e( 'Finishing', TRS_INVOICE_SYSTEM_DOMAIN ); ?></th>
                            <th><?php _e( 'QTY', TRS_INVOICE_SYSTEM_DOMAIN ); ?></th>
                            <th><?php _e( 'Price', TRS_INVOICE_SYSTEM_DOMAIN ); ?></th>
                            <th><?php _e( 'Remove', TRS_INVOICE_SYSTEM_DOMAIN ); ?></th>
                        </tr>
                        </thead>
                        <tbody id="trs-invoice-output-tbody">
                        
                            <?php
	                            if (! empty($row_listings)){
		                            foreach ($row_listings as $row):
			                            echo '<tr>';
			                            echo '<td>' . $row->name . '</td>';
			                            echo '<td>' . $row->specification . '</td>';
			                            echo '<td>' . $row->size . '</td>';
			                            echo '<td>' . $row->stock . '</td>';
			                            echo '<td>' . $row->printing . '</td>';
			                            echo '<td>' . $row->finishing . '</td>';
			                            echo '<td>' . $row->quantity . '</td>';
			                            echo '<td>' . $row->price . '</td>';
			
			                            // attach icon to delete row
			                            echo '<td><i id="trs-invoice-remove-row" title="Remove Row" class="trs-invoice-remove-row fa fa-remove"></i></td>';
			                            echo '</tr>';
		
		                            endforeach;
                                }
                            ?>

                        </tbody>
                    </table>
                    
                    <input type="hidden"
                           name="<?php echo $this->get_input_name( self::getOutputSectionSlug(), self::getProductInfoArr() ) ?>"
                           id="<?php echo self::getProductInfoArr() ?>"
                           value=""
                    />

                    <?php
                        wp_nonce_field(self::getProductNonceAction(), self::getProductNonceName());
                     ?>

                    <script>
                        var $ = jQuery;
                         $('#trs-invoice-product-info-arr').val(JSON.stringify(<?php echo $json_value; ?>));
                    </script>
                 </div>
				
				
				<?php
				echo ob_get_clean();
			}
			
			protected function get_product_listing() {
				$args = [
					'post_type'      => 'product',
					'posts_per_page' => - 1,
					'post_status'    => 'publish',
					'orderby'        => 'title',
					'order'          => 'asc'
				];
				
				$products = new \WP_Query( $args );
				
				$options = '';
				
				if ( $products->have_posts() ) :
					
					while ( $products->have_posts() ): $products->the_post();
						
						$options .= '<option data-label="' . get_the_title() . '" value="' . get_the_ID() . '">' . ucwords( get_the_title() ) . '</option>';
					
					endwhile;
				
				else:
					
					$options .= '<option value="">' . apply_filters( 'trs_no_product_found_msg', __( 'Sorry! No product found.', TRS_INVOICE_SYSTEM_DOMAIN ) ) . '</option>';
				
				
				endif;
				
				wp_reset_postdata();
				
				return $options;
			}
			
			public static function get_meta_boxes_registered_sections(){
			    return $registered_sections = [
			        self::getCustomerBillingInformationSectionSlug(),
                    self::getCustomerShippingInformationSectionSlug(),
                    self::getOutputSectionSlug(),
                    self::getPostIdEncSectionSlug()
                ];
            }
            
            public function get_metafield_value($post_id, $section_slug, $field_name){
	           
			    // if NULL the data array, means it first time.
			    if (! $this->getMetaDataArr() )
	                $this->updateMetaDataArr($post_id);
			    
			    // return value
                if (!empty($dataArr = $this->getMetaDataArr())){
	                if (isset($dataArr[$section_slug], $dataArr[$section_slug][$field_name]))
		                if (self::$isReplacementOn && empty($dataArr[$section_slug][$field_name]))
			                return FrontendInvoiceSystem::getSpecialWord() ;
		
		                return $dataArr[$section_slug][$field_name];
                }
                
                return false; // House Keeping
            }
			
			public function get_metafield_sections($post_id, $section_slug){
				
				// if NULL the data array, means it first time.
				if (! $this->getMetaDataArr() )
					$this->updateMetaDataArr($post_id);
    
				// MetaData array is set So, return value if found, else return false.
				if (!empty($dataArr = $this->getMetaDataArr())){
					if (isset($dataArr[$section_slug]))
						return $dataArr[$section_slug];
				}
				
				return false; // House Keeping
			}
			
			private function updateMetaDataArr($post_id) {
                $this->current_pid = $post_id;
                
				$trs_invoice_metas = maybe_unserialize(get_post_meta($post_id, (new SaveInvoiceMetaBoxes)->getMetaKeySlug(), true));
				$registered_sections = self::get_meta_boxes_registered_sections();
				$data = [];
				
				// if invoice metadata is empty, then set metadata as empty
				if (empty($trs_invoice_metas)){
					$this->setMetaDataArr($data);
					return;
                }
                
                // invoice metadata has value so set meta data variable.
                foreach ( $trs_invoice_metas as $section => $trs_invoice_meta ) {
                    if (! in_array($section, $registered_sections))
                        continue;
    
                    $data[$section] = $trs_invoice_meta;
                }
				$this->setMetaDataArr($data);
            }
			
			/**
			 * @return mixed
			 */
			public static function getCustomerBillingInformationSectionSlug() {
				return self::$customer_billing_information_section_slug;
			}
			
			/**
			 * @param mixed $customer_billing_information_section_slug
			 */
			protected static function setCustomerBillingInformationSectionSlug( $customer_billing_information_section_slug ) {
				self::$customer_billing_information_section_slug = $customer_billing_information_section_slug;
			}
			
			/**
			 * @return mixed
			 */
			public static function getCustomerShippingInformationSectionSlug() {
				return self::$customer_shipping_information_section_slug;
			}
			
			/**
			 * @param mixed $customer_shipping_information_section_slug
			 */
			protected static function setCustomerShippingInformationSectionSlug( $customer_shipping_information_section_slug ) {
				self::$customer_shipping_information_section_slug = $customer_shipping_information_section_slug;
			}
			
			
			/**
			 * @return mixed
			 */
			public static function getProductsInformationSectionSlug() {
				return self::$products_information_section_slug;
			}
			
			/**
			 * @param mixed $products_information_section_slug
			 */
			protected static function setProductsInformationSectionSlug( $products_information_section_slug ) {
				self::$products_information_section_slug = $products_information_section_slug;
			}
			
			/**
			 * @return mixed
			 */
			public static function getOutputSectionSlug() {
				return self::$output_section_slug;
			}
			
			/**
			 * @param mixed $output_section_slug
			 */
			protected static function setOutputSectionSlug( $output_section_slug ) {
				self::$output_section_slug = $output_section_slug;
			}
			
			/**
			 * @return mixed
			 */
			public static function getBillingFirstName() {
			    if (self::$isReplacementOn)
			    
				return self::$_billing_first_name;
			}
			
			/**
			 * @param mixed $billing_first_name
			 */
			protected static function setBillingFirstName( $billing_first_name ) {
				self::$_billing_first_name = $billing_first_name;
			}
			
			/**
			 * @return mixed
			 */
			public static function getBillingLastName() {
				return self::$_billing_last_name;
			}
			
			/**
			 * @param mixed $billing_last_name
			 */
			protected static function setBillingLastName( $billing_last_name ) {
				self::$_billing_last_name = $billing_last_name;
			}
			
			/**
			 * @return mixed
			 */
			public static function getBillingEmail() {
				return self::$_billing_email;
			}
			
			/**
			 * @param mixed $billing_email
			 */
			protected static function setBillingEmail( $billing_email ) {
				self::$_billing_email = $billing_email;
			}
			
			/**
			 * @return mixed
			 */
			public static function getBillingAddressLine1() {
				return self::$_billing_address_line_1;
			}
			
			/**
			 * @param mixed $billing_address_line_1
			 */
			protected static function setBillingAddressLine1( $billing_address_line_1 ) {
				self::$_billing_address_line_1 = $billing_address_line_1;
			}
			
			/**
			 * @return mixed
			 */
			public static function getBillingAddressLine2() {
				return self::$_billing_address_line_2;
			}
			
			/**
			 * @param mixed $billing_address_line_2
			 */
			protected static function setBillingAddressLine2( $billing_address_line_2 ) {
				self::$_billing_address_line_2 = $billing_address_line_2;
			}
			
			/**
			 * @return mixed
			 */
			public static function getBillingCity() {
				return self::$_billing_city;
			}
			
			/**
			 * @param mixed $billing_city
			 */
			protected static function setBillingCity( $billing_city ) {
				self::$_billing_city = $billing_city;
			}
			
			/**
			 * @return mixed
			 */
			public static function getBillingState() {
				return self::$_billing_state;
			}
			
			/**
			 * @param mixed $billing_state
			 */
			protected static function setBillingState( $billing_state ) {
				self::$_billing_state = $billing_state;
			}
			
			/**
			 * @return mixed
			 */
			public static function getBillingZipCode() {
				return self::$_billing_zip_code;
			}
			
			/**
			 * @param mixed $billing_zip_code
			 */
			protected static function setBillingZipCode( $billing_zip_code ) {
				self::$_billing_zip_code = $billing_zip_code;
			}
			
			/**
			 * @return mixed
			 */
			public static function getBillingPhone() {
				return self::$_billing_phone;
			}
			
			/**
			 * @param mixed $billing_phone
			 */
			protected static function setBillingPhone( $billing_phone ) {
				self::$_billing_phone = $billing_phone;
			}
			
			/**
			 * @return mixed
			 */
			public static function getBillingCountry() {
				return self::$_billing_country;
			}
			
			/**
			 * @param mixed $billing_country
			 */
			protected static function setBillingCountry( $billing_country ) {
				self::$_billing_country = $billing_country;
			}
			
			/**
			 * @return mixed
			 */
			public static function getBillingAddress() {
				return self::$_billing_address;
			}
			
			/**
			 * @param mixed $billing_address
			 */
			protected static function setBillingAddress( $billing_address ) {
				self::$_billing_address = $billing_address;
			}
			
			/**
			 * @return mixed
			 */
			public static function getBillingComment() {
				return self::$_billing_comment;
			}
			
			/**
			 * @param mixed $billing_comment
			 */
			protected static function setBillingComment( $billing_comment ) {
				self::$_billing_comment = $billing_comment;
			}
			
			/**
			 * @return mixed
			 */
			public static function getShippingFirstName() {
				return self::$_shipping_first_name;
			}
			
			/**
			 * @return mixed
			 */
			public static function getBillingCompanyName() {
				return self::$_billing_companyName;
			}
			
			/**
			 * @param mixed $billing_companyName
			 */
			protected static function setBillingCompanyName( $billing_companyName ) {
				self::$_billing_companyName = $billing_companyName;
			}
			
			/**
			 * @param mixed $shipping_first_name
			 */
			protected static function setShippingFirstName( $shipping_first_name ) {
				self::$_shipping_first_name = $shipping_first_name;
			}
			
			/**
			 * @return mixed
			 */
			public static function getShippingCompanyName() {
				return self::$_shipping_companyName;
			}
			
			/**
			 * @param mixed $shipping_companyName
			 */
			protected static function setShippingCompanyName( $shipping_companyName ) {
				self::$_shipping_companyName = $shipping_companyName;
			}
			
			/**
			 * @return mixed
			 */
			public static function getShippingLastName() {
				return self::$_shipping_last_name;
			}
			
			/**
			 * @param mixed $shipping_last_name
			 */
			protected static function setShippingLastName( $shipping_last_name ) {
				self::$_shipping_last_name = $shipping_last_name;
			}
			
			/**
			 * @return mixed
			 */
			public static function getShippingEmail() {
				return self::$_shipping_email;
			}
			
			/**
			 * @param mixed $shipping_email
			 */
			protected static function setShippingEmail( $shipping_email ) {
				self::$_shipping_email = $shipping_email;
			}
			
			/**
			 * @return mixed
			 */
			public static function getShippingAddressLine1() {
				return self::$_shipping_address_line_1;
			}
			
			/**
			 * @param mixed $shipping_address_line_1
			 */
			protected static function setShippingAddressLine1( $shipping_address_line_1 ) {
				self::$_shipping_address_line_1 = $shipping_address_line_1;
			}
			
			/**
			 * @return mixed
			 */
			public static function getShippingAddressLine2() {
				return self::$_shipping_address_line_2;
			}
			
			/**
			 * @param mixed $shipping_address_line_2
			 */
			protected static function setShippingAddressLine2( $shipping_address_line_2 ) {
				self::$_shipping_address_line_2 = $shipping_address_line_2;
			}
			
			/**
			 * @return mixed
			 */
			public static function getShippingCity() {
				return self::$_shipping_city;
			}
			
			/**
			 * @param mixed $shipping_city
			 */
			protected static function setShippingCity( $shipping_city ) {
				self::$_shipping_city = $shipping_city;
			}
			
			/**
			 * @return mixed
			 */
			public static function getShippingState() {
				return self::$_shipping_state;
			}
			
			/**
			 * @param mixed $shipping_state
			 */
			protected static function setShippingState( $shipping_state ) {
				self::$_shipping_state = $shipping_state;
			}
			
			/**
			 * @return mixed
			 */
			public static function getShippingZipCode() {
				return self::$_shipping_zip_code;
			}
			
			/**
			 * @param mixed $shipping_zip_code
			 */
			protected static function setShippingZipCode( $shipping_zip_code ) {
				self::$_shipping_zip_code = $shipping_zip_code;
			}
			
			/**
			 * @return mixed
			 */
			public static function getShippingPhone() {
				return self::$_shipping_phone;
			}
			
			/**
			 * @param mixed $shipping_phone
			 */
			protected static function setShippingPhone( $shipping_phone ) {
				self::$_shipping_phone = $shipping_phone;
			}
			
			/**
			 * @return mixed
			 */
			public static function getShippingCountry() {
				return self::$_shipping_country;
			}
			
			/**
			 * @param mixed $shipping_country
			 */
			protected static function setShippingCountry( $shipping_country ) {
				self::$_shipping_country = $shipping_country;
			}
			
			/**
			 * @return mixed
			 */
			public static function getShippingAddress() {
				return self::$_shipping_address;
			}
			
			/**
			 * @param mixed $shipping_address
			 */
			protected static function setShippingAddress( $shipping_address ) {
				self::$_shipping_address = $shipping_address;
			}
			
			/**
			 * @return mixed
			 */
			public static function getShippingComment() {
				return self::$_shipping_comment;
			}
			
			/**
			 * @param mixed $shipping_comment
			 */
			protected static function setShippingComment( $shipping_comment ) {
				self::$_shipping_comment = $shipping_comment;
			}
			
			/**
			 * @return mixed
			 */
			public static function getProductName() {
				return self::$product_name;
			}
			
			/**
			 * @param mixed $product_name
			 */
			protected static function setProductName( $product_name ) {
				self::$product_name = $product_name;
			}
			
			/**
			 * @return mixed
			 */
			public static function getProductQuantity() {
				return self::$product_quantity;
			}
			
			/**
			 * @param mixed $product_quantity
			 */
			protected static function setProductQuantity( $product_quantity ) {
				self::$product_quantity = $product_quantity;
			}
			
			/**
			 * @return mixed
			 */
			public static function getProductPrice() {
				return self::$product_price;
			}
			
			/**
			 * @param mixed $product_price
			 */
			protected static function setProductPrice( $product_price ) {
				self::$product_price = $product_price;
			}
			
			
			
			/**
			 * @return mixed
			 */
			public static function getOutputHtml() {
				return self::$output_html;
			}
			
			/**
			 * @param mixed $output_html
			 */
			protected static function setOutputHtml( $output_html ) {
				self::$output_html = $output_html;
			}
			
			/**
			 * @return mixed
			 */
			public static function getSpecification() {
				return self::$specification;
			}
			
			/**
			 * @param mixed $specification
			 */
			protected static function setSpecification( $specification ) {
				self::$specification = $specification;
			}
			
			/**
			 * @return mixed
			 */
			public static function getSize() {
				return self::$size;
			}
			
			/**
			 * @param mixed $size
			 */
			protected static function setSize( $size ) {
				self::$size = $size;
			}
			
			/**
			 * @return mixed
			 */
			public static function getStock() {
				return self::$stock;
			}
			
			/**
			 * @param mixed $stock
			 */
			protected static function setStock( $stock ) {
				self::$stock = $stock;
			}
			
			/**
			 * @return mixed
			 */
			public static function getPrinting() {
				return self::$printing;
			}
			
			/**
			 * @param mixed $printing
			 */
			protected static function setPrinting( $printing ) {
				self::$printing = $printing;
			}
			
			/**
			 * @return mixed
			 */
			public static function getFinishing() {
				return self::$finishing;
			}
			
			/**
			 * @param mixed $finishing
			 */
			protected static function setFinishing( $finishing ) {
				self::$finishing = $finishing;
			}
			
			/**
			 * @return mixed
			 */
			public static function getProductInfoArr() {
				return self::$product_info_arr;
			}
			
			/**
			 * @param mixed $product_info_arr
			 */
			protected static function setProductInfoArr( $product_info_arr ) {
				self::$product_info_arr = $product_info_arr;
			}
			
			/**
			 * @return mixed
			 */
			public static function getProductNonceName() {
				return self::$product_nonce_name;
			}
			
			/**
			 * @param mixed $product_nonce_name
			 */
			protected static function setProductNonceName( $product_nonce_name ) {
				self::$product_nonce_name = $product_nonce_name;
			}
			
			/**
			 * @return mixed
			 */
			public static function getProductNonceAction() {
				return self::$product_nonce_action;
			}
			
			/**
			 * @param mixed $product_nonce_action
			 */
			protected static function setProductNonceAction( $product_nonce_action ) {
				self::$product_nonce_action = $product_nonce_action;
			}
			
			/**
			 * @return mixed
			 */
			public function getMetaDataArr() {
				return $this->meta_data_arr;
			}
			
			/**
			 * @param mixed $meta_data_arr
			 */
			protected function setMetaDataArr( $meta_data_arr ) {
				$this->meta_data_arr = $meta_data_arr;
			}
			
			/**
			 * @return mixed
			 */
			public static function getPostIdEncFieldSlug() {
				return self::$post_id_enc_field_slug;
			}
			
			/**
			 * @param mixed $post_id_enc_field_slug
			 */
			protected static function setPostIdEncFieldSlug( $post_id_enc_field_slug ) {
				self::$post_id_enc_field_slug = $post_id_enc_field_slug;
			}
			
			/**
			 * @return mixed
			 */
			public static function getPostIdEncSectionSlug() {
				return self::$post_id_enc_section_slug;
			}
			
			/**
			 * @param mixed $post_id_enc_section_slug
			 */
			protected static function setPostIdEncSectionSlug( $post_id_enc_section_slug ) {
				self::$post_id_enc_section_slug = $post_id_enc_section_slug;
			}
		}
	}