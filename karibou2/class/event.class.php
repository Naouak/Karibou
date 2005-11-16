<?php
/**
 * @copyright 2005 Jonathan Semczyk <jonathan.semczyk@free.fr>
 *
 * @license http://www.gnu.org/licenses/lgpl.html Lesser GNU Public License
 * See the enclosed file COPYING for license information (LGPL).
 * 
 * @package framework
 **/

/**
 * @package framework
 */
class Event
{
	protected $name;
	protected $message;
	
	function __construct($name, $message)
	{
		$this->name = $name;
		$this->message = $message;
	}
	
	function getName()
	{
		return $this->name;
	}
	function getMessage()
	{
		return $this->message;
	}
}

?>
