<?php
/**
 * @version $Id:  news.class.php,v 0.1 2005/07/16 21:10:56 dat Exp $
 * @copyright 2005 Antoine Leclercq <http://antoine.leclercq.netcv.org>
 *
 * See the enclosed file COPYING for license information (GPL). If you
 * did not receive this file, see http://www.gnu.org/licenses/gpl.html.
 * 
 * @package applications
 **/

require_once dirname(__FILE__)."/newscomment.class.php";

class News
{
	protected $time;		//Date de la news
	protected $id;		//ID de la news dans la base de donnees
	protected $author;	//Objet utilisateur
	protected $title;		//Titre de la news
	protected $content;	//Contenu (texte) de la news
	protected $group;	//ID du groupe de destination
	
	protected $comments;	//Tableau contenant les commentaires
	protected $nbComments; //Tableau contenant le nombre de commentaires

	protected $db;

	//Constructeur de la classe News
	//Si les valeurs sont passees en argument, la news est creee
	//Sinon on doit la creer a partir de la base
	function __construct($userFactory)
	{
		$this->userFactory = $userFactory;
	}

	//Charge l'article a partir de l'identifiant (a rechercher dans la base) passe en parametre
	function loadFromId($db, $id)
	{
		//Affectation du lien de base dans l'objet
		$this->db = $db;
	
		//Recherche de toutes les dernières news non supprimées
		$reqArticle = "
			SELECT news.id, news.id_author, news.id_groups, news.title, news.content, UNIX_TIMESTAMP(news.time) as timestamp, count(news_comments.id) as nb_comments
			FROM news LEFT OUTER JOIN news_comments ON news.id = news_comments.id_news
			WHERE (news.id = ".$id." AND news.last = 1 AND news.deleted = 0)
			GROUP BY news.id
			";		
	

		$resArticle = $this->db->prepare($reqArticle);
		$resArticle->execute();
		
		//Vérification qu'un article a bien été trouvé
		//L'utilisation de rowCount semble poser un problème
		if ($row = $resArticle->fetch(PDO::FETCH_ASSOC))
		{

			$this->load(
				$row['id'],
				$row['timestamp'],
				$this->userFactory->prepareUserFromId($row['id_author']),
				$row['id_groups'],
				$row['title'],
				$row["content"],
				$row['nb_comments']
				);
			
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}
	
	//Charge la news a partir des arguments
	function load($id, $time, $author, $group, $title, $content, $nbComments)
	{
			$this->time				= $time;
			$this->id				= $id;
			$this->author			= $author;
			$this->group			= $group;
			$this->title			= $title;
			$this->content			= $content;
			$this->nbComments		= $nbComments;
	}
	
	//Charge les commentaires
	function loadComments ()
	{
			$reqComments = "SELECT UNIX_TIMESTAMP(time) as timestamp, id, id_parent, id_author, title, content
						FROM news_comments
						WHERE id_news = ".$this->getID()."
						ORDER BY time
						ASC;";
			$resComments = $this->db->prepare($reqComments);
			$resComments->execute();

			$theArticleComments = array();
			while ( $rowComments = $resComments->fetch(PDO::FETCH_ASSOC) )
			{
				//Creation de l'objet d'un commentaire
				$articleComment = new NewsComment (
						$rowComments['timestamp'],
						$rowComments['id'],
						$rowComments['id_parent'],
						$this->userFactory->prepareUserFromId($rowComments['id_author']),
						$rowComments['title'],
						$rowComments['content']
				);
				
				//Affectation de l'objet comment dans le tableau des comments
				$theArticleComments[] = $articleComment;
			}
			
			$this->comments = $theArticleComments;
	}

	function getID()
	{
		return $this->id;
	}

	function getAuthorLogin()
	{
		return $this->author->getLogin();
	}

	function getAuthor()
	{
		return $this->author->getUserLink();
	}
	
	function getAuthorId()
	{
		return $this->author->getId();
	}
	
	function getGroup()
	{
		return $this->group;
	}
	
	function getGroupName()
	{
		if ($this->getGroup() != "")
		{
			$groups = $this->userFactory->getGroups();
			$group = $groups->getGroupById($this->getGroup());
			return $group->getName();
		}
		else
		{
			return FALSE;
		}
	}

	function getDate()
	{
		return date('d/m/Y H:i', $this->time);
	}

	function getTitle()
	{
		return $this->title;
	}

	function getContent()
	{
		return $this->content;
	}
	
	function getContentXHTML()
	{
        $wiki = new wiki2xhtmlBasic();
        // initialisation variables
        $wiki->wiki2xhtml();
        $content = $wiki->transform($this->content);
        return $content;
	}
	
	function getComments()
	{
		return $this->comments;
	}
	
	function getNbComments()
	{
		return $this->nbComments;
	}
}

?>
