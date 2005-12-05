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
 * Alpha-Numeric Argument
 * 
 * @package applications
 */
class KeyArgument extends Argument
{
	function getVar($arg)
	{
		if( preg_match('/^([abcdef0-9]{4}-[abcdef0-9]{4})$/', $arg, $match) )
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