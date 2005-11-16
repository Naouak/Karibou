<?php 
/**
 * @version $Id:  preferenceslarge.class.php,v 0.1 2005/06/26 10:52:56 dat Exp $
 * @copyright 2005 Antoine Leclercq <http://antoine.leclercq.netcv.org>
 *
 * @license http://www.gnu.org/licenses/gpl.html GNU Public License
 * See the enclosed file COPYING for license information.
 * 
 * @package applications
 **/

/** 
 * 
 * @package applications
 **/
class PreferencesLarge extends Model
{
	public function build()
	{
		$this->assign('langpref', $this->userFactory->getCurrentUser()->getPref('lang') );
		$this->assign('permission', $this->permission);
	}
}


?>
