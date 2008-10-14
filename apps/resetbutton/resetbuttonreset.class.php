<?php
/* BEWARE made by Naouak*/ 
 
/**
 * Classe resetbuttonreset
 *
 * @package applications
 */
 
class resetbuttonreset extends Model
{	
	public function build()
	{
		//anti-flood
		$stmt = $this->db->prepare(" (
SELECT *
FROM resetbutton
WHERE user = :user
AND date > SUBTIME( NOW( ) , '1:00:00' )
)
UNION (

SELECT *
FROM (

SELECT *
FROM resetbutton
ORDER BY id DESC
LIMIT 1
) AS lastentry
WHERE lastentry.user = :user
) ");
		$stmt->bindValue(':user',$this->currentUser->getID(),PDO::PARAM_INT);
		$stmt->execute();
		if(!$stmt->fetch()){
			//ajout si passe l'anti-flood
			$stmt = $this->db->prepare("INSERT INTO resetbutton(date,user) VALUES ( NOW(), :user );");
			$stmt->bindValue(':user',$this->currentUser->getID(),PDO::PARAM_INT);
			$stmt->execute();
		}
	}
	
	
}
?>