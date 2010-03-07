<?php
class PantieSocketException extends Exception {
	public function __construct($msg) {
		parent::__construct("$msg (error: « " . socket_strerror(socket_last_error()) . " »)");
	}
}

class PantieSocketTimeout extends PantieSocketException {
}
