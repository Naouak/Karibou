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
class EventManager
{
	protected $listeners;
	
	function __construct()
	{
		if( !isset($_SESSION['karibou_events']) )
		{
			$_SESSION['karibou_events'] = new ObjectList();
		}
	}
	
	function addListener($eventname, Listener $listener)
	{
		if( !isset($this->listeners[$eventname]) )
		{
			$this->listeners[$eventname] = array();
		}
		$this->listeners[$eventname][] = $listener;
	}
	
	function sendEvent($name, $message = "")
	{
		$_SESSION['karibou_events'][] = new Event($name, $message);
	}
	
	function performActions()
	{
		foreach($_SESSION['karibou_events'] as $key => $event)
		{
			Debug::display("EVENT : ".$event->getName() );
			if( isset($this->listeners[$event->getName()]) )
			{
				foreach($this->listeners[$event->getName()] as $listener)
				{
					$listener->eventOccured($event);
				}
			}
		}
		$_SESSION['karibou_events'] = new ObjectList();
	}
}

?>
