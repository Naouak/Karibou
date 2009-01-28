<?php
/**
 * @copyright 2005 Jonathan Semczyk <jonathan.semczyk@free.fr>
 *
 * @license http://www.gnu.org/licenses/gpl.html GNU Public License
 * See the enclosed file COPYING for license information.
 * 
 * @package applications
 **/

/**
 * @package applications
 */
class CalendarEventDB extends CalendarEvent
{

	function __construct($tab)
	{
		if( is_array($tab) )
		{
			parent::__construct(
				$tab['id'],
				$tab['calendar_id'],
				$tab['author_id'],
				$tab['description'],
				$tab['summary'],
				$tab['priority'],
				$tab['startdate'],
				$tab['stopdate'],
				$tab['location'],
				$tab['category'],
				$tab['recurrence'] );
		}
	}

}

?>
