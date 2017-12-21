<?php
	/**
	 * Created by PhpStorm.
	 * User: Ali Shan
	 * Date: 04-Sep-17
	 * Time: 6:00 PM
	 */
	
	namespace AdminInvoiceSystem;
	
	
	use CommonInvoiceSystem\InvoiceSystem;
	
	if ( ! defined( 'ABSPATH' ) ) {
		exit;
	}
	
	if ( ! class_exists( 'InvoicePost' ) ) {
		class InvoicePost {
			public static $invoice_post_slug;
			
			public function __construct() {
				self::setInvoicePostSlug( 'trs_invoice_system' );
				
				add_action( 'init', [ $this, 'register_invoice_post' ], 6 );
				
				if (!InvoiceSystem::getIsActive())
					return;
				
				add_action( 'init', [ $this, 'invoice_post_taxonomies' ], 6 );
				
				// Custom Columns
				add_filter( 'manage_' . self::getInvoicePostSlug() . '_posts_columns', [
					$this,
					'set_custom_columns'
				] );
				add_action( 'manage_' . self::getInvoicePostSlug() . '_posts_custom_column', [
					$this,
					'custom_columns'
				], 10, 2 );
				
				add_filter( 'archive_template', [ $this, 'load_invoice_system_template' ] );
				
				add_filter( 'post_type_link', [ $this, 'update_invoice_view_link' ], 10, 2 );
				add_filter( 'post_row_actions', [$this, 'update_row_actions'], 10, 2 );
				
			}
			
			/**
			 * @return mixed
			 */
			public static function getInvoicePostSlug() {
				return self::$invoice_post_slug;
			}
			
			/**
			 * @param mixed $invoice_post_slug
			 */
			protected static function setInvoicePostSlug( $invoice_post_slug ) {
				self::$invoice_post_slug = $invoice_post_slug;
			}
			
			public function load_invoice_system_template( $archive_template ) {
				global $post;
				
				$filename = 'archive-' . self::getInvoicePostSlug() . '.php';
				
				if ( is_post_type_archive( self::getInvoicePostSlug() ) && ! empty( locate_template( $filename ) ) ) {
					$archive_template = locate_template( $filename );
				} else {
					$archive_template = trailingslashit( TRS_INVOICE_SYSTEM_TEMPLATES_PATH ) . $filename;
				}
				
				
				return $archive_template;
			}
			
			
			public function register_invoice_post() {
				$labels = [
					'name'                  => _x( 'Invoices', 'Post Type General Name', 'trs_invoice_system' ),
					'singular_name'         => _x( 'Invoice', 'Post Type Singular Name', 'trs_invoice_system' ),
					'menu_name'             => __( 'Invoices', 'trs_invoice_system' ),
					'name_admin_bar'        => __( 'Invoices', 'trs_invoice_system' ),
					'archives'              => __( 'Invoice Archives', 'trs_invoice_system' ),
					'attributes'            => __( 'Invoice Attributes', 'trs_invoice_system' ),
					'parent_item_colon'     => __( 'Parent Invoice:', 'trs_invoice_system' ),
					'all_items'             => __( 'All Invoices', 'trs_invoice_system' ),
					'add_new_item'          => __( 'Add New Invoice', 'trs_invoice_system' ),
					'add_new'               => __( 'Add New', 'trs_invoice_system' ),
					'new_item'              => __( 'New Invoice', 'trs_invoice_system' ),
					'edit_item'             => __( 'Edit Invoice', 'trs_invoice_system' ),
					'update_item'           => __( 'Update Invoice', 'trs_invoice_system' ),
					'view_item'             => __( 'View Invoice', 'trs_invoice_system' ),
					'view_items'            => __( 'View Invoice', 'trs_invoice_system' ),
					'search_items'          => __( 'Search Invoice', 'trs_invoice_system' ),
					'not_found'             => __( 'Not found', 'trs_invoice_system' ),
					'not_found_in_trash'    => __( 'Not found in Trash', 'trs_invoice_system' ),
					'featured_image'        => __( 'Featured Image', 'trs_invoice_system' ),
					'set_featured_image'    => __( 'Set featured image', 'trs_invoice_system' ),
					'remove_featured_image' => __( 'Remove featured image', 'trs_invoice_system' ),
					'use_featured_image'    => __( 'Use as featured image', 'trs_invoice_system' ),
					'insert_into_item'      => __( 'Insert into Invoice', 'trs_invoice_system' ),
					'uploaded_to_this_item' => __( 'Uploaded to this Invoice', 'trs_invoice_system' ),
					'items_list'            => __( 'Items list', 'trs_invoice_system' ),
					'items_list_navigation' => __( 'Items list navigation', 'trs_invoice_system' ),
					'filter_items_list'     => __( 'Filter items list', 'trs_invoice_system' ),
				];
				$args   = [
					'label'       => __( 'Invoice', 'trs_invoice_system' ),
					'description' => __( 'Generate Invoices', 'trs_invoice_system' ),
					'labels'      => $labels,
					'supports'    => [ 'title', 'author', ],
					
					'hierarchical'        => false,
					'public'              => true,
					'show_ui'             => true,
					'show_in_menu'        => true,
					'menu_position'       => 20,
					'menu_icon'           => 'dashicons-media-text',
					'show_in_admin_bar'   => true,
					'show_in_nav_menus'   => true,
					'can_export'          => true,
					'has_archive'         => true,
					'exclude_from_search' => false,
					'publicly_queryable'  => true,
					'capability_type'     => 'page',
				];
				register_post_type( self::getInvoicePostSlug(), $args );
			}
			
			public function invoice_post_taxonomies() {
				register_taxonomy(
					'invoice_categories',
					self::getInvoicePostSlug(),
					[
						'hierarchical' => true,
						'label'        => 'Invoice Types',
						'query_var'    => true,
						'rewrite'      => true
					]
				);
				
				$labels = [
					'name'                       => _x( 'Invoice Tags', 'taxonomy general name' ),
					'singular_name'              => _x( 'Tag', 'taxonomy singular name' ),
					'search_items'               => __( 'Search Tags' ),
					'popular_items'              => __( 'Popular Tags' ),
					'all_items'                  => __( 'All Tags' ),
					'parent_item'                => null,
					'parent_item_colon'          => null,
					'edit_item'                  => __( 'Edit Tag' ),
					'update_item'                => __( 'Update Tag' ),
					'add_new_item'               => __( 'Add New Tag' ),
					'new_item_name'              => __( 'New Tag Name' ),
					'separate_items_with_commas' => __( 'Separate tags with commas' ),
					'add_or_remove_items'        => __( 'Add or remove tags' ),
					'choose_from_most_used'      => __( 'Choose from the most used tags' ),
					'menu_name'                  => __( 'Invoice Tags' ),
				];
				
				register_taxonomy(
					'invoice_tags',
					self::getInvoicePostSlug(),
					[
						'hierarchical'          => false,
						'labels'                => $labels,
						'show_ui'               => true,
						'update_count_callback' => '_update_post_term_count',
						'query_var'             => true,
						'rewrite'               => [ 'slug' => 'invoice-tags' ],
					]
				);
			}
			
			public function update_invoice_view_link( $permalink, $post ) {
				if ( $post->post_type !== self::getInvoicePostSlug() || ! current_user_can( 'manage_options' ) ) {
					return $permalink;
				}

				// if string already has id then.
				$exploded_permalink = explode('?id', $permalink);
				if (is_array($exploded_permalink) && sizeof($exploded_permalink) > 1){
					return $permalink;
				}
				
				
				// if string have not id attribute
				$permalink = untrailingslashit( $permalink ) . '?id=' . get_invoice_enc_id( $post->ID );
				
				return $permalink;
			}
			
			
			public function set_custom_columns( $columns ) {
				
				return [
					'cb'             => '<input type="checkbox" />',
					'title'          => __( 'Title', TRS_INVOICE_SYSTEM_DOMAIN ),
					'invoice_url'    => __( 'URL', TRS_INVOICE_SYSTEM_DOMAIN ),
					'invoice_status' => __( 'Status', TRS_INVOICE_SYSTEM_DOMAIN ),
					'invoice_type'   => __( 'Type', TRS_INVOICE_SYSTEM_DOMAIN ),
					'invoice_tags'   => __( 'Tags', TRS_INVOICE_SYSTEM_DOMAIN ),
					'date'           => __( 'Date', TRS_INVOICE_SYSTEM_DOMAIN ),
				];
			}
			
			public function custom_columns( $column, $post_id ) {
				switch ( $column ) {
					case 'invoice_url':
						echo '<input onClick="this.select();" type="text" class="invoice_url_input" readonly value="' . untrailingslashit( get_post_permalink( $post_id ) ) . '" />';
						break;
					// @todo: set below columns
					case 'invoice_status':
						$status = is_invoice_paid( $post_id );
						if ( $status ) {
							echo '<i title="paid" class="fa fa-check"></i>';
						} else {
							echo '<i title="unpaid" class="fa fa-close"></i>';
						}
						break;
					
					case 'invoice_type':
						$terms = get_the_term_list( $post_id, 'invoice_categories', '', ',', '' );
						if ( is_string( $terms ) ) {
							echo $terms;
						}
						break;
					
					case 'invoice_tags':
						$terms = get_the_term_list( $post_id, 'invoice_tags', '', ',', '' );
						if ( is_string( $terms ) ) {
							echo $terms;
						}
						break;
				}
			}
			
			public function update_row_actions( $actions, $post ) {
				
				if (strpos($actions['view'], __('Preview', TRS_INVOICE_SYSTEM_DOMAIN)))
					unset($actions['view']);
				
				return $actions;
			}
		}
	}