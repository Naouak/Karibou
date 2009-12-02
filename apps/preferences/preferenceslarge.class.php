<?php 
/**
 * @version $Id:  preferenceslarge.class.php,v 0.1 2005/06/26 10:52:56 dat Exp $
 * @copyright 2005 Antoine Leclercq <http://antoine.leclercq.netcv.org>
 * @copyright 2009 Grégoire Leroy <lupuscramus@gmail.com>
 * @copyright 2009 Pierre Ducroquet <pinaraf@pinaraf.info>
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
		$this->assign('lang', $this->userFactory->getCurrentUser()->getPref('lang') );
		$this->assign('localization', var_export($this->userFactory->getCurrentUser()->getPref('localize', true), true));
		$this->assign('permission', $this->permission);
		$theme = $this->userFactory->getCurrentUser()->getPref('theme');
		if ($theme == null)
			$theme = "";
		$this->assign('currenttheme', $theme);
		$this->assign('themes', ThemeManager::instance()->getVisibleThemes());
	}
}


?>
