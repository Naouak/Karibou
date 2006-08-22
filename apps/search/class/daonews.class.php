<?php 
/**
 * @copyright 2005 Antoine Leclercq <http://antoine.leclercq.netcv.org>
 *
 * @license http://www.gnu.org/licenses/gpl.html GNU Public License
 * See the enclosed file COPYING for license information.
 * 
 * @package applications
 **/

/** 
 * 
 * @package applications
 **/
class DAONews extends DAOElement
{
	protected $db;
	protected $userFactory;
	
	public function findFromKeyWords($keywords) {
		$req = 		"
			SELECT news.id, news.id_author, news.id_groups, news.title, news.content, UNIX_TIMESTAMP(news.time) as timestamp, count(news_comments.id) as nb_comments
			FROM news LEFT OUTER JOIN news_comments ON news.id = news_comments.id_news
			WHERE (news.title LIKE '%".$keywords."%' AND news.last = 1 AND news.deleted = 0)
			GROUP BY news.id
			";
		$res = $this->db->prepare($req);
		$res->execute();
		
		
		$articles = array();
		
		//Vérification qu'un article a bien été trouvé
		//L'utilisation de rowCount semble poser un problème
		while ($row = $res->fetch(PDO::FETCH_ASSOC))
		{
			$article = new News($this->userFactory);

			$article->load(
				$row['id'],
				$row['timestamp'],
				$this->userFactory->prepareUserFromId($row['id_author']),
				$row['id_groups'],
				$row['title'],
				$row["content"],
				$row['nb_comments']
				);
			
			$articles[] = $article;
		}
		return $articles;
    }
}

?>