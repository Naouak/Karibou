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
	protected $rendering;

	function __construct($time, $author, $message, $rendering)
	{
		$this->time = $time;
		$this->author = $author;
		$this->message = $message;
		$this->rendering = $rendering;
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
		$content = $this->rendering->transform($this->message);
		return $content;
	}
}


?>