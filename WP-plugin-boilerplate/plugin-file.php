<?php
	
	if ( ! defined( 'ABSPATH' ) ) {
		exit;
	}
	
	/**
	 * TRS Plugin Boilerplate
	 *
	 * @package     TRS_plugin_boilerplate
	 * @author      Ali Shan
	 * @copyright   2017 © TheRightSol - All rights are reserved
	 * @license     GPL-3.0+
	 *
	 * @wordpress-plugin
	 * Plugin Name: TRS Plugin Boilerplate
	 * Plugin URI:  http://therightsol.com
	 * Description: write your plugin description here.
	 * Version:     1.0.0
	 * Author:      Ali Shan
	 * Author URI:  http://alishan.therightsol.com
	 * Text Domain: therightsol
	 * License:     GPL-3.0+
	 * License URI: http://www.gnu.org/licenses/gpl-3.0.txt
	 */
	
	use app\common\TRS_Plugin_Name_Init;
	
	
	if ( file_exists( __DIR__ . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php' ) ) {
		require_once 'vendor/autoload.php';
	}
	
	
	if ( file_exists( __DIR__ . DIRECTORY_SEPARATOR . 'framework' . DIRECTORY_SEPARATOR . 'admin-folder' . DIRECTORY_SEPARATOR . 'admin' . DIRECTORY_SEPARATOR . 'admin-init.php' ) ) {
		require_once __DIR__ . DIRECTORY_SEPARATOR . 'framework' . DIRECTORY_SEPARATOR . 'admin-folder' . DIRECTORY_SEPARATOR . 'admin' . DIRECTORY_SEPARATOR . 'admin-init.php';
	}
	
	
	TRS_Plugin_Name_Init::app( __FILE__ );
	