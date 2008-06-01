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
	private $stack = array();
	
	/**
	 * Connection to the database
	 *
	 * @var PDO
	**/
	private $db;
	
	/**
	 * The current user
	 *
	 * @var CurrentUser
	**/
	private $user;
	
	/**
	 * Constructor
	 *
	 * Sets this class as the error handler
	 */
	public function  __construct(PDO $db, CurrentUser $user) {
		$this->db = $db;
		$this->user = $user;
		
		// We set this class as the new error handler
		set_error_handler(array($this, "newError"));
	}
	
	/**
	 * Destructor
	 *
	 * Trigger the flush() function
	 */
	public function __destruct() {
		$this->flush();
	}
	
	/**
	 * Error handler
	 *
	 * @see http://fr.php.net/set_error_handler
	 */
	public function newError($errno, $errstr, $errfile, $errline) {
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
	public function flush() {
		if(count($this->stack) > 0) {
			$query = $this->db->prepare("INSERT INTO ".$GLOBALS['config']['bdd']["frameworkdb"].".logs (date, messages, user) VALUES (NOW(), :messages, :userId)");
			try {
				$query->bindValue(":userId", $this->user->getID());
				$query->execute(array(
					"messages" => implode("\n", $this->stack)
				));
				$this->stack = array();
			} catch (Exception $ex) {
			}
		}
	}
}
?>
