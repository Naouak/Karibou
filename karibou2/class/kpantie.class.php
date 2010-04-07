<?php
class KPantie extends Pantie {
	public function __construct($session = null) {
		global $config;
		parent::__construct($session, $config['pantie']['host'], $config['pantie']['port']);
	}
}
