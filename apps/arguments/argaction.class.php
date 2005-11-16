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
 * Argument Class to handle URL action
 *  - add
 *  - edit
 *  - remove
 * 
 * @package applications
 */
class ArgAction extends Argument
{
	function getVar($arg)
	{
		if( preg_match('#(add|edit|remove)#', $arg, $match) )
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