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
	 * Objet de gestion des événements
	 */
	protected $eventManager;

	/*
	 * Objet de gestion des messages
	 */
	protected $messageManager;

	/*
	 * We will store the result of the build function to allow template-less models and views.
	 * It is useful for JSON outputs for instance.
	 */
	protected $rawResult = FALSE;

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
		$this->templatedir = $templatedir;
		$this->userFactory = $userFactory;
		$this->currentUser = $userFactory->getCurrentUser();
		
		$this->appList = $appList;
		
		//Gestion des messages
		$this->formMessage = new FormMessage();

		//Gestion des ancres
		$this->hookManager = $hookManager;
		
		$this->eventManager = $eventManager;
		
		$this->messageManager = $messageManager;

		$this->smarty = $smarty;

		$this->vars = array('islogged' => $this->currentUser->isLogged(), 'currentuser' => $this->currentUser, 'karibou' => $GLOBALS['config']);
	}
	
	protected function getConfig()
	{
		return $this->appList->getApp($this->appname)->getConfig();
	}
	
	/**
	 * Fonction à utiliser pour passer une variable au template
	 */
	public function assign($name, $value=NULL)
	{
		if( is_array($name)  && ($value==NULL) )
		{
			foreach($name as $key => $v)
			{
				$this->vars[$key] = $v;
			}
		}
		else
		{
			$this->vars[$name] = $value;
		}
	}
	
	abstract function build();
	
	function display($template)
	{
		ExecutionTimer::getRef()->start("Display Model (".$template.") ".$this->appname."(".get_class($this).")");

		if ($template == "")
		{
			echo $this->rawResult;
		}
		else if (preg_match('/\.tpl$/i',$template))
		{
			ExecutionTimer::getRef()->start("Config Smarty");
			// problème avec les hook, smarty appelé recursivement efface des valeurs
			// ça fonction mieux en commentant cette ligne mais faut trouvé un contournement
			//$this->smarty->clear_all_assign();
			$this->smarty->quick_hack_clear_values();
			$this->smarty->template_dir = $this->templatedir.'/';
			$this->smarty->compile_dir = KARIBOU_COMPILE_DIR.'/'.get_class($this).'/';
			if(!is_dir($this->smarty->compile_dir)) mkdir($this->smarty->compile_dir);
			$this->smarty->config_dir = KARIBOU_CONFIG_DIR.'/';
			$this->smarty->cache_dir = KARIBOU_CACHE_DIR.'/'.get_class($this).'/';
			if(!is_dir($this->smarty->cache_dir)) mkdir($this->smarty->cache_dir);
			$this->smarty->setApp($this->appname);
			ExecutionTimer::getRef()->stop("Config Smarty");

			
			ExecutionTimer::getRef()->start("Assign Values");
			foreach($this->vars as $key => $value)
			{
				$this->smarty->assign($key, $value);
			}
			ExecutionTimer::getRef()->stop("Assign Values");

			$this->smarty->display($template);
		}
		ExecutionTimer::getRef()->stop("Display Model (".$template.") ".$this->appname."(".get_class($this).")");
	}

	public function getPermission() {
		return $this->permission;
	}

	public function setRawResult($rawResult) {
		$this->rawResult = $rawResult;
	}

	public function getRawResult() {
		return $this->rawResult;
	}
}

?>
