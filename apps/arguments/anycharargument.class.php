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
 * AllChar Argument
 * 
 * @package applications
 */
class AnyCharArgument extends Argument
{
	function getVar($arg)
	{
		if( preg_match('/^([^,]+)$/', $arg, $match) )
		{
			return $match[1];
		}
		return FALSE;
	}
	
	function getUrlArgument()
	{
		return $this->value;
	}
}

?>