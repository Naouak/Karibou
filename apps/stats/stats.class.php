<?php
/* BEWARE made by Naouak*/ 
 
/**
 * Classe Stats
 *
 * @package applications
 */

class Stats extends Model
{
	private function getflood(){
		$stmt = $this->db->prepare(" SELECT id_auteur AS auteur, COUNT( id ) AS compte
				FROM minichat
				GROUP BY id_auteur
				ORDER BY COUNT( id ) DESC
				LIMIT 100 ");
		$stmt->execute();
		$i=0;
		while($result = $stmt->fetch()){
			$profil = $this->userFactory->prepareUserFromId($result['auteur']);
			$final[$i] = array($profil,$result['compte']);
			$i++;
		}
		//$this->assign("islogged", $this->currentUser->isLogged());
		$this->assign("flooderlist", $final);
		
	}
	
	public function build()
	{
		$final = array();
		$this->assign("islogged", $this->currentUser->isLogged());
		$this->assign("contacts", $final);
		$this->getflood();
	}
}
?>
