<?php
class PantieEvent {
	var $name, $data;

	public function __construct($name, $data) {
		$this->name = $name;
		$this->data = $data;
	}
}
