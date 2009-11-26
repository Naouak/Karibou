<?php

class RBState extends Model {

	public function build() {
		$stmt = $this->db->prepare("SELECT UNIX_TIMESTAMP(date) as hour, user
				FROM resetbutton
				ORDER BY date DESC
				LIMIT 1 ");
		$stmt->execute();
		$temp = $stmt->fetch();

		if($temp !== false) {
			$this->assign("json", new RBTrojanRabbit($temp["hour"], $this->userFactory->prepareUserFromId(intval($temp['user'])), $this->currentUser->isLogged(), $this->appList));
		}
	}
}
