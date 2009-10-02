<?php
/* BEWARE made by Naouak*/
/* Nux also came here, it's now harmless */
/* Pinaraf came too, so it's understandable now, and not complex by design any more... */
 
/**
 * Classe resetbuttonstats
 *
 * @package applications
 */

class RBMyStats extends Model
{	
	private function longtime(){
		$stmt = $this->db->prepare("
		SELECT
			COALESCE(MAX(TIMEDIFF(cut.date , frst.date)), TIME('00:00:00')) as hour
		FROM
			resetbutton AS frst
		INNER JOIN resetbutton cut ON cut.user=:user AND cut.id=frst.id+1
		");
		$stmt->bindValue(':user',$this->currentUser->getID(),PDO::PARAM_INT);
		$stmt->execute();
		$temp = $stmt->fetch();
		$this->assign("longestresethour",$temp['hour']);
	}
	
	private function reseter(){
		$stmt = $this->db->prepare("
		SELECT
			COUNT( id ) AS compte
		FROM
			resetbutton
		WHERE
			user = :user
		");
		$stmt->bindValue(':user',$this->currentUser->getID(),PDO::PARAM_INT);
		$stmt->execute();
		$result = $stmt->fetch();
		$this->assign("myresetcount", $result['compte']);
	}
	
	private function stolenpoints(){
		$sth = $this->db->prepare("
			SELECT
				r2.user AS cutter,
				IF(r1.user=:user, TIME_TO_SEC(TIMEDIFF(r1.date, r2.date)), TIME_TO_SEC(TIMEDIFF(r2.date, r1.date))) AS scorediff,
				IF(r2.user=:user, r1.date, r2.date) AS date
			FROM
				resetbutton r1
			LEFT JOIN
				resetbutton r2 ON r2.id = r1.id+1
			WHERE
				r1.user = :user OR r2.user = :user
			ORDER BY r1.date DESC
			LIMIT 10");
		$sth->bindValue(":user", $this->currentUser->getID(), PDO::PARAM_INT);
		$sth->execute();

		$i=0;
		$final = array();
		while(($result = $sth->fetch()) !== false){
			$profil = $this->userFactory->prepareUserFromId(intval($result['cutter']));
			$final[$i++] = array($profil,$result['scorediff'],$result['date']);
		}
		$this->assign("scorelist", $final);
	}
	
	private function myscore(){
		$sth = $this->db->prepare("
		SELECT
			SUM(IF(r1.user=:user, TIME_TO_SEC(TIMEDIFF(r1.date, r2.date)), TIME_TO_SEC(TIMEDIFF(r2.date, r1.date))))
		FROM
			resetbutton r1
		LEFT JOIN
			resetbutton r2 ON r2.id = r1.id+1
		WHERE
			r1.user = :user OR r2.user = :user;
		");
		$sth->bindValue(':user', $this->currentUser->getID(), PDO::PARAM_INT);
		$sth->execute();

		$this->assign("myscore", $sth->fetchColumn(0));
	}

	public function build()
	{
		$this->longtime();
		$this->reseter();
		$this->stolenpoints();
		$this->myscore();
		$this->assign("islogged", $this->currentUser->isLogged());
	}
	
}
?>
