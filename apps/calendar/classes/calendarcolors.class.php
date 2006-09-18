<?php
/**
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
class CalendarColors
{
	static $colors;
	static $db;
	
	function __construct()
	{

	}
	
	function init($db)
	{
		self::$db = $db;
		if (!isset(self::$colors))
		{
			self::$colors = array();
			$qry = 'SELECT * FROM calendar_colors';
			
			try
			{
				$stmt = $db->query($qry);
				$colors = $stmt->fetchAll(PDO::FETCH_ASSOC);
				if (count($colors)>0)
				{
					foreach($colors as $color)
					{
						self::$colors[$color['calendarid']] = array();
						self::$colors[$color['calendarid']][1] = $color['color1'];
						self::$colors[$color['calendarid']][2] = $color['color2'];
					}
				}
			}
			catch(PDOException $e)
			{
			}
		}
	}
	
	public static function getColor($calendarid, $n = FALSE)
	{
		if (isset(self::$colors))
		{
			if (isset(self::$colors[$calendarid]))
			{
				if ($n !== FALSE)
				{
					return self::$colors[$calendarid][$n];
				}
				else
				{
					return self::$colors[$calendarid][1];
				}
			}
		}

		if ($n !== FALSE)
		{
			return 'bbbbff';
		}
		else
		{
			return '9999ff';
		}
	}
}

?>