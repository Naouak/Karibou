<?php
class GeolocationInfo {
	private $user, $location;

	public function setUser(User $user) {
		$this->user = $user;
	}

	public function getUser() {
		return $this->user;
	}

	public function setLocation($location) {
		$this->location = $location;
	}

	public function getLocation() {
		$g = GeolocationFactory::getInstance();
		$g->feedAll();
		return $this->location;
	}
}

