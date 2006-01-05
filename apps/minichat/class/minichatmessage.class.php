<?php 

/**
 * @copyright 2004 Jonathan Semczyk <jonathan.semczyk@free.fr>
 * @copyright 2006 Antoine Leclercq <http://antoine.leclercq.netcv.org>
 *
 * See the enclosed file COPYING for license information (GPL). If you
 * did not receive this file, see http://www.gnu.org/licenses/gpl.html.
 * 
 * @package applications
 **/

class MinichatMessage
{
	protected $time;
	protected $auteur;
	protected $txt_post;
	protected $wiki;

	function __construct($time, $auteur, $txt_post, $wiki)
	{
		$this->time = $time;
		$this->auteur = $auteur;
		$this->txt_post = $txt_post;
		$this->wiki = $wiki;
	}

	function getAuthor()
	{
		return $this->auteur->getUserLink();
	}
	function getAuthorLogin()
	{
		return $this->auteur->getlogin();
	}

	function getDate()
	{
		return date('H:i', $this->time);
	}

	function getPost()
	{
		return $this->txt_post;
	}
	
	function getPostXHTML()
	{
		$content = $this->wiki->transform($this->txt_post);
		return $content;
	}
}


?>