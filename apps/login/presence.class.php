<?php

class Presence extends Model {

	public function build () {
		if ($this->currentUser->isLogged()) {
			$uid = $this->currentUser->getID();
			// ALTER TABLE `onlineusers` ADD `last_presence` DATETIME NULL AFTER `timestamp` ;
			$rqt = $this->db->prepare("UPDATE onlineusers SET last_presence = NOW() WHERE user_id=:uid");
			$rqt->bindValue(":uid", $uid);
			$rqt->execute();
		}
		return "";
	}

}

?>
