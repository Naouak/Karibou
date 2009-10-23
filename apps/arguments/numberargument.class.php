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
 * Numeric Argument
 * 
 * @package applications
 */
class NumberArgument extends Argument
{
	function getVar($arg)
	{
		if( preg_match('#([-]?[0-9]+)#', $arg, $match) )
		{
			return $match[1];
		}
		return false;
	}
	
	function getUrlArgument()
	{
		return $this->value;
	}
}

?>
