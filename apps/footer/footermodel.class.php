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
class FooterModel extends Model
{
	function build()
	{
		//Assignation de la variable hookManager dans Smarty pour affichage
		$this->assign("hookManager", $this->hookManager);
		$currentUser = $this->userFactory->getCurrentUser();
		$this->assign("user", $currentUser);
		$this->assign("islogged", $currentUser->isLogged());
		if ($currentUser->isLogged()) {
			$this->assign("login", $currentUser->getLogin());
		}
		if (isset($GLOBALS["config"]["footer"]["message"]))
			$this->assign("message", $GLOBALS["config"]["footer"]["message"]);
		else
			$this->assign("message", "");
		$this->assign("hasMenu", $this->hookManager->hasHook("header_menu"));
	}
}

?>
