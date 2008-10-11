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
		// Create the views if needed...
		try {
			$this->db->exec("CREATE VIEW minichat_stats_temp AS (SELECT id_auteur AS auteur, post, DATE(`time`) AS msgDate FROM minichat WHERE post LIKE 'preums' OR post LIKE 'deuz' OR post LIKE 'troiz' OR post LIKE 'dernz' ORDER BY `time`)");
			$this->db->exec("CREATE VIEW minichat_preums AS (SELECT auteur, msgDate FROM minichat_stats_temp WHERE post LIKE 'preums' GROUP BY msgDate)");
			$this->db->exec("CREATE VIEW minichat_deuz AS (SELECT mt.auteur, mt.msgDate FROM minichat_stats_temp mt WHERE mt.post LIKE 'deuz' AND NOT(mt.auteur IN (SELECT mp.auteur FROM minichat_preums mp WHERE mp.msgDate = mt.msgDate)) GROUP BY mt.msgDate)");
			$this->db->exec("CREATE VIEW minichat_troiz AS (SELECT mt.auteur, mt.msgDate FROM minichat_stats_temp mt WHERE mt.post LIKE 'troiz' AND NOT(mt.auteur IN (SELECT mp.auteur FROM minichat_preums mp WHERE mp.msgDate = mt.msgDate)) AND NOT(mt.auteur IN (SELECT md.auteur FROM minichat_deuz md WHERE md.msgDate=mt.msgDate)) GROUP BY mt.msgDate);");
		} catch (PDOException $e) {
			// Just ignore, it means the view already exists...
		}

		$app = $this->appList->getApp($this->appname);
		$config = $app->getConfig();
		
		$numberofchampion = $config["numberofchampions"]["normal"];
		$preums=array("text"=>"preums","score"=>5);
		$deuz =array("text"=>"deuz","score"=>2);
		$dernz=array("text"=>"dernz","score"=>3);
		$troiz=array("text"=>"troiz","score"=>1);
		$i = 0;
		$results = array();
		
		//Récupération des preums
		$stmt = $this->db->prepare("SELECT auteur, COUNT(msgDate)*:score FROM minichat_preums GROUP BY auteur");
		
		$stmt->bindValue(':score', $preums["score"],PDO::PARAM_INT);
		$stmt->execute();
		while ($row = $stmt->fetch()) {
			$results[$row[0]] = $row[1];
		}
		
		//Récupération des deuzs
		$stmt = $this->db->prepare("SELECT auteur, COUNT(msgDate)*:score FROM minichat_deuz GROUP BY auteur");

		$stmt->bindValue(':score', $deuz["score"],PDO::PARAM_INT);
		$stmt->execute();
		
		while ($row = $stmt->fetch()) {
			if (array_key_exists($row[0], $results))
				$results[$row[0]] += $row[1];
			else
				$results[$row[0]] = $row[1];
		}
		
		//Récupération des troiz
		$stmt = $this->db->prepare("SELECT auteur, COUNT(msgDate)*:score FROM minichat_troiz GROUP BY auteur");
		
		$stmt->bindValue(':score', $troiz["score"],PDO::PARAM_INT);
		$stmt->execute();
		while ($row = $stmt->fetch()) {
			if (array_key_exists($row[0], $results))
				$results[$row[0]] += $row[1];
			else
				$results[$row[0]] = $row[1];
		}
		
		//récupération des dernz
		$stmt = $this->db->prepare("SELECT m1.id_auteur AS champion, COUNT(m1.id)*:score AS score FROM minichat m1 WHERE m1.post LIKE :dernz AND m1.id=(SELECT id FROM minichat WHERE DATE(`time`)=DATE(m1.time) ORDER BY `time` DESC LIMIT 1) GROUP BY id_auteur");

		$stmt->bindParam(':dernz', $dernz["text"]);
		$stmt->bindValue(':score', $dernz["score"],PDO::PARAM_INT);
		$stmt->execute();
		while ($row = $stmt->fetch()) {
			if (array_key_exists($row[0], $results))
				$results[$row[0]] += $row[1];
			else
				$results[$row[0]] = $row[1];
		}
		
		//tri des scores
		arsort($results);
		$results = array_slice($results, 0, $numberofchampion, true);
		
		$final = array();
		foreach ($results as $champion => $score) {
			$championObj = $this->userFactory->prepareUserFromId($champion);
			$final[$champion] = array($championObj, $score);
		}
		$this->assign("islogged", $this->currentUser->isLogged());
		$this->assign("contacts", $final);
		$this->getflood();
	}
	
	
}
?>