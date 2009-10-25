<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of GeolocationDaemon
 *
 * @author remy
 */
class GeolocationDaemon extends Listener {
	function eventOccured(Event $event) {
		GeolocationFactory::initialize($this->db, $this->userFactory);
	}
}

