<?php
	
	namespace app\common;
	
	if (!defined('ABSPATH'))
		exit;
	
	//@todo: update class and file name.
	if (!class_exists('TRS_Plugin_Name_Init')) {
		class TRS_Plugin_Name_Init
		{
			
			private static $pluginpath;
 		
			public function __construct ()
			{
				// DO NOT DELETE THESE
				add_action('init', [$this, 'constants'], 5);
				add_action('init', [$this, 'inc_debug_abstract_class'], 5);
				add_action('init', [$this, 'inc_traits'], 5);
				
				// insert other classes here using init hook
				
			}
			
			
			/*
			 * @todo: Update plugin name in all below strings "__PLUGIN_NAME__"
			 *
			 * @todo: search all entries that have "__PLUGIN_NAME__" - Update according to your plugin name.
			 *
			 *
			 * */
			public function constants ()
			{
				define('TRS__PLUGIN_NAME__PLUGIN_FILE_PATH', self::getPluginpath());
				
				define('TRS__PLUGIN_NAME__PLUGIN_PATH', dirname(self::getPluginpath()));
				define('TRS__PLUGIN_NAME__PLUGIN_URL', plugins_url('', self::getPluginpath()));
				
				define('TRS__PLUGIN_NAME__FRONTEND_PATH', TRS__PLUGIN_NAME__PLUGIN_FILE_PATH . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'frontend');
				define('TRS__PLUGIN_NAME__FRONTEND_URL', trailingslashit(TRS__PLUGIN_NAME__PLUGIN_URL) . 'app/frontend');
				
				define('TRS__PLUGIN_NAME__BACKEND_PATH', TRS__PLUGIN_NAME__PLUGIN_PATH . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'backend');
				define('TRS__PLUGIN_NAME__BACKEND_URL', trailingslashit(TRS__PLUGIN_NAME__PLUGIN_URL) . 'app/backend');
				
				define('TRS__PLUGIN_NAME__DEBUGGING_PATH', TRS__PLUGIN_NAME__PLUGIN_PATH . DIRECTORY_SEPARATOR . 'debugging');
				define('TRS__PLUGIN_NAME__DEBUGGING_URL', trailingslashit(TRS__PLUGIN_NAME__PLUGIN_URL) . 'debugging');
				
				define('TRS__PLUGIN_NAME__ABSTRACT_PATH', TRS__PLUGIN_NAME__PLUGIN_PATH . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'abstracts');
				define('TRS__PLUGIN_NAME__ABSTRACT_URL', trailingslashit(TRS__PLUGIN_NAME__PLUGIN_URL) . 'app/abstracts');
				
				define('TRS__PLUGIN_NAME__TRAITS_PATH', TRS__PLUGIN_NAME__PLUGIN_PATH . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'traits');
				define('TRS__PLUGIN_NAME__TRAITS_URL', trailingslashit(TRS__PLUGIN_NAME__PLUGIN_URL) . 'app/traits');
				
				
				define('TRS__PLUGIN_NAME__VERSION', '1.0.0');
				define('TRS__PLUGIN_NAME__MIN_PHP_VER', '5.6');
				define('TRS__PLUGIN_NAME__TEXT_DOMAIN', 'therightsol');
			}
			
			
			public function inc_debug_abstract_class ()
			{
				if (file_exists(TRS__PLUGIN_NAME__ABSTRACT_PATH . DIRECTORY_SEPARATOR . 'TRS_Exception_Handler.php'))
					require_once TRS__PLUGIN_NAME__ABSTRACT_PATH . DIRECTORY_SEPARATOR . 'TRS_Exception_Handler.php';
			}
			
			public function inc_traits ()
			{
				if (file_exists(TRS__PLUGIN_NAME__TRAITS_PATH . DIRECTORY_SEPARATOR . 'TRS_Queries.php'))
					require_once TRS__PLUGIN_NAME__TRAITS_PATH . DIRECTORY_SEPARATOR . 'TRS_Queries.php';
			}
			
			public static function plugin_activated ()
			{
				/*
				 * DO NOT DELETE BELOW CHECKS.
				 *
				 * */
				
				if ( version_compare( get_bloginfo('version'), '4.6', '<') )  {
					$message = "Sorry! Impossible to activate plugin. <br />";
					$message .= "This Plugin requires at least WP Version 4.9";
					die( $message );
				}
				
				if (version_compare(PHP_VERSION, '5.6', '<')){
					$message = "Sorry! Impossible to activate plugin. <br />";
					$message .= "This Plugin requires minimum PHP Version 5.6.0";
					die( $message );
				}
				
				
				
				
				
				/*
				 * DO WHAT YOU WANT ON PLUGIN ACTIVATION.
				 *
				 * */
				
				
			}
			
			
			
			public static function plugin_deactivated ()
			{
				
				/*
				 * DO WHAT YOU WANT ON PLUGIN DEACTIVATION.
				 *
				 * */
				
			}
			
			public static function app ($filepath)
			{
				register_activation_hook($filepath, [TRS_Plugin_Name_Init::class, 'plugin_activated']);
				register_deactivation_hook($filepath, [TRS_Plugin_Name_Init::class, 'plugin_deactivated']);
				
				
				$obj = new self;
				$obj::setPluginpath($filepath);
				return $obj;
			}
			
			
			
			
			/*---------------------------- Setters and Getters ------------------------------------------------------*/
			
			/**
			 * @return mixed
			 */
			public static function getPluginpath ()
			{
				return self::$pluginpath;
			}
			
			/**
			 * @param mixed $pluginpath
			 */
			protected static function setPluginpath ($pluginpath)
			{
				self::$pluginpath = $pluginpath;
			}
			
		}
	}