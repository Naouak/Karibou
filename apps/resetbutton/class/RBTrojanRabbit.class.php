<?php
/**
 * A class to make strings generated during the template
 * rendering, when users data are present.
 *
 * @author Rémy Sanchez <remy.sanchez@hyperthese.net>
 * @copyright Copright © 2009 Rémy Sanchez
 * @license GNU Public License v2
 */

// Fetchez la vache !
class RBTrojanRabbit {
	private $lastClick, $user, $showpicture, $applist;

	public function __construct($lastClick, $user, $showpicture, $applist) {
		$this->user = $user;
		$this->showpicture = $showpicture;
		$this->lastClick = $lastClick;
		$this->applist = $applist;
	}

	public function __toString() {
		return json_encode(array(
			"lastClick" => $this->lastClick,
			"userlink" => userlink(
				array(
					"user" => $this->user,
					"showpicture" => $this->showpicture
				),
				$this->applist
			)
		));
	}
}
