<?php
	
	if ( ! defined( 'ABSPATH' ) ) {
		exit;
	}
	
	/**
	 * TRS Invoice System
	 *
	 * @package     TRS_Invoice_System
	 * @author      Ali Shan
	 * @copyright   2017 © TheRightSol - All rights are reserved
	 * @license     GPL-2.0+
	 *
	 * @wordpress-plugin
	 * Plugin Name: TRS Invoice System
	 * Plugin URI:  http://therightsol.com
	 * Description: Invoice System for woocommerce
	 * Version:     1.0.0
	 * Author:      Ali Shan
	 * Author URI:  http://therightsol.com
	 * Text Domain: therightsol
	 * License:     GPL-2.0+
	 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
	 */
	
	use CommonInvoiceSystem\InvoiceSystem;
	
	require_once 'bootstrap.php';
	new InvoiceSystem( __FILE__ );