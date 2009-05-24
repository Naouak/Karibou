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
		$app = $this->appList->getApp($this->appname);
		$config = $app->getConfig();
		$max = $config["displaynb"]["newsgrand"];
		
		//Gestion des mois / années
		if (isset($this->args['month'],$this->args['year']) && $this->args['month'] != "" && $this->args['year'] != "")
		{
			$month = $this->args['month'];
			$year = $this->args['year'];
		}

		$sql = "
			SELECT 
				date_format( time, '%Y' ) AS year,
				date_format( time, '%m' ) AS month ,
				count( id ) AS count,
				UNIX_TIMESTAMP(time) as timestamp
			FROM `news`
			WHERE last =1
			AND deleted =0
			GROUP BY date_format( time, '%Y' ) , date_format( time, '%m' )
			ORDER BY time ASC; ";
		$stmt = $this->db->prepare($sql);
		$stmt->execute();
		$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

		$this->assign("newsbymonth", $rows);

		if (isset($month, $year))
		{
			$and 	= '	AND date_format( news.time, \'%Y\' ) = '.$year.'
						AND date_format( news.time, \'%m\' ) = '.$month;
			$limit	= '';
		}
		else
		{
			$and = '';
			$limit = "LIMIT $max OFFSET 0";
		}

		//Recherche de toutes les derniers articles non supprimés et de leurs originaux
		$reqSqlAllArticles = "
			SELECT news.id, news.id_author, news.id_groups, news.title, news.content, UNIX_TIMESTAMP(news.time) as timestamp, count(news_comments.id) as nb_comments
			FROM news LEFT OUTER JOIN news_comments ON news.id = news_comments.id_news
			WHERE (news.last = 1 AND news.deleted = 0) ".
			$and .
			//AND ((news.id_groups IN (".$this->currentUser->getGroupTreeQuery().")) OR (news.id_author = '".$this->currentUser->getId()."'))
			" GROUP BY news.id
			ORDER BY timestamp DESC ".$limit;
			//";//".(($page-1)*$max).

		$theNews = array ();
        $groupname= array ();
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
		// cette boucle permet de récupérer le nom du groupe si la news est postée par un groupe
		foreach ($this->userFactory->getGroups() as $group)
            {
                if ($group->getId() == $aNews->getGroup())
                {
                    $groupname[$row['id']]=$group->getName();
                }

            }
        }
        $this->assign("group",$groupname);
        $this->assign("currentuser",$this->userFactory->getCurrentUser());
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
 $this->currentuser = $this->userFactory->getCurrentUser();
        $this->groups = $this->currentuser->getGroups();
        $grouparray = array();
        foreach ($this->groups as $group2)
        {
            $idofgroup = $group2->getId();
            $grouparray[$idofgroup]=$group2->role;


        }
        $this->assign('grouparray',$grouparray);
	}

}

?>
