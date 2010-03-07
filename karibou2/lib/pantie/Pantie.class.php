<?php
class Pantie {
	private $socket, $session;

	public function __construct($session = null, $host = "localhost", $port = 5896) {
		$this->socket = new PantieSocket($host, $port);

		if($session === null) {
			$this->session = $this->makeSessionKey();
		} else {
			$this->session = $session;
		}
	}

	public function makeSessionKey() {
		return md5(uniqid(microtime(true)));
	}

	public function waitEvent($events) {
		if(!is_array($events)) {
			$events = array($events);
		}

		$data = new stdClass();

		$data->do = "grab";
		$data->what = $events;
		$data->by = $this->session;

		$this->socket->connect();
		$this->socket->sendString(json_encode($data));

		$reply = json_decode($this->socket->readString());

		$evts = array();

		foreach($reply->drawer as $r) {
			$evts[] = new PantieEvent($r->color, $r->pattern);
		}

		return $evts;
	}

	public function throwEvent($name, $cnt, $for = null) {
		$data = new stdClass();
		$data->do = "wear";
		$data->what = $name;
		$data->how = $cnt;
		
		if($for !== null) $data->for = $for;

		$this->socket->connect();
		$this->socket->sendString(json_encode($data));
	}

	public function register($event) {
		$data = new stdClass();
		$data->do = "register";
		$data->who = $this->session;
		$data->for = $event;

		$this->socket->connect();
		$this->socket->sendString(json_encode($data));
	}
}
