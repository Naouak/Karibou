<?php

/**
 * @copyright 2009 Grégoire Leroy <lupuscramus@gmail.com>
 *
 * See the enclosed file COPYING for license information (GPL). If you
 * did not receive this file, see http://www.gnu.org/licenses/gpl.html.
 *
 * @package applications
 **/ 

class BugsPost extends FormModel
{
	public function build() {
		$title = filter_input(INPUT_POST, "title", FILTER_SANITIZE_SPECIAL_CHARS);
		$bug = filter_input(INPUT_POST, "bug", FILTER_SANITIZE_SPECIAL_CHARS);
		$browser = filter_input(INPUT_POST, "browser", FILTER_SANITIZE_SPECIAL_CHARS);
		$id = filter_input(INPUT_POST, "id", FILTER_SANITIZE_NUMBER_INT);
		$bug_type = filter_input(INPUT_POST, "bug_type", FILTER_SANITIZE_SPECIAL_CHARS);
		$bug_state = filter_input(INPUT_POST, "bug_state", FILTER_SANITIZE_SPECIAL_CHARS);
		$doublon = filter_input(INPUT_POST, "doublon", FILTER_VALIDATE_BOOLEAN);
		$doublon_id = filter_input(INPUT_POST, "doublon_id", FILTER_SANITIZE_NUMBER_INT);

		
		// if id is null, the bug doesn't exist so we do the creation
		if($id === null)
		{
			if($title !== null && $title != "" && $bug !== null && $bug != "" && $browser !== null && $bug != "")
			{
				$state = "En attente";
				$sql = $this->db->prepare("
					INSERT INTO
						bugs (`bug`, `bug_state`, `bug_type`, `title`, `Browser`, `user_id`, `doublon`)
					VALUES
						(:bug, :bug_state, :bug_type, :title, :browser, :user_id, :doublon)
				");
				$sql->bindValue(":bug", $bug);
				$sql->bindValue(":bug_state", $state);
				$sql->bindValue(":bug_type", $bug_type);
				$sql->bindValue(":title", $title);
				$sql->bindValue(":browser", $browser);
				$sql->bindValue(":user_id", $this->currentUser->getID());
				$sql->bindValue(":doublon", 0);

				try {
					$sql->execute();
				} catch (PDOException $e) {
					Debug::kill($e->getMessage());
				}
			}

		} else {

			if($doublon == true && $doublon_id != 0 && $doublon_id != null) {
				$doublon = 1;
			} else {
				$doublon = 0;
				$doublon_id = null;
			}

			
			$sql = $this->db->prepare("
				UPDATE
					`bugs`
				SET
					`bug` = :bug,
						`bug_state` = :bug_state,
					`bug_type` = :bug_type,
					`title` = :title,
					`Browser` = :browser,
					`doublon` = :doublon,
					`doublon_id` = :doublon_id
				WHERE
					`Id` = :id
			");
			$sql->bindValue(":bug", $bug);
			$sql->bindValue(":bug_state", $bug_state);
			$sql->bindValue(":bug_type", $bug_type);
			$sql->bindValue(":title", $title);
			$sql->bindValue(":browser", $browser);
			$sql->bindValue(":id", $id);
			$sql->bindValue(":doublon", $doublon);
			$sql->bindValue(":doublon_id", $doublon_id);

			try {
				$sql->execute();
			} catch (PDOException $e) {
				Debug::kill($e->getMessage());
			}
		}
	}
}

