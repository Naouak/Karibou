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
	protected $author;
	protected $message;
	protected $wiki;
	protected $bbcode;

	function __construct($time, $author, $message, $wiki, $bbcode)
	{
		$this->time = $time;
		$this->author = $author;
		$this->message = $message;
		$this->wiki = $wiki;
		$this->bbcode = $bbcode;
	}

	function getAuthor()
	{
		return $this->author->getUserLink();
	}
	function getAuthorLogin()
	{
		return $this->author->getlogin();
	}
	
	function getAuthorObject()
	{
		return $this->author;
	}

	function getDate()
	{
		return date($this->time);
	}

	function getPost()
	{
		$content = $this->bbcode->transform($this->message);
		return $content;
	}
}


?>