<?php
/**
 * @copyright 2008 Rémy Sanchez <remy.sanchez@hyperthese.net>
 *
 * See the enclosed file COPYING for license information (GPL). If you
 * did not receive this file, see http://www.gnu.org/licenses/gpl.html.
 *
 * @package framework
 **/

/**
 * Handles not expected errors
 *
 * @package framework
 */
class ErrorHandler {
	/**
	 * Errors stack
	 *
	 * @var array[string]
	**/
	var $stack = array();
	
	/**
	 * Connection to the database
	 *
	 * @var PDO
	**/
	var $db;
	
	/**
	 * Constructor
	 *
	 * Sets this class as the error handler
	 */
	function __construct(PDO $db) {
		$this->db = $db;
		
		// We set this class as the new error handler
		set_error_handler(array($this, "newError"));
	}
	
	/**
	 * Destructor
	 *
	 * Trigger the flush() function
	 */
	function __destruct() {
		$this->flush();
	}
	
	/**
	 * Error handler
	 *
	 * @see http://fr.php.net/set_error_handler
	 */
	function newError($errno, $errstr, $errfile, $errline) {
		$error_titles = array(
			E_ERROR             => "Error",
			E_WARNING           => "Warning",
			E_PARSE             => "Parse Error",
			E_NOTICE            => "Notice",
			E_CORE_ERROR        => "Core Error",
			E_CORE_WARNING      => "Core Warning",
			E_COMPILE_ERROR     => "Compile Error",
			E_COMPILE_WARNING   => "Compile Warning",
			E_USER_ERROR        => "User Error",
			E_USER_WARNING      => "User Warning",
			E_USER_NOTICE       => "User Notice",
			E_STRICT            => "Strict Error",
			E_RECOVERABLE_ERROR => "Recoverable Error",
		);
		
		$fatal = E_ERROR | E_PARSE | E_CORE_ERROR | E_COMPILE_ERROR | E_USER_ERROR;
		
		if($errno & error_reporting()) {
			$this->stack[] = $error_titles[$errno] . ": $errstr @$errfile:$errline";
		}
		
		if($errno & $fatal) {
			echo "FATAL ERROR: « $errstr »";
			exit(1);
		}
	}
	
	/**
	 * Empty the stack to the database
	 */
	function flush() {
		if(count($this->stack) > 0) {
			$query = $this->db->prepare("INSERT INTO ".$GLOBALS['config']['bdd']["frameworkdb"].".logs (date, messages) VALUES (NOW(), :messages)");
			try {
				$query->execute(array(
					"messages" => implode("\n", $this->stack)
				));
			} catch (Exception $ex) {
			}
		}
	}
}
?>
