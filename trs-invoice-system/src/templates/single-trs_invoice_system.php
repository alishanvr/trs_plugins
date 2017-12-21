<?php 
	if (! defined('ABSPATH') ) exit;
	
	
	// Redirect if request is for PAYMENT
	require_once 'includes/is-payment-request.php';

	// move further if not payment request.
	get_header();
	
	global $post;
	
	$db_enc_id = ((new \AdminInvoiceSystem\InvoiceMetaBoxes)->get_metafield_value($post->ID,\AdminInvoiceSystem\InvoiceMetaBoxes::getPostIdEncSectionSlug(), \AdminInvoiceSystem\InvoiceMetaBoxes::getPostIdEncFieldSlug()));
	
	if (isset($_GET['id']) && esc_attr($_GET['id']) === $db_enc_id){
		require_once ('includes/single-invoice.php');
	}
	else
		require_once ('includes/id-not-found.php');
	
	get_footer();