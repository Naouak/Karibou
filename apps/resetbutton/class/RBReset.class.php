<?php
/* BEWARE made by Naouak*/
/* Nux also came here, it's now harmless */
/* Pinaraf came too, so it's understandable now, and not complex by design any more... */
 
/**
 * Classe resetbuttonreset
 *
 * @package applications
 */
 
class RBReset extends FormModel
{	
	public function build()
	{
		// Si l'utilisateur n'est pas loggé, il n'a pas le droit de remettre le bouton à 0
		if(!$this->currentUser->isLogged()) return;
	
		//anti-flood
		$stmt = $this->db->prepare("
		SELECT *
		FROM resetbutton
		WHERE 
			user=:user
			AND (
				date > SUBTIME(NOW(), '1:00:00')
				OR
				id = (SELECT MAX(id) FROM resetbutton)
			)");
		$stmt->bindValue(':user',$this->currentUser->getID(),PDO::PARAM_INT);
		$stmt->execute();
		if($stmt->fetch() === false){
			// Score calculation
			$stmt = $this->db->prepare("
				SELECT
					user,
					TIME_TO_SEC(TIMEDIFF(NOW(), date)) AS score
				FROM
					resetbutton
				ORDER BY
					date DESC
				LIMIT
					1
			");
			$stmt->execute();

			if($row = $stmt->fetch()) {
				ScoreFactory::stealScoreFromUser($this->currentUser, $this->userFactory->prepareUserFromId($row["user"]), $row["score"] * 10, "resetbutton");
			}

			//ajout si passe l'anti-flood
			$stmt = $this->db->prepare("INSERT INTO resetbutton(date,user) VALUES ( NOW(), :user );");
			$stmt->bindValue(':user',$this->currentUser->getID(),PDO::PARAM_INT);
			$stmt->execute();

			$p = new KPantie();
			$evt = array(
				"date" => date(DATE_RFC1123),
				"userlink" => userlink(array('noicon' => true, 'showpicture' => true, 'user' => $this->currentUser), $this->appList),
				"lastClick" => time()
			);
			$p->throwEvent("resetbutton-*-reset", json_encode($evt));
		}
	}
	
	
}

?>
