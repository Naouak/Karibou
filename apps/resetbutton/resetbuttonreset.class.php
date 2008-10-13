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
			$stmt = $this->db->prepare("INSERT INTO resetbutton(date,user) VALUES ( NOW(), :user );");
			$stmt->bindValue(':user',$this->currentUser->getID(),PDO::PARAM_INT);
			$stmt->execute();
	}
	
	
}
?>