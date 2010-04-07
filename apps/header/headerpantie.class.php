<?php
/**
 * @copyright 2010 Rémy Sanchez <remy.sanchez@hyperthese.net>
 *
 * @license http://www.gnu.org/licenses/gpl.html GNU Public License
 * See the enclosed file COPYING for license information.
 *
 * @package applications
 **/

class HeaderPantie extends Model {
	public function build() {
		$ret = new stdClass();

		$event = filter_input(INPUT_GET, "event", FILTER_SANITIZE_STRING);
		$sid = filter_input(INPUT_GET, "session", FILTER_SANITIZE_STRING);

		// Check the rights to listen to this event
		// Format of the event: app-uid-name
		$exp = "/^(\w+)-(\d+|\*)-(\w+)$/";
		$m = preg_match($exp, $event, $matches);

		// The event has either to be for this specific user, either a broadcast
		if($m and ($matches[2] == $this->currentUser->getID() or $matches[2] == "*")) {
			$ret->push_url = "/push.php";

			$p = new KPantie($sid);
			$p->register($event);
		} else {
			$ret->slap = true;
		}

		return json_encode($ret);
	}
}
