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
	//@todo: always update option name for each plugin.
    $opt_name = "redux_trs_plugin_name";

    /**
     * ---> SET ARGUMENTS
     * All the possible arguments for Redux.
     * For full documentation on arguments, please refer to: https://github.com/ReduxFramework/ReduxFramework/wiki/Arguments
     * */

    //$theme = wp_get_theme(); // For use with some settings. Not necessary.

    $args = array(
        'opt_name' => $opt_name,
        'use_cdn' => TRUE,
        'display_name' => 'TRS Settings',
        'display_version' => FALSE,
        'page_slug' => 'trs-plugin-settings',   // @todo: Also update the page slug.
        'page_title' => 'Settings',
        'update_notice' => FALSE,
        'admin_bar' => FALSE,
        'menu_type' => 'submenu',
        'menu_title' => 'Settings',
        'allow_sub_menu' => TRUE,
        'page_parent' => 'edit.php?post_type=', // @todo: add post_type OR page Slug accordingly
        'page_parent_post_type' => 'edit.php?post_type=', // // @todo: Page Parent -- add post_type OR page Slug accordingly
        'customizer' => TRUE,
        'default_mark' => '*',
        'hints' => array(
            'icon' => 'el el-adjust-alt',
            'icon_position' => 'left',
            'icon_color' => 'lightgray',
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
        'output' => TRUE,
        'output_tag' => TRUE,
        'settings_api' => TRUE,
        'cdn_check_time' => '1440',
        'compiler' => TRUE,
        'page_permissions' => 'manage_options',
        'save_defaults' => TRUE,
        'show_import_export' => TRUE,
        'database' => 'options',
        'transient_time' => '3600',
        'network_sites' => TRUE,
    );

    // SOCIAL ICONS -> Setup custom links in the footer for quick links in your panel footer icons.
    $args['share_icons'][] = array(
        'url'   => 'https://www.facebook.com/therightsol',
        'title' => 'Like us on Facebook',
        'icon'  => 'el el-facebook'
    );
    $args['share_icons'][] = array(
        'url'   => 'https://twitter.com/therightsol',
        'title' => 'Follow us on Twitter',
        'icon'  => 'el el-twitter'
    );
    $args['share_icons'][] = array(
        'url'   => 'https://www.linkedin.com/company/10364234/',
        'title' => 'Find us on LinkedIn',
        'icon'  => 'el el-linkedin'
    );

    Redux::setArgs( $opt_name, $args );

    /*
     * ---> END ARGUMENTS
     */

    /*
     * ---> START HELP TABS
     */
     

    /*
     *
     * ---> START SECTIONS
     *
     */
     

    Redux::setSection( $opt_name, array(
		'title'  => __( 'Debugging', 'redux-framework-demo' ),
		'id'     => 'debugging-main-section',
		'desc'   => __( 'Debugging main section.', 'redux-framework-demo' ),
		'icon'   => 'el el-wrench',
    ) );

    Redux::setSection( $opt_name, array(
		'title'  => __( 'Turn On Debugging', 'redux-framework-demo' ),
		'id'     => 'debugging-inner-section',
		'desc'   => __( 'Select which type of debugging you want to enable.', 'redux-framework-demo' ),
        'subsection' => true,
		'fields' => array(
			array(
				'id'       => 'debugging_checkboxes',
				'type'     => 'radio',
				'title'    => __('Select which type of debugging you want to enable', 'redux-framework-demo'),
				
				//Must provide key => value pairs for multi checkbox options
				'options'  => array(
					'read-queries' => 'Only on READ Queries',
					'write-queries' => 'Only on WRITE Queries',
					'delete-queries' => 'Only on Delete Queries',
					'update-queries'	=> "Only on UPDATE Queries",
					'all-queries'	=> "On All Queries",
					'disable-all'	=> "Disable All Debugging"
				),
				
				//See how default has changed? you also don't need to specify opts that are 0.
				'default' => 'read-queries'
			),
			array(
				'id'       => 'debugging_file_path',
				'type'     => 'text',
				'title'    => __('Debugging File Path', 'redux-framework-demo'),
				'description'	=> '<a target="_blank" href="'.wp_slash(plugin_dir_url(dirname(dirname(__DIR__)))).'debugging/read-log.php?sc=ML6%9t{6W^$2dsaR3/">Click here to read file</a>',
				'default'	=> dirname(dirname(__DIR__)) . DIRECTORY_SEPARATOR . 'logs.html',
				'readonly'	=> true
			)
		)
    ) );

    /*
     * <--- END SECTIONS
     */
