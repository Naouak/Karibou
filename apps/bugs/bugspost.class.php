<?php

/**
 * @copyright 2009 Grégoire Leroy <lupuscramus@gmail.com>
 *
 * See the enclosed file COPYING for license information (GPL). If you
 * did not receive this file, see http://www.gnu.org/licenses/gpl.html.
 *
 * @package applications
 **/

ClassLoader::add('ProfileFactory', dirname(__FILE__).'/../annuaire/classes/profilefactory.class.php');
ClassLoader::add('Profile', dirname(__FILE__).'/../annuaire/classes/profile.class.php');

class BugsPost extends FormModel
{
	public function build() {
		$a = $GLOBALS['config']['bdd']['frameworkdb'];
		$args1 = array(
			'summary' => FILTER_SANITIZE_SPECIAL_CHARS,
			'bug' => FILTER_SANITIZE_SPECIAL_CHARS,
			//'browser' => FILTER_SANITIZE_SPECIAL_CHARS,
			'type' => FILTER_SANITIZE_SPECIAL_CHARS,
			'state' => FILTER_SANITIZE_SPECIAL_CHARS,
			
			
		);

		$args2 = array(
			'developer' => array(
								'filter' => FILTER_SANITIZE_NUMBER_INT,
								'flags'  => FILTER_REQUIRE_ARRAY,
			)
		);
		$args3 = array(
			'id' => FILTER_SANITIZE_NUMBER_INT,
			'doublon' => FILTER_SANITIZE_NUMBER_INT,
			'module' => FILTER_SANITIZE_SPECIAL_CHARS
		);
		$inputs1 = filter_input_array(INPUT_POST, $args1);
		$inputs2 = filter_input_array(INPUT_POST, $args2);
		$inputs3 = filter_input_array(INPUT_POST, $args3);

		if($inputs3["doublon"] == 0)
			$inputs3["doublon"] = null;

		$qry = $this->db->prepare("INSERT INTO flashmail (from_user_id, to_user_id, message, date)
								VALUES (:userId, :toUserId, :message, NOW())");
		$fromUser = 1;
		$message = "Bonjour. Le bug suivant vous a été assigné : ".$inputs1["bug"];

		$qry2 = Database::instance()->prepare("SELECT login FROM ".$a.".users WHERE id=:id");
		
		$factory = new ProfileFactory($this->db, $GLOBALS['config']['bdd']["frameworkdb"].".profile");
		
		// if id is null, the bug doesn't exist so we do the creation
		if($inputs3["id"] === null)
		{
			
			if($inputs1["summary"]!== null && $inputs1["summary"] != "" && $inputs1["bug"] !== null && $inputs1["bug"] != "")//&& $inputs1["browser"] !== null && $inputs1["browser"] != "")
			{
				
				//$stmt4 = $this->db->prepare("SELECT * FROM "
				$stmt = $this->db->prepare("SELECT * FROM `bugs_module` WHERE name=:module ORDER BY id ASC");
				$stmt->bindValue(":module",$inputs3["module"]);
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
					$sql->bindValue(":bug", $inputs1["bug"]);
					$sql->bindValue(":state", $state);
					$sql->bindValue(":type", $inputs1["type"]);
					$sql->bindValue(":summary", $inputs1["summary"]);
					$sql->bindValue(":browser", $inputs1["browser"]);
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
					
					$qry->bindValue(":userId", $fromUser);
					$qry->bindValue(":toUserId", $module["user_id"]);
					$qry->bindValue(":message", $message);
					
					$qry->execute();
					$stmt3->execute();

					$subject = "BugTracker";
					
					
					$qry2->bindValue(":id", $module["user_id"]);
					$qry2->execute();
					$username = $qry2->fetch();

					if($profile = $factory->fetchFromUsername($username["login"]))
					{
						$factory->fetchEmails($profile);
						$mail = $profile->getEmails();
						
					}
					mail($mail[0]["email"], $subject, $message);

					
				} catch (PDOException $e) {
					Debug::kill($e->getMessage());
				}
			}

		} else {
			$req1 = $this->db->prepare("SELECT * FROM bugs_dev WHERE module_id=:module");
			
			$req2 = $this->db->prepare("INSERT IGNORE INTO `bugs_assign` (user_id,bugs_id) VALUES (:user_id, :bugs_id)");

			$req3 = $this->db->prepare("SELECT * FROM bugs_subscribe WHERE bugs_id=:bugs_id");

			$req4 = $this->db->prepare("SELECT * FROM bugs_assign WHERE bugs_id=:bugs_id");
			
			
			$stmt = $this->db->prepare("SELECT * FROM `bugs_module` WHERE name=:module ORDER BY id ASC");
			$stmt->bindValue(":module",$inputs3["module"]);
			
			try {
				$stmt->execute();
				$module = $stmt->fetch();
				$inputs1["module_id"] = $module["id"];
				$inputs1["doublon_id"] = $inputs3["doublon"];

				$req1->bindValue(":module", $inputs1["module_id"]);
				$req1->execute();
				$dev = $req1->fetch();

				$req4->bindValue(":bugs_id", $inputs3["id"]);
				$req4->execute();
				$assign_list = $req4->fetchAll();
				$assigned = 0;
				foreach($assign_list as $value) {
					if(in_array($dev["user_id"],$value))
						$assigned = 1;
				}
				foreach( $inputs2["developer"] as $value) {
					
					$req2->bindValue(":user_id",$value);
					$req2->bindValue(":bugs_id", $inputs3["id"]);
					$req2->execute();
					
					$qry->bindValue(":userId", $fromUser);
					$qry->bindValue(":toUserId", $value);
					$qry->bindValue(":message", $message);
					$qry->execute();

					$qry2->bindValue(":id",$value);
					$qry2->execute();
					$username = $qry2->fetch();
					if($profile = $factory->fetchFromUsername($username["login"]))
					{
						$factory->fetchEmails($profile);
						$mail = $profile->getEmails();

					}
					mail($mail[0]["email"], $subject, $message);
				}
				$req2->bindValue(":user_id", $dev["user_id"]);
				$req2->bindValue(":bugs_id", $inputs3["id"]);
				$req2->execute();
				
				if($assigned == 0) {
					$qry->bindValue(":userId", $fromUser);
					$qry->bindValue(":toUserId", $dev["user_id"]);
					$qry->bindValue(":message", $message);
					$qry->execute();
				}
				

				if($inputs3["doublon"] !=null)
					$inputs1["state"] = "DOUBLON";
				
				$sql = "UPDATE `bugs_bugs` SET ";
				$conds = array();
				foreach($inputs1 as $key => $value) {
					$conds2= array();
					if($value != null) {
						$conds2 = "$key = " . $this->db->quote($value);
						$conds[] = $conds2;
					}					
					
				}
				if(!empty($conds))
					$conds3= implode(", ", $conds);
				$sql.= $conds3;
				$sql.= " WHERE id = :id";

				$sql2 = $this->db->prepare($sql);
				
				$sql2->bindValue(":id", $inputs3["id"]);
				$sql2->execute();

				$req3->bindValue(":bugs_id",$inputs3["id"]);
				$req3->execute();

				$subscribers = $req3->fetchAll();
				
				$message = "Le bug suivant a été modifié : ".$inputs1["bug"];
				
				foreach($subscribers as $value) {
					$qry->bindValue(":userId", $fromUser);
					$qry->bindValue(":toUserId", $value["user_id"]);
					$qry->bindValue(":message", $message);
					$qry->execute();

					$qry2->bindValue(":id",$value["user_id"]);
					$qry2->execute();
					$username = $qry2->fetch();
					if($profile = $factory->fetchFromUsername($username["login"]))
					{
						$factory->fetchEmails($profile);
						$mail = $profile->getEmails();

					}
					mail($mail[0]["email"], $subject, $message);
				}
				
			} catch (PDOException $e) {
				Debug::kill($e->getMessage());
			}
		}
	}
}

