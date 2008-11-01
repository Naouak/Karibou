<?php
/* BEWARE made by Naouak*/
/* Nux also came here, it's now harmless */
/* Pinaraf came too, so it's understandable now, and not complex by design any more... */
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
		$sth = $this->db->prepare("
		SELECT
			two.user AS user,
			SEC_TO_TIME(SUM(TIME_TO_SEC(TIMEDIFF(two.date, one.date)))) AS compte
		FROM
			resetbutton AS one
		INNER JOIN resetbutton two ON two.id = one.id + 1
		GROUP BY
			user
		ORDER BY
			compte DESC
		LIMIT
			10
		");

		$sth->execute();
		$i=0;
		$final = array();
		while(($result = $sth->fetch()) !== false){
			$profil = $this->userFactory->prepareUserFromId($result['user']);
			$final[$i++] = array($profil,$result['compte']);
		}
		$this->assign("timecountlist", $final);
	}
	
	private function stolenpoints(){
		// This one deserves a special mention for being really dirty...
		// I prefer not risk rewriting it in a mysql-incompatible way....
		$sth = $this->db->prepare("
		SELECT
			user,
			SUM(score) AS score
		FROM
			(
				SELECT * FROM (
					SELECT
						SUM(TIME_TO_SEC(TIMEDIFF(one1.date, two1.date))) AS score,
						one1.user AS user
					FROM
						resetbutton AS two1
					LEFT JOIN resetbutton AS one1 ON one1.id = two1.id - 1
					GROUP BY
						user
				) AS DerivedTable1
				UNION ALL
				SELECT * FROM (
					SELECT
						SUM(TIME_TO_SEC(TIMEDIFF(two2.date, one2.date))) AS score,
						two2.user AS user
					FROM
						resetbutton AS two2
					LEFT JOIN resetbutton AS one2 ON one2.id = two2.id - 1
					GROUP BY
						user
				) AS DerivedTable2
			) AS MainTable
		GROUP BY
			user
		ORDER BY
			score DESC
		LIMIT 10
		");

		$sth->execute();
		$i=0;
		$final = array();
		while(($result = $sth->fetch()) !== false){
			$profil = $this->userFactory->prepareUserFromId($result['user']);
			$final[$i++] = array($profil,$result['score']);
		}
		$this->assign("scorelist", $final);
	}

	public function build()
	{
		$this->longtime();
		$this->reseters();
		$this->timecount();
		$this->stolenpoints();
		$this->assign("islogged", $this->currentUser->isLogged());
	}
	
}
?>