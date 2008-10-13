<?php
/* BEWARE made by Naouak*/ 
 
/**
 * Classe resetbutton
 *
 * @package applications
 */

class resetbutton extends Model
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
		if(isset($_POST['reset'])){
			$this->db->exec("INSERT INTO resetbutton(date) VALUES ( NOW() );");
		}
		$stmt = $this->db->prepare(" SELECT DATEDIFF(NOW(), date) as days, TIMEDIFF( NOW() , date) as hour
				FROM resetbutton
				ORDER BY date DESC
				LIMIT 1 ");
		$stmt->execute();
		$temp = $stmt->fetch();
		$time = $temp['days']." "._("DAYS")." ".$temp['hour'];
		$this->assign("resettime",$time);
		
	}
	
	
}
?>