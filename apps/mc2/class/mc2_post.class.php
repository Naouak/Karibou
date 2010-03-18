<?php
class Mc2Post extends FormModel {
	public function build() {
		$db = Database::instance();

		$msg = trim(filter_input(INPUT_POST, "msg", FILTER_SANITIZE_SPECIAL_CHARS));

		if(empty($msg)) {
			return;
		}

		// TODO: antiflood, games

		try {
			// Insert the message
			$sth = $db->prepare('INSERT INTO minichat (time, id_auteur, type, post) VALUES (NOW(), :uid, "msg", :msg)');
			$sth->bindValue(':uid', $this->currentUser->getID());
			$sth->bindValue(':msg', $msg);
			$sth->execute();

			// Notify the user of what happened
			$user = $this->userFactory->prepareUserFromId($this->currentUser->getID());
			$this->userFactory->setUserList();
			$p = new Pantie();
			$evt = array(
				'time' => time() * 1000,
				'user_id' => $this->currentUser->getID(),
				'userlink' => userlink(array('noicon' => true, 'showpicture' => true, 'user' => $user), $this->appList),
				'post' => $msg,
				'type' => 'msg'
			);
			$p->throwEvent('mc2-*-message', json_encode($evt));
		} catch(Exception $ex) {
			// Ok you're fucked
		}
	}
}
