<?php
/**
 * @copyright 2005 Jonathan Semczyk <jonathan.semczyk@free.fr>
 * @copyright 2009 Gilles Dehaudt <tonton1728@gmail.com>
 *
 * @license http://www.gnu.org/licenses/gpl.html GNU Public License
 * See the enclosed file COPYING for license information.
 * 
 * @package applications
 **/
 
/**
 * Relative Numeric Argument
 * 
 * @package applications
 */
class RelativeNumberArgument extends Argument
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
