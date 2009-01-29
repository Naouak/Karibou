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
			//ajout si passe l'anti-flood
			$stmt = $this->db->prepare("INSERT INTO resetbutton(date,user) VALUES ( NOW(), :user );");
			$stmt->bindValue(':user',$this->currentUser->getID(),PDO::PARAM_INT);
			$stmt->execute();
		}
	}
	
	
}

?>
