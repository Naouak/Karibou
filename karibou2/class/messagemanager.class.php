<?php
/**
 * @copyright 2005 Antoine Leclercq <http://antoine.leclercq.netcv.org>
 *
 * @license http://www.gnu.org/licenses/lgpl.html Lesser GNU Public License
 * See the enclosed file COPYING for license information (LGPL).
 * 
 * @package framework
 **/

/**
 * Classe de gestion des messages
 * @package framework
 */
Class MessageManager
{

	private $messages;

	public function __construct()
	{
		$this->messages = array();
	}

	public function addMessage($destination, $message)
	{
		if (!isset($this->messages[$destination]))
		{
			$this->messages[$destination] = array();
		}
		
		array_push($this->messages[$destination], $message);
	}
	
	public function getMessages($destination)
	{
		if (isset($this->messages[$destination]))
		{
			return $this->messages[$destination];
		}
		else
		{
			return FALSE;
		}
	}

}

?>