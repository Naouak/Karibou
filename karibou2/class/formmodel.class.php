<?php
/**
 * @copyright 2005 Jonathan Semczyk <jonathan.semczyk@free.fr>
 *
 * @license http://www.gnu.org/licenses/lgpl.html Lesser GNU Public License
 * See the enclosed file COPYING for license information (LGPL).
 * 
 * @package framework
 **/

/**
 * Classe Model
 * @package framework
 */
abstract class FormModel extends Model
{
	private $form;

	function __construct(
		PDO $p_db,
		$kapp,
		UserFactory $userFactory,
		AppList $appList,
		$templatedir,
		HookManager $hookManager,
		EventManager $eventManager,
		MessageManager $messageManager,
		KSmarty $smarty,
		$permission = _READ_ONLY_,
		$args = array() )
	{
		$this->permission = $permission;
		$this->args = $args;
		$this->db = $p_db;
		$this->appname = $kapp;
		$this->userFactory = $userFactory;
		$this->eventManager = $eventManager;
		$this->messageManager = $messageManager;
		$this->currentUser = $userFactory->getCurrentUser();
		
		$this->appList = $appList;
		
		//Gestion des messages
		$this->formMessage = new FormMessage();

	}
	
	
	function setForm($form)
	{
		$this->form = $form;
	}
	
	function setRedirectArg($name, $value)
	{
		$this->form->setRedirectArg($name, $value);
	}
	
	function getLocation()
	{
		return $this->form->getLocation( $this->appList );
	}

}

?>
