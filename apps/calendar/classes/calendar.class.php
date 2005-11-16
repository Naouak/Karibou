<?php
/**
 * @copyright 2005 Jonathan Semczyk <http://jon.netcv.org>
 * @copyright 2005 Antoine Leclercq <http://antoine.leclercq.netcv.org>
 *
 * @license http://www.gnu.org/licenses/gpl.html GNU Public License
 * See the enclosed file COPYING for license information.
 * 
 * @package applications
 **/

/**
 * @package applications
 */
class Calendar
{
	protected $calendarType;
	protected $id;
	protected $name;
	protected $type;
	protected $reader;
	protected $writer;
	
	function __construct(CalendarReader $reader, CalendarWriter $writer, $calendarType, $id, $name, $type)
	{
		$this->reader = $reader;
		$this->writer = $writer;
		$this->calendarType = $calendarType;
		$this->id = $id;
		$this->name = $name;
		$this->type = $type;
	}
	
	function getCalendarType()
	{
		return $this->calendarType;
	}
	
	function getId()
	{
		return $this->id;
	}

	function getName()
	{
		return $this->name;
	}

	function getType()
	{
		return $this->type;
	}

	function getReader()
	{
		return $this->reader;
	}

	function getWriter()
	{
		return $this->writer;
	}
}

?>