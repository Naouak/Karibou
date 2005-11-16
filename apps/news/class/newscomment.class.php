<?php
class NewsComment
{
	protected $time;		//Date du commentaire
	protected $id;		//ID du commentaire
	protected $idNews;	//ID de la news
	protected $idParent;	//ID du commentaire parent	
	protected $author;	//Objet utilisateur
	protected $title;		//Titre de la news
	protected $content;	//Contenu (texte) de la news

	function __construct($time, $id, $idParent, $author, $title, $content)
	{
		$this->time				= $time;
		$this->id				= $id;
		$this->idParent			= $idParent;
		$this->author			= $author;
		$this->title				= $title;
		$this->content			= $content;
	}

	function getID()
	{
		return $this->id;
	}

	function getIdParent()
	{
		return $this->idParent;
	}

	function getAuthorLogin()
	{
		return $this->author->getLogin();
	}

	function getAuthor()
	{
		return $this->author->getUserLink();
	}

	function getDate()
	{
		return date('d/m/Y H:i', $this->time);
	}

	function getContent()
	{
		return $this->content;
	}

	function getTitle()
	{
		return $this->title;
	}
}
?>
