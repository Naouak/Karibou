<?php

class SetUserState extends FormModel {
	public function build() {
		if (isset($_POST["userState"])) {
			$sql = "INSERT INTO usermood(user_id, message, mood) VALUES(:userId, :userState, :userMood) ON DUPLICATE KEY UPDATE message=:userState, mood=:userMood";
			try {
				$stmt = $this->db->prepare($sql);
				$stmt->bindValue("userId", $this->currentUser->getID());
				$stmt->bindValue("userState", substr(filter_input(INPUT_POST, "userState", FILTER_SANITIZE_STRING), 0, 64));
				$stmt->bindValue("userMood", $_POST["userMood"]);
				$stmt->execute();
			} catch(PDOException $e) {
				Debug::kill($e->getMessage());
			}
		}
	}
}

?>
