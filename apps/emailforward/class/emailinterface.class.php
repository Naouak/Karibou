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
abstract class EmailInterface
{

	function __construct()
	{
	}
	
	abstract function changeMailDrop ($email, $newemail);
}

?>
