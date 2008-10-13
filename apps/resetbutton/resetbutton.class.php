<?php
/* BEWARE made by Naouak*/ 
 
/**
 * Classe resetbutton
 *
 * @package applications
 */

class resetbutton extends Model
{	
	public function build()
	{
			$stmt = $this->db->prepare(" SELECT DATEDIFF(NOW(), date) as days, TIMEDIFF( NOW() , date) as hour, user
					FROM resetbutton
					ORDER BY date DESC
					LIMIT 1 ");
			$stmt->execute();
			$temp = $stmt->fetch();
			
			$this->assign("islogged", $this->currentUser->isLogged());
			$this->assign("lastresetby",$this->userFactory->prepareUserFromId($temp['user']));
			$this->assign("resetdays",$temp['days']);
			$this->assign("resethour",$temp['hour']);
	}
	
	
}
?>