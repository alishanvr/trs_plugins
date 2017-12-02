<?php
	
	// if uninstall.php is not called by WordPress, die
	if (!defined('WP_UNINSTALL_PLUGIN')) exit;
	
	
	$option_name = '';
	delete_option($option_name);
	
	
	// for site options in Multisite
	delete_site_option($option_name);
	
	// drop a custom database table
	global $wpdb;
	
	$c_tables = [
		'table_name',
	];
	
	foreach ($c_tables as $table) {
		$wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}$table");
	}