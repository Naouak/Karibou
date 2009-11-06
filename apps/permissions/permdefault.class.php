<?php
/**
 * @copyright 2004 Jonathan Semczyk <jonathan.semczyk@free.fr>
 *
 * @license http://www.gnu.org/licenses/gpl.html GNU Public License
 * See the enclosed file COPYING for license information.
 * 
 * @package applications
 **/

/**
 * @package applications
 */

class PermDefault extends Model
{
	function build()
	{
		$applis = array();
		
		Config::appReset();
		while( list($name, $appli) = Config::appEach() )
		{
			$applis[] = $name;
		}
		sort($applis);
		$this->assign('applis', $applis);
	}

}

?>
