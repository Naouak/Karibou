<?php 

/**
 * @copyright 2005 Antoine Leclercq <http://antoine.leclercq.netcv.org>
 *
 * See the enclosed file COPYING for license information (GPL). If you
 * did not receive this file, see http://www.gnu.org/licenses/gpl.html.
 * 
 * @package applications
 **/
 
require_once dirname(__FILE__)."/class/news.class.php";
require_once dirname(__FILE__)."/class/newscomment.class.php";

class NewsGrand extends Model
{
	public function build()
	{
	
		$menuApp = $this->appList->getApp($this->appname);
		$menuApp->addView("menu", "header_menu", array("page" => "news") );

	
		//Comptage des news (prise en compte des dernieres uniquement)
		//$max = 2;
		$app = $this->appList->getApp($this->appname);
		$config = $app->getConfig();
		$max = $config["displaynb"]["newsgrand"];
        $this->currentUser->getGroups($this->db);
		//$req_sql = 'SELECT COUNT(*) as nb FROM news WHERE last = 1 AND deleted = 0';
		$req_sql = "
			SELECT count(news.id) as nb
			FROM news
			WHERE (news.last = 1 AND news.deleted = 0)
			AND ((news.id_groups IN (".$this->currentUser->getGroupTreeQuery().")) OR (news.id_author = '".$this->currentUser->getId()."'))
		";
		$stmt = $this->db->prepare($req_sql);
		$stmt->execute();
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		$count = $row["nb"];
		unset($stmt);
		unset($row);
		
		//Gestion des pages
		if(isset($this->args['pagenum']))
			$page = $this->args['pagenum'];
		else
			$page = 1;
		
		//Comptage du nombre de pages
		$page_count = ceil($count / $max);
		if($page_count > 1)
		{
			$pages = range(1, $page_count);
			$this->assign('pages', $pages);
			$this->assign('page', $page);
		}
		
		//Recherche de toutes les derniers articles non supprimés et de leurs originaux
		$reqSqlAllArticles = "
			SELECT news.id, news.id_author, news.id_groups, news.title, news.content, UNIX_TIMESTAMP(news.time) as timestamp, count(news_comments.id) as nb_comments
			FROM news LEFT OUTER JOIN news_comments ON news.id = news_comments.id_news
			WHERE (news.last = 1 AND news.deleted = 0)
			AND ((news.id_groups IN (".$this->currentUser->getGroupTreeQuery().")) OR (news.id_author = '".$this->currentUser->getId()."'))
			GROUP BY news.id
			ORDER BY timestamp
			DESC
			LIMIT $max OFFSET ".(($page-1)*$max).";
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
		
		//Verification de la presence d'erreur et affection du message d'erreur a afficher
		$this->assign("theNewsMessages", $this->formMessage->getSession());
		$this->formMessage->flush();
		
		//Ajout du hook pour le rss
		//$this->hookManager->add("html_head", "<link rel=\"alternate\" type=\"application/rss+xml\" title=\"Karibou :: News\" href=\"http://k2/news/rss/\"\">");
		
		//Affectation des variables a afficher
		$this->assign("theNews", $theNews);
		$this->assign('permission', $this->permission);
		$this->assign('servername', $_SERVER['SERVER_NAME']);
		$this->assign("currentUserId", $this->currentUser->getId());
		$this->assign("newsgrand", TRUE);
	}

}

?>
