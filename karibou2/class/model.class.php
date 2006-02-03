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
abstract class Model
{
	/**
	 * @var PDO
	 */
	protected $db;
	
	/**
	 * @var UtilisateurCourant
	 */
	protected $currentUser;
	
	/**
	 * @var UserFactory
	 */
	protected $userFactory;
	
	
	protected $appList;
	
	/**
	 * @var String
	 */
	public $appname;
		/**
	 * @var String
	 */
	protected $templatedir;
	
	/**
	 * droit de lecture par défaut
	 * @var Int
	 */
	protected $permission = _NO_ACCESS_;
	
	/**
	 * @var Array
	 */
	protected $args;
	
	/**
	 * Smarty est un moteur de template : http://smarty.php.net/
	 * @var Smarty
	 */
	public $smarty;
	
	/**
	 * Un tableau qui contient les variables à passer à Smarty
	 * @var Array
	 */
	protected $vars = array();
	
	/*
	 * Objet de gestion des messages de formulaires (passage en session)
	 */
	public $formMessage;
	
	/*
	 * Objet de gestion des ancres
	 */
	protected $hookManager;

	/*
	 * Objet de gestion des langues
	 */
	public $languageManager;

	/*
	 * Objet de gestion des événements
	 */
	protected $eventManager;

	/*
	 * Objet de gestion des messages
	 */
	protected $messageManager;

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
		KSmarty $smarty,
		$permission = _READ_ONLY_,
		$args = array() )
	{
		$this->permission = $permission;
		$this->args = $args;
		$this->db = $p_db;
		$this->appname = $kapp;
		$this->templatedir = $templatedir;
		$this->userFactory = $userFactory;
		$this->currentUser = $userFactory->getCurrentUser();
		
		$this->appList = $appList;
		
		//Gestion des messages
		$this->formMessage = new FormMessage();

		//Gestion des ancres
		$this->hookManager = $hookManager;
		
		//Gestion des langues
		$this->languageManager = $languageManager;

		$this->eventManager = $eventManager;
		
		$this->messageManager = $messageManager;

		$this->smarty = $smarty;
	}
	
	protected function getConfig()
	{
		return $this->appList->getApp($this->appname)->getConfig();
	}
	
	/**
	 * Fonction à utiliser pour passer une variable au template
	 */
	public function assign($name, $value=FALSE)
	{
		if( is_array($name)  && !$value )
		{
			foreach($name as $key => $value)
			{
				$this->vars[$key] = $value;
			}
		}
		else
		{
			$this->vars[$name] = $value;
		}
	}
	
	abstract function build();
	
	/**
	 * Lance le moteur de template
	 * @return String HTML généré
	 */
	function fetch($template)
	{
		ExecutionTimer::getRef()->start("Config Smarty");
		$this->smarty->clear_all_assign();
		$this->smarty->template_dir = $this->templatedir.'/';
		$this->smarty->compile_dir = KARIBOU_COMPILE_DIR.'/'.get_class($this).'/';
		if(!is_dir($this->smarty->compile_dir)) mkdir($this->smarty->compile_dir);
		$this->smarty->config_dir = KARIBOU_CONFIG_DIR.'/';
		$this->smarty->cache_dir = KARIBOU_CACHE_DIR.'/'.get_class($this).'/';
		if(!is_dir($this->smarty->cache_dir)) mkdir($this->smarty->cache_dir);
		$this->smarty->setApp($this->appname);
		ExecutionTimer::getRef()->stop("Config Smarty");

		foreach($this->vars as $key => $value)
		{
			$this->smarty->assign($key, $value);
		}
		$html = $this->smarty->fetch($template);
		return $html;
	}
	function display($template)
	{
		ExecutionTimer::getRef()->start("Config Smarty");
		$this->smarty->clear_all_assign();
		$this->smarty->template_dir = $this->templatedir.'/';
		$this->smarty->compile_dir = KARIBOU_COMPILE_DIR.'/'.get_class($this).'/';
		if(!is_dir($this->smarty->compile_dir)) mkdir($this->smarty->compile_dir);
		$this->smarty->config_dir = KARIBOU_CONFIG_DIR.'/';
		$this->smarty->cache_dir = KARIBOU_CACHE_DIR.'/'.get_class($this).'/';
		if(!is_dir($this->smarty->cache_dir)) mkdir($this->smarty->cache_dir);
		$this->smarty->setApp($this->appname);
		ExecutionTimer::getRef()->stop("Config Smarty");

		foreach($this->vars as $key => $value)
		{
			$this->smarty->assign($key, $value);
		}
		$this->smarty->display($template);
	}
}

?>