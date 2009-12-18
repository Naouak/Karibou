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

		//Récupération et filtrage de POST
		$args1 = array(
			'summary' => FILTER_SANITIZE_SPECIAL_CHARS,
			'bug' => FILTER_SANITIZE_SPECIAL_CHARS,
			//'browser' => FILTER_SANITIZE_SPECIAL_CHARS,
			'type' => FILTER_SANITIZE_SPECIAL_CHARS,
			'state' => FILTER_SANITIZE_SPECIAL_CHARS,
			'module_id' => FILTER_SANITIZE_NUMBER_INT,
			'doublon_id' => FILTER_SANITIZE_NUMBER_INT,
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

		//Message et sujet pour les mails
		$message = "Bonjour. Le bug suivant vous a été assigné : ".$inputs1["bug"];
		$subject = "BugTracker";


		//On sélectionne le développeur du module pour lui assigner le bug
		$req1 = $this->db->prepare("
			SELECT
				user_id
			FROM
				bugs_dev
			WHERE
				module_id=:module_id
		");
		$req1->bindValue(":module_id",$inputs1["module_id"]);

		$req2 = $this->db->prepare("
			INSERT IGNORE INTO
				`bugs_assign`
				(user_id,bugs_id)
			VALUES
				(:user_id, :bugs_id)
		");

		try {
			$req1->execute();
			$developer = $req1->fetch();

			// Si l'id est null, le bug n'existe pas, donc c'est une création. Sinon c'est une modification.
			if($inputs2["id"] === null)
			{
				//À la création, un bug est toujours mis en attente.
				$state ="STANDBY";

				//Requête d'ajout
				$sql = $this->db->prepare("
					INSERT INTO
						bugs_bugs
						(`summary`, `browser`, `bug`, `module_id`, `state`, `type`, `reporter_id`)
					VALUES
						(:summary, :browser, :bug, :module_id, :state, :type, :reporter)
				");
				$sql->bindValue(":bug", $inputs1["bug"]);
				$sql->bindValue(":state", $state);
				$sql->bindValue(":type", $inputs1["type"]);
				$sql->bindValue(":summary", $inputs1["summary"]);
				$sql->bindValue(":browser", $inputs1["browser"]);
				$sql->bindValue(":module_id", $inputs1["module_id"]);
				$sql->bindValue("reporter", $this->currentUser->getID());
				$sql->execute();

				//Assignation du bug au développeur
				$bug_id = $this->db->lastInsertId();
				$req2->bindValue(":user_id",$developer["user_id"]);
				$req2->bindValue(":bugs_id", $bug_id);
				$req2->execute();

				//Requête pour l'inscription de l'utilisateur qui poste le bug
				$stmt = $this->db->prepare("
					INSERT INTO
						bugs_subscribe(`user_id`,`bugs_id`)
					VALUES
						(:user_id,:bugs_id)
					");
				$stmt->bindValue(":bugs_id", $bug_id);
				$stmt->bindValue(":user_id",$this->currentUser->getID());
				$stmt->execute();

				//Notifications
				$this->flashsend($developer["user_id"],$message);
				$this->mailsend($developer["user_id"],$subject,$message);

			// Sinon, on a une modiication de bug
			} else {

				//Si le doublon est sur "Aucun", on considère qu'il n'y a pas de doublons.
				if($inputs1["doublon_id"] == 0 || $inputs1["doublon_id"] == null ) {
					$inputs1["doublon_id"] = null;
					$inputs1["state"] = "DOUBLON";;
				}
				
				//On récupère la liste des asignés.
				$req3 = $this->db->prepare("
					SELECT
						*
					FROM
						bugs_assign
					WHERE
						bugs_id=:bugs_id
				");

				$req3->bindValue(":bugs_id", $inputs2["id"]);
				$req3->execute();
				$assign_list = $req3->fetchAll();
				$assigned = 0;

				//On vérifie si le développeur du module se trouve dans la liste des assignés. S'il ne s'y trouve pas c'est qu'on vient de
				//changer de module.
				foreach($assign_list as $value) {
					if(in_array($developer["user_id"],$value))
						$assigned = 1;
				}

				//Si le développeur du module n'est pas assigné avant la modification, on lui envoie un flashmail de modification.
				if($assigned == 0)
					$this->flashsend($developer["user_id"],$message);
				
				//Tous les développeurs préalablement sélectionnés sont assignés au bug. On leur envoie également mail et flashmail.
				foreach( $inputs2["developer"] as $value) {

					$req2->bindValue(":user_id",$value);
					$req2->bindValue(":bugs_id", $inputs2["id"]);
					$req2->execute();

					$this->flashsend($value,$message);
					$this->mailsend($value,$subject,$message);
				}
				
				//On assigne le développeur du module.
				$req2->bindValue(":user_id", $developer["user_id"]);
				$req2->bindValue(":bugs_id", $inputs2["id"]);
				$req2->execute();

				//Requête pour la prise en compte des modifications : on ne modifie que les champs dont l'input n'est pas null.
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
				$sql2->bindValue(":id", $inputs2["id"]);
				$sql2->execute();

				//On sélectionne les inscrits pour leur envoyer le flashmail de modification.
				$req4 = $this->db->prepare("SELECT * FROM bugs_subscribe WHERE bugs_id=:bugs_id");
				$req4->bindValue(":bugs_id",$inputs2["id"]);
				$req4->execute();
				$subscribers = $req4->fetchAll();

				$message = "Le bug suivant a été modifié : ".$inputs1["bug"];

				foreach($subscribers as $value) {
					$this->flashsend($value["user_id"], $message);
					$this->mailsend($value["user_id"],$subject,$message);
				}
			}
		} catch (PDOException $e) {
			Debug::Kill($e->getMessage());
		}
	}

	//Fonction d'envoi de flashmails
	public function flashsend ($touserid, $message) {
		$sql = $this->db->prepare("
			INSERT INTO
				flashmail
				(from_user_id, to_user_id, message, date)
			VALUES
				(1, :toUserId, :message, NOW())
		");

		$sql->bindValue(":message", $message);
		$sql->bindValue(":toUserId", $touserid);
		$sql->execute();
	}

	//Fonction d'envoi de mail.
	public function mailsend ($touser,$subject,$message) {
		$a = $GLOBALS['config']['bdd']["frameworkdb"];

		$stmt = Database::instance()->prepare("
			SELECT
				e.email
			FROM
				".$a.".profile_email as e
			LEFT JOIN
				".$a.".users
			ON
				".$a.".users.profile_id=e.profile_id
			WHERE
				".$a.".users.id=:id
		");
		$stmt->bindValue(":id", $touser);
		$stmt->execute();
		$mail = $stmt->fetch();

		mail($mail[0]["email"], $subject, $message);
	}
}