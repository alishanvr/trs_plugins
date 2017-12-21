<?php 
if (! defined('ABSPATH') ) exit;

if ('trs-invoice-system/trs-invoice-system.php' !== WP_UNINSTALL_PLUGIN)
	return;



delete_option('is_trs_invoicesystem_active');