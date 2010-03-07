<?php
/**
 * @copyright 2003 Jonathan Semczyk <jonathan.semczyk@free.fr>
 * @copyright 2009 Pierre Ducroquet <pinaraf@gmail.com>
 *
 * @license http://www.gnu.org/licenses/gpl.html GNU Public License
 * See the enclosed file COPYING for license information.
 * 
 * @package applications
 **/

/**
 * @package applications
 */
class HeaderHome extends Model
{
	function build()
	{
		ThemeManager::instance()->addApplication('klightbox');
		ThemeManager::instance()->addApplication('hintbox');
		$this->assign("cssfiles", ThemeManager::instance()->getCSSList());
		$this->assign('base_url', $GLOBALS['config']['base_url']);
		$this->assign('user_id', $this->currentUser->getID());
	}
	

}

?>
