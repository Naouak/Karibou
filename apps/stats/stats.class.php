<?php
/* BEWARE made by Naouak*/ 
 
/**
 * Classe Stats
 *
 * @package applications
 */

class Stats extends Model
{
	public function build()
	{
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
		$stmt = $this->db->prepare("SELECT id_auteur AS champion,COUNT(*) * :score As score FROM (SELECT DATE(`time`) AS dateMsg , id_auteur , post FROM minichat WHERE post LIKE :preums GROUP BY dateMsg ORDER BY `time`) AS t GROUP BY id_auteur ORDER BY id_auteur");
		
		$stmt->bindParam(':preums', $preums["text"]);
		$stmt->bindValue(':score', $preums["score"],PDO::PARAM_INT);
		$stmt->execute();
		while ($row = $stmt->fetch()) {
			$results[$row[0]] = $row[1];
		}
		
		//Récupération des deuzs
		$stmt = $this->db->prepare("SELECT champion, Count(*) * :score AS score FROM (SELECT * FROM (SELECT Deuz.id_auteur AS champion,Deuz.dateMsg,time FROM (SELECT DATE(`time`) AS dateMsg ,time, id_auteur , post FROM minichat WHERE post LIKE :preums GROUP BY dateMsg ORDER BY `time`) AS Preums, (SELECT DATE(`time`) AS dateMsg , id_auteur , post FROM minichat WHERE post LIKE :deuz ORDER BY `time`) AS Deuz WHERE Preums.dateMsg = Deuz.dateMsg AND Preums.id_auteur <> Deuz.id_auteur ORDER BY Deuz.id_auteur)AS t GROUP BY dateMsg ORDER BY time) AS t2 GROUP BY champion; ");
		

		$stmt->bindParam(':preums', $preums["text"]);
		$stmt->bindParam(':deuz', $deuz["text"]);
		$stmt->bindValue(':score', $deuz["score"],PDO::PARAM_INT);
		$stmt->execute();
		
		while ($row = $stmt->fetch()) {
			if (array_key_exists($row[0], $results))
				$results[$row[0]] += $row[1];
			else
				$results[$row[0]] = $row[1];
		}
		
		//Récupération des troiz
		$stmt = $this->db->prepare("SELECT champion,COUNT(*) * :score AS score FROM(SELECT champion, DATE(`time`) AS dateMsg FROM (SELECT DISTINCT troiz.champion AS champion, troiz.time AS time FROM (SELECT id_auteur AS champion, time FROM minichat WHERE post LIKE :troiz) AS troiz, (SELECT id_auteur AS champion,COUNT(*) As score FROM (SELECT DATE(`time`) AS dateMsg , id_auteur , post FROM minichat WHERE post LIKE :preums GROUP BY dateMsg ORDER BY `time`) AS t GROUP BY id_auteur ORDER BY id_auteur) AS Preums, (SELECT champion, Count(*) AS score FROM (SELECT * FROM (SELECT Deuz.id_auteur AS champion,Deuz.dateMsg,time FROM (SELECT DATE(`time`) AS dateMsg ,time, id_auteur , post FROM minichat WHERE post LIKE :preums GROUP BY dateMsg ORDER BY `time`) AS Preums, (SELECT DATE(`time`) AS dateMsg , id_auteur , post FROM minichat WHERE post LIKE :deuz ORDER BY `time`) AS Deuz WHERE Preums.dateMsg = Deuz.dateMsg AND Preums.id_auteur <> Deuz.id_auteur ORDER BY Deuz.id_auteur)AS t GROUP BY dateMsg ORDER BY time) AS t2 GROUP BY champion) AS Deuz WHERE Preums.champion<>troiz.champion AND Deuz.champion<>troiz.champion) AS t3 GROUP BY dateMsg ORDER BY time)AS t4 GROUP BY champion;");
		

		$stmt->bindParam(':preums', $preums["text"]);
		$stmt->bindParam(':deuz', $deuz["text"]);
		$stmt->bindParam(':troiz', $troiz["text"]);
		$stmt->bindValue(':score', $troiz["score"],PDO::PARAM_INT);
		$stmt->execute();
		while ($row = $stmt->fetch()) {
			if (array_key_exists($row[0], $results))
				$results[$row[0]] += $row[1];
			else
				$results[$row[0]] = $row[1];
		}
		
		//récupération des dernz
		$stmt = $this->db->prepare("SELECT champion,COUNT(*) * :score AS score FROM(SELECT id_auteur AS champion,dateMsg FROM (SELECT DATE(`time`) AS dateMsg ,time, id_auteur , post FROM minichat WHERE post LIKE :dernz ORDER BY time DESC) AS t GROUP BY dateMsg ORDER BY time) AS t2 GROUP BY champion; ");
		
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
	}
}
?>
