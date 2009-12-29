<?php

/**
 * @copyright 2009 Grégoire Leroy <lupuscramus@gmail.com>
 *
 * See the enclosed file COPYING for license information (GPL). If you
 * did not receive this file, see http://www.gnu.org/licenses/gpl.html.
 *
 * @package applications
 **/
class BugsPostMod extends FormModel
{
	public function build() {
		$args1 = array(
			'name'=> FILTER_SANITIZE_SPECIAL_CHARS,
		);

		$args2 = array(
			'id' => FILTER_SANITIZE_NUMBER_INT,
			'developer' => array(
								'filter' => FILTER_SANITIZE_NUMBER_INT,
								'flags'  => FILTER_REQUIRE_ARRAY,
			)
		);

		$inputs1 = filter_input_array(INPUT_POST, $args1);
		$inputs2 = filter_input_array(INPUT_POST, $args2);

		//Création de module
		if($inputs2["id"] === null) {

		$sql = $this->db->prepare("
			INSERT INTO
				bugs_module
				(name)
			VALUES
				(:name)
		");
		$sql->bindValue(":name",$inputs1["name"]);

		$stmt = $this->db->prepare("
			INSERT INTO
				bugs_dev
				(user_id,module_id)
			VALUES
				(:user_id,:module_id)
		");

		try {
			$sql->execute();
			$module_id = $this->db->lastInsertId();
			
			foreach ($inputs2["developer"] as $value) {
					$stmt->bindValue("user_id",$value);
					$stmt->bindValue("module_id",$module_id);
					$stmt->execute();
			}
		} catch (PDOEXception $e) {
			$e->getMessage();
		}

		//Modification de module
		} else {
			$sql = $this->db->prepare("UPDATE bugs_module SET name=:name WHERE id=:id");
			$sql->bindValue(":name",$inputs1["name"]);
			$sql->bindValue(":id",$inputs2["id"]);

			$stmt = $this->db->prepare("
				INSERT IGNORE INTO
					bugs_dev
					(user_id,module_id)
				VALUES
					(:user_id,:module_id)
			");
			
			try {
				$sql->execute();

				foreach ($inputs2["developer"] as $value) {
					$stmt->bindValue("user_id",$value);
					$stmt->bindValue("module_id",$inputs2["id"]);
					$stmt->execute();
				}
			} catch (PDOException $e) {
				$e->getMessage();
			}
		}
	}
}