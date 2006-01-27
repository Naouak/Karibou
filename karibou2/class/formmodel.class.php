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
	
	//Needed for SmartyValidate
	public $smarty;
	

	function __construct(
		PDO $p_db,
		$kapp,
		UserFactory $userFactory,
		AppList $appList,
		$templatedir,
		HookManager $hookManager,
		LanguageManager $languageManager,
		EventManager $eventManager,
		MessageManager $messageManager,
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
		$this->languageManager = $languageManager;
		
		$this->appList = $appList;
		
		//Gestion des messages
		$this->formMessage = new FormMessage();

		ExecutionTimer::getRef()->start("New Smarty");
		$this->smarty = new KSmarty($kapp, $this->appList, $this->languageManager, FALSE, $this->currentUser->getPref("lang"));
		$this->smarty->template_dir = $templatedir.'/';
		$this->smarty->compile_dir = KARIBOU_COMPILE_DIR.'/'.get_class($this).'/';
		if(!is_dir($this->smarty->compile_dir)) mkdir($this->smarty->compile_dir);
		$this->smarty->config_dir = KARIBOU_CONFIG_DIR.'/';
		$this->smarty->cache_dir = KARIBOU_CACHE_DIR.'/'.get_class($this).'/';
		if(!is_dir($this->smarty->cache_dir)) mkdir($this->smarty->cache_dir);
		ExecutionTimer::getRef()->stop("New Smarty");
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