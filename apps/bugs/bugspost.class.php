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
	
		$args = array(
			'summary' => FILTER_SANITIZE_SPECIAL_CHARS,
			'bug' => FILTER_SANITIZE_SPECIAL_CHARS,
			'browser' => FILTER_SANITIZE_SPECIAL_CHARS,
			'id' => FILTER_SANITIZE_NUMBER_INT,
			'type' => FILTER_SANITIZE_SPECIAL_CHARS,
			'state' => FILTER_SANITIZE_SPECIAL_CHARS,
			'doublon' => FILTER_SANITIZE_NUMBER_INT,
			'module' => FILTER_SANITIZE_SPECIAL_CHARS,
			'developer' => array(
								'filter' => FILTER_SANITIZE_NUMBER_INT,
								'flags'  => FILTER_REQUIRE_ARRAY,)
		);
		$inputs = filter_input_array(INPUT_POST, $args);


		if($inputs["doublon"] == 0)
			$inputs["doublon"] = null;

		// if id is null, the bug doesn't exist so we do the creation
		if($inputs["id"] === null)
		{
			if($inputs["summary"]!== null && $inputs["summary"] != "" && $inputs["bug"] !== null && $inputs["bug"] != "" && $inputs["browser"] !== null && $inputs["browser"] != "")
			{
				$stmt = $this->db->prepare("SELECT * FROM `bugs_module` WHERE name=:module ORDER BY id ASC");
				$stmt->bindValue(":module",$inputs["module"]);
				try {
					$stmt->execute();
					$module = $stmt->fetch();
					
					$state ="STANDBY";
					$sql = $this->db->prepare("
						INSERT INTO
							bugs_bugs (`summary`, `browser`, `bug`, `module_id`, `state`, `type`, `reporter_id`)
						VALUES
							(:summary, :browser, :bug, :module_id, :state, :type, :reporter)
					");
					$sql->bindValue(":bug", $inputs["bug"]);
					$sql->bindValue(":state", $state);
					$sql->bindValue(":type", $inputs["type"]);
					$sql->bindValue(":summary", $inputs["summary"]);
					$sql->bindValue(":browser", $inputs["browser"]);
					$sql->bindValue(":module_id", $module["id"], PDO::PARAM_INT);
					$sql->bindValue("reporter", $this->currentUser->getID(), PDO::PARAM_INT);

					$req = $this->db->prepare("SELECT * FROM bugs_dev WHERE module_id=:module_id");
					$req->bindValue(":module_id",$module["id"], PDO::PARAM_INT);

					$stmt2 = $this->db->prepare("
						INSERT INTO
							bugs_assign(`user_id`, `bugs_id`)
						VALUES
							(:user_id,:bugs_id)
					");

					$stmt3 = $this->db->prepare("
						INSERT INTO
							bugs_subscribe(`user_id`,`bugs_id`)
						VALUES
							(:user_id,:bugs_id)
						");


					$sql->execute();
					$bug['id'] = $this->db->lastInsertId();

					$req->execute();
					$module = $req->fetch();

					$stmt2->bindValue(":user_id",$module["user_id"], PDO::PARAM_INT);
					$stmt2->bindValue(":bugs_id", $bug["id"], PDO::PARAM_INT);

					$stmt3->bindValue(":bugs_id", $bug["id"], PDO::PARAM_INT);
					$stmt3->bindValue(":user_id",$this->currentUser->getID(), PDO::PARAM_INT);

					$stmt2->execute();
					$stmt3->execute();
					
					
				} catch (PDOException $e) {
					
					Debug::kill($e->getMessage());
				}
			}

		} else {

			
			
			$req = $this->db->prepare("INSERT IGNORE INTO `bugs_assign` (user_id,bugs_id) VALUES (:user_id, :bugs_id)");
			
			
			$stmt = $this->db->prepare("SELECT * FROM `bugs_module` WHERE name=:module ORDER BY id ASC");
			$stmt->bindValue(":module",$inputs["module"]);
			
			try {
				foreach( $inputs["developer"] as $value) {
					
					$req->bindValue(":user_id",$value);
					$req->bindValue(":bugs_id", $inputs["id"]);
					$req->execute();
					
				}
				
				$stmt->execute();
				$module = $stmt->fetch();
				$sql = $this->db->prepare("
					UPDATE
						`bugs_bugs`
					SET
						`bug` = :bug,
						`state` = :state,
						`type` = :type,
						`summary` = :summary,
						`browser` = :browser,
						`module_id` = :module_id,
						`doublon_id` = :doublon_id
					WHERE
						`id` = :id
				");

				if($inputs["doublon"] !=null)
					$inputs["state"] = "DOUBLON";
				
				$sql->bindValue(":bug", $inputs["bug"]);
				$sql->bindValue(":state", $inputs["state"]);
				$sql->bindValue(":type", $inputs["type"]);
				$sql->bindValue(":summary", $inputs["summary"]);
				$sql->bindValue(":browser", $inputs["browser"]);
				$sql->bindValue(":id", $inputs["id"]);
				$sql->bindValue(":module_id", $module['id']);
				$sql->bindValue(":doublon_id", $inputs["doublon"]);

				$sql->execute();
				
			} catch (PDOException $e) {
				Debug::kill($e->getMessage());
			}
		}
	}
}

