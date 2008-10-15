<?php
/* BEWARE made by Naouak*/ 
 
/**
 * Classe resetbuttonstats
 *
 * @package applications
 */

class resetbuttonmystats extends Model
{	
	private function longtime(){
		$stmt = $this->db->prepare(" SELECT TIMEDIFF( cut.date , frst.date) as hour
					FROM resetbutton AS frst, resetbutton AS cut
					WHERE cut.id = frst.id + 1 AND cut.user = :user
					ORDER BY hour DESC
					LIMIT 1");
		$stmt->bindValue(':user',$this->currentUser->getID(),PDO::PARAM_INT);
		$stmt->execute();
		$temp = $stmt->fetch();
		$this->assign("longestresethour",$temp['hour']);
	}
	
	private function reseter(){
		$stmt = $this->db->prepare(" SELECT COUNT( id ) AS compte
				FROM resetbutton
				WHERE user = :user
				LIMIT 1 ");
		$stmt->bindValue(':user',$this->currentUser->getID(),PDO::PARAM_INT);		
		$stmt->execute();
		$result = $stmt->fetch();
		$this->assign("myresetcount", $result['compte']);
	}
	
	private function stolenpoints(){
				 $stmt = $this->db->prepare("
				 SELECT cutter, COALESCE(hours,0) as Score, date FROM
(SELECT -TIME_TO_SEC(TIMEDIFF( cut.date, frst.date )) AS HOURs, frst.user AS cutter, cut.date as date, cut.id as id
		FROM resetbutton AS frst LEFT JOIN resetbutton AS cut ON frst.id = cut.id-1
UNION
SELECT -TIME_TO_SEC(TIMEDIFF( frst.date,COALESCE(cut.date,NOW()) )) AS HOURs, COALESCE(cut.user,frst.user) AS cutter, cut.date as date, cut.id as id
		FROM resetbutton AS frst LEFT JOIN resetbutton AS cut ON frst.id = cut.id-1
	) as t
	WHERE cutter = :user
ORDER BY date,id DESC
");
		$stmt->bindValue(':user',$this->currentUser->getID(),PDO::PARAM_INT);
		$stmt->execute();
		$i=0;
		while($result = $stmt->fetch()){
			$profil = $this->userFactory->prepareUserFromId($result['cutter']);
			$final[$i] = array($profil,$result['Score'],$result['date']);
			$i++;
		}
		$this->assign("scorelist", $final);
	}

	public function build()
	{
		$this->longtime();
		$this->reseter();
		$this->stolenpoints();
		$this->assign("islogged", $this->currentUser->isLogged());
	}
	
}
?>