<?php 

/**
 * @copyright 2005 Antoine Leclercq <http://antoine.leclercq.netcv.org>
 *
 * See the enclosed file COPYING for license information (GPL). If you
 * did not receive this file, see http://www.gnu.org/licenses/gpl.html.
 * 
 * @package applications
 **/

class NewsMini extends Model
{
	public function build()
	{
		//Recherche de toutes les derniers articles non supprimés et de leurs originaux
		if( isset($this->args['max']) )
		{
			$this->args['max'] = intval($this->args['max']);
			if($this->args['max'] != 0)
				$max = $this->args['max'];
			else
				$max = 5;
		}
		else
		{
			$max = 5;
		}
/*
		$reqSqlAllArticles = "
			SELECT news.id, news.id_author, news.id_groups, news.title, news.content, UNIX_TIMESTAMP(news.time) as timestamp, count(news_comments.id) as nb_comments
			FROM news LEFT OUTER JOIN news_comments ON news.id = news_comments.id_news
			WHERE (news.last = 1 AND news.deleted = 0)
			GROUP BY news.id
			ORDER BY timestamp
			DESC
			LIMIT $max";
*/
		$this->currentUser->getGroups($this->db);

		$reqSqlAllArticles = "
			SELECT news.id, news.id_author, news.id_groups, news.title, news.content, UNIX_TIMESTAMP(news.time) as timestamp, count(news_comments.id) as nb_comments
			FROM news LEFT OUTER JOIN news_comments ON news.id = news_comments.id_news
			WHERE (news.last = 1 AND news.deleted = 0)" .
			// AND ((news.id_groups IN (".$this->currentUser->getGroupTreeQuery().")) OR (news.id_author = '".$this->currentUser->getId()."'))
			"GROUP BY news.id
			ORDER BY timestamp
			DESC
			LIMIT $max;
			";

		$theNews = array ();
		$resSqlAllArticles = $this->db->prepare($reqSqlAllArticles);
		$resSqlAllArticles->execute();
		
		//Pour eviter les problemes de "Err 2014 : Command out of sync. You can't run this query now..."
		//il faut faire un fetchall, afin de vider la pile des enregistrements pour les traiter par la suite
		while ( $row = $resSqlAllArticles->fetch(PDO::FETCH_ASSOC) )
		{
			//Creation de l'objet d'une news
			$aNews = new News($this->userFactory);
			$aNews->load(
				$row['id'],
				$row['timestamp'], 
				$this->userFactory->prepareUserFromId($row['id_author']), 
				$row['id_groups'],
				$row['title'],
				$row['content'],
				$row['nb_comments']
				);

			$theNews[] = $aNews;
		}

		$this->assign("theNews", $theNews);
		$this->assign("permission", $this->permission);
		$this->assign("islogged", $this->currentUser->isLogged());
	}
}
?>
