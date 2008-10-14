<?php
/* BEWARE made by Naouak*/ 
 
/**
 * Classe resetbuttonstats
 *
 * @package applications
 */

class resetbuttonstats extends Model
{	
	private function longtime(){
		$stmt = $this->db->prepare(" SELECT TIMEDIFF( cut.date , frst.date) as hour, cut.user as cutter
					FROM resetbutton AS frst, resetbutton AS cut
					WHERE cut.id = frst.id + 1
					ORDER BY hour DESC
					LIMIT 1");
		$stmt->execute();
		$temp = $stmt->fetch();
		$this->assign("longestcutter",$this->userFactory->prepareUserFromId($temp['cutter']));
		$this->assign("longestresethour",$temp['hour']);
	}
	
	private function reseters(){
		$stmt = $this->db->prepare(" SELECT user, COUNT( id ) AS compte
				FROM resetbutton
				GROUP BY user
				HAVING user <> 0
				ORDER BY COUNT( id ) DESC
				LIMIT 100 ");
		$stmt->execute();
		$i=0;
		while($result = $stmt->fetch()){
			$profil = $this->userFactory->prepareUserFromId($result['user']);
			$final[$i] = array($profil,$result['compte']);
			$i++;
		}
		$this->assign("reseterlist", $final);
	}
	
	private function timecount(){
		 $stmt = $this->db->prepare(" SELECT timed.cutter AS user, CONCAT( FLOOR( SUM( TIME_TO_SEC( timed.hours ) ) /3600 ) , ':', FLOOR( MOD( SUM( TIME_TO_SEC( timed.hours ) ) , 3600 ) /60 ) , ':', MOD( SUM( TIME_TO_SEC( timed.hours ) ) , 60 ) ) AS compte
FROM (

SELECT TIMEDIFF( cut.date, frst.date ) AS HOURs, cut.user AS cutter
FROM resetbutton AS frst, resetbutton AS cut
WHERE cut.id = frst.id +1
ORDER BY HOURs DESC
) AS timed
GROUP BY cutter
HAVING cutter <>0
ORDER BY compte DESC LIMIT 100");
		$stmt->execute();
		$i=0;
		while($result = $stmt->fetch()){
			$profil = $this->userFactory->prepareUserFromId($result['user']);
			$final[$i] = array($profil,$result['compte']);
			$i++;
		}
		$this->assign("timecountlist", $final);
	}
	
	private function score(){
		 $stmt = $this->db->prepare(" SELECT timed.cutter AS user, FLOOR(SUM( LN(TIME_TO_SEC( timed.hours )) )) AS compte
FROM (

SELECT TIMEDIFF( cut.date, frst.date ) AS HOURs, cut.user AS cutter
FROM resetbutton AS frst, resetbutton AS cut
WHERE cut.id = frst.id +1
ORDER BY HOURs DESC
) AS timed
GROUP BY cutter
HAVING cutter <>0
ORDER BY compte DESC LIMIT 100");
		$stmt->execute();
		$i=0;
		while($result = $stmt->fetch()){
			$profil = $this->userFactory->prepareUserFromId($result['user']);
			$final[$i] = array($profil,$result['compte']);
			$i++;
		}
		$this->assign("scorelist", $final);
	}

	public function build()
	{
		$this->longtime();
		$this->reseters();
		$this->timecount();
		$this->score();
		$this->assign("islogged", $this->currentUser->isLogged());
	}
	
}
?>