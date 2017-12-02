<?php
	
	namespace app\common;
	
	use Throwable;
	
	if ( ! defined( 'ABSPATH' ) ) {
		exit;
	}
	
	if ( ! class_exists( 'TRS_Exception' ) ) {
		class TRS_Exception extends \Exception {
			
			private $severity;
			private static $file_name;
			
			const severity_arr = [
				'NORMAL',
				'HARD',
				'Debugging Only'
			];
			
			public function __construct( $message = "", $code = 0, Throwable $previous = null ) {
				parent::__construct( $message, $code, $previous );
				
				
				self::setFileName( 'logs.html' );
				
				$this->severity = 'NORMAL';
				
			}
			
			/**
			 * @return mixed
			 */
			public function getSeverity() {
				return $this->severity;
			}
			
			/**
			 * @param mixed $severity
			 */
			public function setSeverity( $severity ) {
				if ( ! in_array( $severity, $this::severity_arr ) ) {
					$severity = 'NORMAL';
				}
				
				$this->severity = $severity;
			}
			
			
			/**
			 * @param mixed $message
			 */
			public function setMessage( $message ) {
				$this->message = $message;
			}
			
			/**
			 * @param mixed $code
			 */
			public function setCode( $code ) {
				$this->code = $code;
			}
			
			/**
			 * @param mixed $file
			 */
			public function setFile( $file ) {
				$this->file = $file;
			}
			
			/**
			 * @param mixed $line
			 */
			public function setLine( $line ) {
				$this->line = $line;
			}
			
			public function save_log( $default_time_zone = 'ASIA/KARACHI' ) {
				
				$path = self::create_debug_file();
				
				date_default_timezone_set( $default_time_zone );
				
				$newData = PHP_EOL . "<br />----------------------------------------------------------------------------------<br />" . PHP_EOL;
				$newData .= PHP_EOL . "<br />Code: " . $this->getCode() . '<br />' . PHP_EOL;
				$newData .= PHP_EOL . "<br />Message: " . $this->getMessage() . '<br />' . PHP_EOL;
				$newData .= PHP_EOL . "<br />Severity: " . $this->getSeverity() . '<br />' . PHP_EOL;
				$newData .= PHP_EOL . "<br />FILE: " . $this->getFile() . '<br />' . PHP_EOL;
				$newData .= PHP_EOL . "<br />LINE #: " . $this->getLine() . '<br />' . PHP_EOL;
				$newData .= PHP_EOL . "<br />Time: " . date( 'd M Y --- h:i:s' ) . '<br />' . PHP_EOL;
				$newData .= PHP_EOL . "<br />----------------------------------------------------------------------------------<br />" . PHP_EOL;
				
				
				file_put_contents( $path, ( $newData . file_get_contents( $path ) ) );
				
			}
			
			public static function erase_file() {
				file_put_contents( self::create_debug_file(), '' );
			}
			
			private static function create_debug_file() {
				$path = TRS__PLUGIN_NAME__PLUGIN_PATH . DIRECTORY_SEPARATOR . 'debugging' . DIRECTORY_SEPARATOR . self::getFileName();
				
				if ( ! file_exists( $path ) ) {
					$handle = fopen( $path, 'w' );
					fclose( $handle );
				}
				
				return $path;
			}
			
			/*
			 * ================================================== Setters and Getters =================================================
			 * */
			
			
			/**
			 * @return mixed
			 */
			public static function getFileName() {
				return self::$file_name;
			}
			
			/**
			 * @param mixed $file_name
			 */
			public static function setFileName( $file_name ) {
				self::$file_name = $file_name;
			}
			
			
		}
	}