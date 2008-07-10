<?php
/* BEWARE made by Naouak*/ 
 
/**
 * Classe Stats
 *
 * @package applications
 */

class Stats extends Model
{
	private function mergetable($donnees,$donneesdeuz){
		$i=0;
		$k=0;
		$count = count($donnees);
		while($i<$count){
			$j=0;
			while($j<count($donneesdeuz)){
				if($donnees[$i]['champion'] == $donneesdeuz[$j]['champion']){
					$donnees[$i]['score'] += $donneesdeuz[$j]['score'];
					break;
				}
				$j++;
			}
			$i++;
		}
		$j=0;
		while($j<count($donneesdeuz)){
			$i = 0;
			while($i<$count){
				if($donnees[$i]['champion'] == $donneesdeuz[$j]['champion']){

					break;
				}
				$i++;
			}
			if($i == $count){
				$donnees[$count+$k] = $donneesdeuz[$j];
				$k++;
			}
			$j++;
		}
		return $donnees;
	}
	
	public function build()
	{
		$numberofchampion = 100;
		$preums=array("text"=>"preums","score"=>5);
		$deuz =array("text"=>"deuz","score"=>2);
		$dernz=array("text"=>"dernz","score"=>3);
		$troiz=array("text"=>"troiz","score"=>1);
		$i = 0;
		
		//Récupération des preums
		$stmt = $this->db->prepare("SELECT id_auteur AS champion,COUNT(*) * :score As score FROM (SELECT DATE_FORMAT(`time`,'%d.%m.%Y') AS dateMsg , id_auteur , post FROM minichat WHERE post LIKE :preums GROUP BY dateMsg ORDER BY `time`) AS t GROUP BY id_auteur ORDER BY id_auteur");
		
		$stmt->bindParam(':preums', $preums["text"]);
		$stmt->bindValue(':score', $preums["score"],PDO::PARAM_INT);
		$stmt->execute();
		$donnees = $stmt->fetchall();
		
		//Récupération des deuzs
		$stmt = $this->db->prepare("SELECT champion, Count(*) * :score AS score FROM (SELECT * FROM (SELECT Deuz.id_auteur AS champion,Deuz.dateMsg,time FROM (SELECT DATE_FORMAT(`time`,'%d.%m.%Y') AS dateMsg ,time, id_auteur , post FROM minichat WHERE post LIKE :preums GROUP BY dateMsg ORDER BY `time`) AS Preums, (SELECT DATE_FORMAT(`time`,'%d.%m.%Y') AS dateMsg , id_auteur , post FROM minichat WHERE post LIKE :deuz ORDER BY `time`) AS Deuz WHERE Preums.dateMsg = Deuz.dateMsg AND Preums.id_auteur <> Deuz.id_auteur ORDER BY Deuz.id_auteur)AS t GROUP BY dateMsg ORDER BY time) AS t2 GROUP BY champion; ");
		

		$stmt->bindParam(':preums', $preums["text"]);
		$stmt->bindParam(':deuz', $deuz["text"]);
		$stmt->bindValue(':score', $deuz["score"],PDO::PARAM_INT);
		$stmt->execute();
		
		$donnees = $this->mergetable($donnees,$stmt->fetchall());
		
		//Récupération des troiz
		$stmt = $this->db->prepare("SELECT champion,COUNT(*) * :score AS score FROM(SELECT champion, DATE_FORMAT(`time`,'%d.%m.%Y') AS dateMsg FROM (SELECT DISTINCT troiz.champion AS champion, troiz.time AS time FROM (SELECT id_auteur AS champion, time FROM minichat WHERE post LIKE :troiz) AS troiz, (SELECT id_auteur AS champion,COUNT(*) As score FROM (SELECT DATE_FORMAT(`time`,'%d.%m.%Y') AS dateMsg , id_auteur , post FROM minichat WHERE post LIKE :preums GROUP BY dateMsg ORDER BY `time`) AS t GROUP BY id_auteur ORDER BY id_auteur) AS Preums, (SELECT champion, Count(*) AS score FROM (SELECT * FROM (SELECT Deuz.id_auteur AS champion,Deuz.dateMsg,time FROM (SELECT DATE_FORMAT(`time`,'%d.%m.%Y') AS dateMsg ,time, id_auteur , post FROM minichat WHERE post LIKE :preums GROUP BY dateMsg ORDER BY `time`) AS Preums, (SELECT DATE_FORMAT(`time`,'%d.%m.%Y') AS dateMsg , id_auteur , post FROM minichat WHERE post LIKE :deuz ORDER BY `time`) AS Deuz WHERE Preums.dateMsg = Deuz.dateMsg AND Preums.id_auteur <> Deuz.id_auteur ORDER BY Deuz.id_auteur)AS t GROUP BY dateMsg ORDER BY time) AS t2 GROUP BY champion) AS Deuz WHERE Preums.champion<>troiz.champion AND Deuz.champion<>troiz.champion) AS t3 GROUP BY dateMsg ORDER BY time)AS t4 GROUP BY champion;");
		

		$stmt->bindParam(':preums', $preums["text"]);
		$stmt->bindParam(':deuz', $deuz["text"]);
		$stmt->bindParam(':troiz', $troiz["text"]);
		$stmt->bindValue(':score', $troiz["score"],PDO::PARAM_INT);
		$stmt->execute();
		
		$donnees = $this->mergetable($donnees,$stmt->fetchall());
		
		//récupération des dernz
		$stmt = $this->db->prepare("SELECT champion,COUNT(*) * :score AS score FROM(SELECT id_auteur AS champion,dateMsg FROM (SELECT DATE_FORMAT(`time`,'%d.%m.%Y') AS dateMsg ,time, id_auteur , post FROM minichat WHERE post LIKE :dernz ORDER BY time DESC) AS t GROUP BY dateMsg ORDER BY time) AS t2 GROUP BY champion; ");
		
		$stmt->bindParam(':dernz', $dernz["text"]);
		$stmt->bindValue(':score', $dernz["score"],PDO::PARAM_INT);
		$stmt->execute();
		
		$donnees = $this->mergetable($donnees,$stmt->fetchall());
		
		//tri des scores
		foreach ($donnees as $key => $row) {
			$score[$key] = $row['score'];
		}
		array_multisort($score, SORT_DESC, $donnees);
		
		
		$i=0;
		while($i<$numberofchampion AND $i<count($donnees)){
			if($donnees[$i]['champion'] =  $this->userFactory->prepareUserFromId($donnees[$i]['champion']));
			$i++;
		}
		$this->assign("islogged", $this->currentUser->isLogged());
		$this->assign("contacts", array_slice($donnees,0,$numberofchampion));
	}
}
?>
