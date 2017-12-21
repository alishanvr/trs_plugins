<?php
	if ( ! defined( 'ABSPATH' ) ) {
		exit;
	}
	
	require_once 'vendor/autoload.php';
	
	if ( !class_exists( 'ReduxFramework' ) && file_exists( dirname( __FILE__ ) . '/framework/ReduxFramework/admin-init.php' ) ) {
		require_once( dirname(__FILE__) . '/framework/ReduxFramework/admin-init.php' );
	}