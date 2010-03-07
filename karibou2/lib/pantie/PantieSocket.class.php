<?php
class PantieSocket {
	private $socket;

	private $host, $port, $timeout;

	public function __construct($host, $port, $timeout = 15) {
		$this->host = $host;
		$this->port = $port;
		$this->timeout = $timeout;
	}

	public function __destruct() {
		if($this->socket !== null) {
			@socket_close();
		}
	}

	public function connect() {
		$s = @socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
		if($s === false)
			throw new PantieSocketException("Could not create the socket");

		if(!@socket_connect($s, $this->host, $this->port))
			throw new PantieSocketException("Could not connect the socket");

		if(!@socket_set_option($s, SOL_SOCKET, SO_RCVTIMEO, array('sec' => intval($this->timeout), 'usec' => 0)))
			throw new PantieSocketException("Could not set timeout of the socket");

		$this->socket = $s;
	}

	public function sendString($str) {
		$snd  = PantieToolkit::intAsString(strlen($str));
		$snd .= $str;
		$this->write($snd);
	}

	public function readString() {
		return $this->read($this->readInt());
	}

	private function write($buf) {
		if(!@socket_write($this->socket, $buf))
			throw new PantieSocketException("Could not write the socket");
	}

	public function read($len) {
		if(@socket_recv($this->socket, $buf, $len, MSG_WAITALL) != $len) {
			if(socket_last_error($this->socket) === 11) {
				throw new PantieSocketTimeout("Timeout while reading socket");
				socket_close($this->socket);
			} else {
				throw new PantieSocketException("Could not read the socket, or bad length");
			}
		}

		return $buf;
	}

	private function readInt() {
		return PantieToolkit::stringAsInt($this->read(4));
	}
}
