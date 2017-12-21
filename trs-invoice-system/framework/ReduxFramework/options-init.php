<?php

    /**
     * For full documentation, please visit: http://docs.reduxframework.com/
     * For a more extensive sample-config file, you may look at:
     * https://github.com/reduxframework/redux-framework/blob/master/sample/sample-config.php
     */

    if ( ! class_exists( 'Redux' ) ) {
        return;
    }
	
	
	// This is your option name where all the Redux data is stored.
	$opt_name = "trs_invoice_system_settings";
    
	
    if (! function_exists('trs_invoice_add_redux_custom_scripts')){
    	function trs_invoice_add_redux_custom_scripts(){
		    wp_register_script(
			    'redux-custom-js',
			    trailingslashit(TRS_INVOICE_SYSTEM_PLUGIN_URL) .
			    'framework/ReduxFramework/assets/js/trs-invoice-redux.js',
			    array( 'jquery' ),
			    time(),
			    true
		    );
		    wp_enqueue_script('redux-custom-js');
	    }
    }
	// This example assumes your opt_name is set to redux_demo, replace with your opt_name value
	add_action( 'redux/page/'.$opt_name.'/enqueue', 'trs_invoice_add_redux_custom_scripts' );
    
    
    
    
    
    

    

    /**
     * ---> SET ARGUMENTS
     * All the possible arguments for Redux.
     * For full documentation on arguments, please refer to: https://github.com/ReduxFramework/ReduxFramework/wiki/Arguments
     * */

    $theme = wp_get_theme(); // For use with some settings. Not necessary.

    $args = array(
        'opt_name' => 'trs_invoice_system_settings',
        'dev_mode' => FALSE,
        'use_cdn' => TRUE,
        'display_name' => 'TRS Invoice System',
        'display_version' => '1.0.0',
        'page_slug' => 'trs_invoice_system_settings',
        'page_title' => 'TRS Invoice System Settings',
        'footer_text' => 'Developed by <a href="http://www.therightsol.com" target="_blank" >TheRightSol</a>',
        'admin_bar' => TRUE,
        'menu_type' => 'submenu',
        'menu_title' => 'Settings',
        'allow_sub_menu' => TRUE,
        'page_parent' => 'edit.php?post_type=trs_invoice_system',
        'page_parent_post_type' => 'trs_invoice_system',
        'default_mark' => '*',
        'hints' => array(
            'icon_position' => 'right',
            'icon_size' => 'normal',
            'tip_style' => array(
                'color' => 'light',
            ),
            'tip_position' => array(
                'my' => 'top left',
                'at' => 'bottom right',
            ),
            'tip_effect' => array(
                'show' => array(
                    'duration' => '500',
                    'event' => 'mouseover',
                ),
                'hide' => array(
                    'duration' => '500',
                    'event' => 'mouseleave unfocus',
                ),
            ),
        ),
        'output_tag' => TRUE,
        'cdn_check_time' => '1440',
        'page_permissions' => 'manage_options',
        'save_defaults' => TRUE,
        'show_import_export' => TRUE,
        'database' => 'options',
        'transient_time' => '3600',
        'network_sites' => TRUE,
    );

    // SOCIAL ICONS -> Setup custom links in the footer for quick links in your panel footer icons.
    $args['share_icons'][] = array(
        'url'   => 'http://www.therightsol.com/',
        'title' => 'Visit us on TheRightSol',
        'icon'  => 'el el-globe'
        //'img'   => '', // You can use icon OR img. IMG needs to be a full URL.
    );
    $args['share_icons'][] = array(
        'url'   => 'https://www.facebook.com/therightsol',
        'title' => 'Like us on Facebook',
        'icon'  => 'el el-facebook'
    );
    

    Redux::setArgs( $opt_name, $args );

    /*
     * ---> END ARGUMENTS
     */

    /*
     * ---> START HELP TABS
     */

    $tabs = array(
    	// @todo: Remove these tabs or update accordingly in future.
        /*array(
            'id'      => 'redux-help-tab-1',
            'title'   => __( 'Theme Information 1', 'admin_folder' ),
            'content' => __( '<p>This is the tab content, HTML is allowed.</p>', 'admin_folder' )
        ),
        array(
            'id'      => 'redux-help-tab-2',
            'title'   => __( 'Theme Information 2', 'admin_folder' ),
            'content' => __( '<p>This is the tab content, HTML is allowed.</p>', 'admin_folder' )
        )*/
    );
    Redux::setHelpTab( $opt_name, $tabs );

    // Set the help sidebar
    $content = __( '<p>This is the sidebar content, HTML is allowed.</p>', 'admin_folder' );
    Redux::setHelpSidebar( $opt_name, $content );


    /*
     * <--- END HELP TABS
     */


    /*
     *
     * ---> START SECTIONS
     *
     */
	$options = get_option($opt_name);
	
	
	Redux::setSection($opt_name, [
		'title'  => __( 'General', 'redux-framework-demo' ),
		'id'     => 'trs-invoice-general',
		'desc'   => __( 'General Settings', 'redux-framework-demo' ),
		'icon'   => 'el el-cogs ',
	]);
	Redux::setSection( $opt_name, array(
		'title'      => __( 'Common', 'redux-framework-demo' ),
		'desc'       => __( 'Commong Settings', 'redux-framework-demo' ),
		'id'         => 'trs-invoice-common-settings-container',
		'subsection' => true,
		'fields'     => array(
			array(
				'id'       => 'trs-invoice-replace-empty-switch',
				'type'     => 'switch',
				'title'    => __( 'Replace Empty Values?', 'redux-framework-demo' ),
				'subtitle' => __( '', 'redux-framework-demo' ),
				'desc'     => __( 'Do you want to replace empty fields with some text like N/A etc', 'redux-framework-demo' ) ,
				'default'   => false,
			),
			array(
				'id'       => 'trs-invoice-replace-empty-word',
				'type'     => 'text',
				'title'    => __( 'Enter Word', 'redux-framework-demo' ),
				'subtitle' => __( '', 'redux-framework-demo' ),
				'desc'     => __( 'This will use if some field is empty ', 'redux-framework-demo' ),
				'default'     => 'N/A',
				'hidden'   => ( $options['trs-invoice-replace-empty-switch'] == 1 ) ? false : true,
			),
		)
	) );
	Redux::setSection( $opt_name, array(
		'title'      => __( 'CSS', 'redux-framework-demo' ),
		'id'         => 'trs-invoice-css-container',
		'subsection' => true,
		'fields'     => array(
			array(
				'id'       => 'trs-invoice-css-field',
				'type'     => 'ace_editor',
				'title'    => __('CSS Code', 'redux-framework-demo'),
				'subtitle' => __('Paste your CSS code here.', 'redux-framework-demo'),
				'mode'     => 'css',
				'theme'    => 'monokai',
			),
		)
	) );
    
    
    
    Redux::setSection($opt_name, [
	    'title'  => __( 'PayPal', 'redux-framework-demo' ),
	    'id'     => 'trs-paypal-settings',
	    'desc'   => __( 'PayPal setting tab.', 'redux-framework-demo' ),
	    'icon'   => 'el el-credit-card ',
    ]);
	
	
	Redux::setSection( $opt_name, array(
		'title'      => __( 'API', 'redux-framework-demo' ),
		'desc'       => __( 'Add PayPal API Credentials', 'redux-framework-demo' ),
		'id'         => 'trs-paypal-api',
		'subsection' => true,
		'fields'     => array(
			array(
				'id'       => 'trs-paypal-client-id',
				'type'     => 'text',
				'title'    => __( 'Client ID:', 'redux-framework-demo' ),
				'subtitle' => __( 'Please enter PayPal Client ID', 'redux-framework-demo' ),
				'desc'     => __( '', 'redux-framework-demo' ),
				'default'  => '',
			),
			array(
				'id'       => 'trs-paypal-client-secret',
				'type'     => 'text',
				'title'    => __( 'Client Secret:', 'redux-framework-demo' ),
				'subtitle' => __( 'Please enter PayPal Client Secret', 'redux-framework-demo' ),
				'desc'     => __( '', 'redux-framework-demo' ),
				'default'  => '',
			),
			array(
				'id'       => 'trs-paypal-is-live-credentials',
				'type'     => 'checkbox',
				'title'    => __( 'Live Mode ?', 'redux-framework-demo' ),
				'subtitle' => __( 'Please select if you are using Live Mode Credentials.', 'redux-framework-demo' ),
				'desc'     => __( 'If you have completed the testing, then you need to go lvie. Check this box if you want to go Live.', 'redux-framework-demo' ),
				'default'  => '',
			),
		)
	) );
	
	
	Redux::setSection( $opt_name, array(
		'title'      => __( 'Debugging', 'redux-framework-demo' ),
		'desc'       => __( 'Enable / Disable Debugging', 'redux-framework-demo' ),
		'id'         => 'trs-invoice-paypal-debugging-switch-container',
		'subsection' => true,
		'fields'     => array(
			array(
				'id'       => 'trs-invoice-paypal-debugging-switch',
				'type'     => 'switch',
				'title'    => __( 'Enable Debugging', 'redux-framework-demo' ),
				'subtitle' => __( '', 'redux-framework-demo' ),
				'desc'     => __( '', 'redux-framework-demo' ) ,
				'default'   => false,
			
			),
			array(
				'id'       => 'trs-invoice-paypal-debugging-file',
				'type'     => 'text',
				'title'    => __( 'Debug File', 'redux-framework-demo' ),
				'subtitle' => __( '', 'redux-framework-demo' ),
				'desc'     => __( 'Click ', 'redux-framework-demo' ) . '<a href="'.plugins_url('/debugging/log.php?key=})@JukuqEj5u[ST12(*]', dirname(__DIR__)).'" target="_blank" >Here</a> to read file',
				'default'     => dirname(dirname(__DIR__)) . DIRECTORY_SEPARATOR . 'debugging' . DIRECTORY_SEPARATOR . 'debug-file.log',
				'hidden'   => ( $options['trs-invoice-paypal-debugging-switch'] == 1 ) ? false : true,
				'readonly'  => true,
			),
		)
	) );
	
	Redux::setSection( $opt_name, array(
		'title'      => __( 'MISC', 'redux-framework-demo' ),
		'desc'       => __( '', 'redux-framework-demo' ),
		'id'         => 'trs-invoice-paypal-misc',
		'subsection' => true,
		'fields'     => array(
			array(
				'id'       => 'trs-encryption-key',
				'type'     => 'text',
				'title'    => __( 'Encryption Key:', 'redux-framework-demo' ),
				'subtitle' => __( '', 'redux-framework-demo' ),
				'desc'     => __( 'Please enter secure string. You can generate from ', 'redux-framework-demo' ) . '<a target="_blank" href="https://identitysafe.norton.com/password-generator/">Here</a>',
			
			),
		)
	) );
	
	
	/*
	 *      @todo: Nothing todo in free version.
	 *      REGISTRATION KEY - DISABLED - FOR FREE VERSION
	 *
	 * */
	/*Redux::setSection( $opt_name, array(
		'title'  => __( 'Registration', 'redux-framework-demo' ),
		'id'     => 'plugin-registration',
		'desc'   => __( 'Register TRS Invoice System', 'redux-framework-demo' ),
		'icon'   => 'el el-key',
		'fields' => array(
			array(
				'id'       => 'regkey',
				'type'     => 'text',
				'title'    => __( 'Registration Key', 'redux-framework-demo' ),
				'desc'     => __( 'Please enter registration key.', 'redux-framework-demo' ),
			)
		)
	) );*/
	
	/*
	 * <--- END SECTIONS
	 */
