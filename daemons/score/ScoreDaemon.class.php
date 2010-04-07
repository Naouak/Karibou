<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ScoreDaemon
 *
 * @author remy
 */
class ScoreDaemon extends Listener {
	function eventOccured(Event $event) {
		ScoreFactory::initialize($this->db, $this->userFactory, $this->appList);
	}
}
?>
