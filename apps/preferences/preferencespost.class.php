<?php 
/**
 * @version $Id:  preferencespost.class.php,v 0.1 2005/06/29 10:57:56 dat Exp $
 * @copyright 2005 Antoine Leclercq <http://antoine.leclercq.netcv.org>
 * @copyright 2009 Grégoire Leroy <lupuscramus@gmail.com>
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
class PreferencesPost extends FormModel
{
	public function build()
	{
		$currentUser = $this->userFactory->getCurrentUser();
		$prefName = array('lang','localize'); // wtf ??
		
		$lang = filter_input(INPUT_POST,"lang",FILTER_SANITIZE_SPECIAL_CHARS);
		$currentUser->setPref($prefName[0], $lang);

		$loc = filter_input(INPUT_POST, "localization", FILTER_SANITIZE_SPECIAL_CHARS);
		$currentUser->setPref($prefName[1], $loc == "true");

		$currentUser->savePrefs($this->db);
	}
}
?>
