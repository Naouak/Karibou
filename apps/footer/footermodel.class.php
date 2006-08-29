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
		$this->assign("currentUser", $currentUser);
		
		$permissionsFactory = new PermissionsFactory($this->db);
		$this->assign('permission', $permissionsFactory->getPermissions($this->currentUser));
		unset ($permissionsFactory);
	}
}

?>
