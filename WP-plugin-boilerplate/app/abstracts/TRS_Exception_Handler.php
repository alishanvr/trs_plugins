<?php
	
	namespace app\abstracts;
	
	
	use app\common\TRS_Exception;
	
	if ( ! defined( 'ABSPATH' ) ) {
		exit;
	}
	
	if ( ! class_exists( 'TRS_Exception_Handler' ) ) {
		abstract class TRS_Exception_Handler {
			
			public function invoke_exception( $error_code, $last_error, $last_query, $line_number, $last_results = '', $message = "Error", $severity = "HARD", $backtrace = false, $backtrace_limit = 7 ) {
				
				$msg = $message . " : " . $last_error . '<br />' . PHP_EOL . "Last Query: " . $last_query;
				
				if ( ! empty( $last_results ) ) {
					$msg .= PHP_EOL;
					$msg .= trs_debug( $last_results, false );
					$msg .= PHP_EOL;
				}
				
				if ( $backtrace ):
					
					$msg .= PHP_EOL;
					$msg .= "<hr />";
					$msg .= "<strong>";
					$msg .= "Backtrace:";
					$msg .= "</strong>";
					$msg .= "<hr />";
					$msg .= trs_debug( debug_backtrace( DEBUG_BACKTRACE_PROVIDE_OBJECT, $backtrace_limit ), false );
					$msg .= PHP_EOL;
				
				endif;
				
				
				$ex = new \app\common\TRS_Exception();
				$ex->setCode( $error_code );
				$ex->setMessage( $msg );
				$ex->setFile( __FILE__ );
				$ex->setLine( $line_number );
				$ex->setSeverity( $severity );
				$ex->save_log();
			}
			
			
			public static function debug_read_queries( $last_query, $line, $last_result, $backtrace = false, $backtrace_limit = 7 ) {
				// If read queries are ON
				if ( trs_get_redux_value( 'debugging_checkboxes', 'read-queries' ) ) {
					self::invoke_exception( 1005, '', $last_query, $line, $last_result, "Debugging", "Debugging Only", $backtrace, $backtrace_limit );
				}
			}
			
			public static function debug_write_queries( $last_query, $line, $backtrace = false, $backtrace_limit = 7 ) {
				// If write queries are ON
				if ( trs_get_redux_value( 'debugging_checkboxes', 'write-queries' ) ) {
					self::invoke_exception( 1004, '', $last_query, $line, '', "Debugging", "Debugging Only", $backtrace, $backtrace_limit );
				}
			}
			
			public static function debug_update_queries( $last_query, $line, $backtrace = false, $backtrace_limit = 7 ) {
				// If write queries are ON
				if ( trs_get_redux_value( 'debugging_checkboxes', 'update-queries' ) ) {
					self::invoke_exception( 1006, '', $last_query, $line, '', "Debugging", "Debugging Only", $backtrace, $backtrace_limit );
				}
			}
			
			public static function debug_delete_queries( $last_query, $line, $backtrace = false, $backtrace_limit = 7 ) {
				// If write queries are ON
				if ( trs_get_redux_value( 'debugging_checkboxes', 'delete-queries' ) ) {
					self::invoke_exception( 1007, '', $last_query, $line, '', "Debugging", "Debugging Only", $backtrace, $backtrace_limit );
				}
			}
			
			public static function trs_debug_forcefully( $data, $line, $erase = false ) {
				
				// erase file
				if ( $erase ) {
					TRS_Exception::erase_file();
				}
				
				self::invoke_exception( "ForceFully", '', $data, $line, '', "ForceFully", "ForceFully Only", false, 0 );
				
				
			}
		}
	}