<?php
/**
 * @copyright 2009 Grégoire Leroy <lupuscramus@gmail.com>
 *
 * @license http://www.gnu.org/licenses/gpl.html GNU Public License
 * See the enclosed file COPYING for license information.
 *
 * @package applications
 **/

/**
 * Argument Class to handle URL action
 *	- module
 *  - state
 *  - type
 *  - summary
 *
 * @package applications
 */
class SortBugs extends Argument
{
	function getVar($arg)
	{
		if( preg_match('#(module_id|id|state|type|summary)#', $arg, $match) )
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
