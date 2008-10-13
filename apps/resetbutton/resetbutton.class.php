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
		if(isset($_POST['reset'])){
			$this->db->exec("INSERT INTO resetbutton(date) VALUES ( NOW() );");
		}
		else{
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
	
	
}
?>