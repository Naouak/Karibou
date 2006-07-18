<?php
/**
 * @copyright 2005 Jonathan Semczyk <http://jon.netcv.org>
 * @copyright 2003 Pierre Laden <pladen@elv.enic.fr>
 *
 * @license http://www.gnu.org/licenses/lgpl.html Lesser GNU Public License
 * See the enclosed file COPYING for license information (LGPL).
 * 
 * @package framework
 **/
 
require_once dirname(__FILE__).'/../config/config.inc.php';

require_once KARIBOU_CLASS_DIR.'/ClassLoader.class.php';
require_once KARIBOU_CLASS_DIR.'/executiontimer.class.php';

ClassLoader::add('Database', KARIBOU_CLASS_DIR.'/database.class.php');
ClassLoader::add('Debug', KARIBOU_CLASS_DIR.'/debug.class.php');
ClassLoader::add('Config', KARIBOU_CLASS_DIR.'/config.class.php');
ClassLoader::add('Auth', KARIBOU_CLASS_DIR.'/auth.class.php');
ClassLoader::add('BaseURL', KARIBOU_CLASS_DIR."/baseurl.class.php");
ClassLoader::add('Argument', KARIBOU_CLASS_DIR."/Argument.class.php");
ClassLoader::add('UrlParser', KARIBOU_CLASS_DIR."/urlparser.class.php");
ClassLoader::add('XMLCache', KARIBOU_CLASS_DIR."/xmlcache.class.php");
ClassLoader::add('Group', KARIBOU_CLASS_DIR.'/group.class.php');
ClassLoader::add('UserFactory', KARIBOU_CLASS_DIR.'/userfactory.class.php');
ClassLoader::add('PermissionsFactory', KARIBOU_CLASS_DIR.'/permissionsfactory.class.php');
ClassLoader::add('CurrentUser', KARIBOU_CLASS_DIR."/currentuser.class.php");
ClassLoader::add('DaemonLoader', KARIBOU_CLASS_DIR."/daemonloader.class.php");
ClassLoader::add('KApp', KARIBOU_CLASS_DIR.'/kapp.class.php');
ClassLoader::add('AppList', KARIBOU_CLASS_DIR."/applist.class.php");
ClassLoader::add('Model', KARIBOU_CLASS_DIR.'/model.class.php');
ClassLoader::add('FormModel', KARIBOU_CLASS_DIR.'/formmodel.class.php');
ClassLoader::add('ModelFactory', KARIBOU_CLASS_DIR.'/modelfactory.class.php');
ClassLoader::add('ModelBuilder', KARIBOU_CLASS_DIR.'/modelbuilder.class.php');
ClassLoader::add('KSmarty', KARIBOU_CLASS_DIR."/ksmarty.class.php");
ClassLoader::add('LanguageManager', KARIBOU_CLASS_DIR."/languagemanager.class.php");
ClassLoader::add('Listener', KARIBOU_CLASS_DIR."/listener.class.php");
ClassLoader::add('Event', KARIBOU_CLASS_DIR."/event.class.php");
ClassLoader::add('EventManager', KARIBOU_CLASS_DIR."/eventmanager.class.php");
ClassLoader::add('MessageManager', KARIBOU_CLASS_DIR."/messagemanager.class.php");

ClassLoader::add('ObjectList', KARIBOU_LIB_DIR.'/objectlist.class.php');
ClassLoader::add('Visiteurs', KARIBOU_LIB_DIR."/visiteurs.class.php");
ClassLoader::add('KText', KARIBOU_LIB_DIR."/ktext.class.php");
ClassLoader::add('FormMessage', KARIBOU_LIB_DIR."/formmessage.class.php");
ClassLoader::add('HookManager', KARIBOU_LIB_DIR."/hookmanager.class.php");
ClassLoader::add('Krypt', KARIBOU_LIB_DIR."/krypt.class.php");
ClassLoader::add('Date', KARIBOU_LIB_DIR."/date.class.php");
ClassLoader::add('Date_Span', KARIBOU_LIB_DIR."/date_span.class.php");

ClassLoader::add('KeyChainFactory', KARIBOU_CLASS_DIR."/keychainfactory.class.php");
ClassLoader::add('KeyChain', KARIBOU_CLASS_DIR."/keychain.class.php");
ClassLoader::add('KeyChainSession', KARIBOU_CLASS_DIR."/keychainsession.class.php");
ClassLoader::add('KeyChainDB', KARIBOU_CLASS_DIR."/keychaindb.class.php");

ClassLoader::add('Geo', KARIBOU_LIB_DIR."/geo/geo.class.php");

/**
 * @todo move session_start() in a class
 */
session_start();

/**
 * Class autoload, uses ClassLoader
 * @param String
 */

function __autoload($className)
{
	//ExecutionTimer::getRef()->stop("building Karibou");	ExecutionTimer::getRef()->start("ALL __autoload");	ExecutionTimer::getRef()->start("Include __autoload (".$className.")");
	$file = ClassLoader::getFilename($className);
	
	if (!is_file($file))
		Debug::kill("Missing class : ".$className);
	else
		require_once $file;
	
	ExecutionTimer::getRef()->stop("Include __autoload (".$className.")");
	ExecutionTimer::getRef()->stop("ALL __autoload");	//ExecutionTimer::getRef()->start("building Karibou");
}

/**
 * Framework de l'intranet
 * @package framework
 */
class Karibou
{
	/**
	 * @var PDO
	 */
	protected $db;

	/**
	 * @var UserFactory
	 */
	protected $userFactory;

	/**
	 * @var UtilisateurCourant
	 */
	protected $currentUser;

	/**
	 * @var ListeApplis
	 */
	protected $appList;

	/**
	 * @var DaemonsList
	 */
	protected $daemonsList;

	/**
	 * @var String
	 */
	protected $txt_title;

	/**
	 * @var String
	 */
	protected $session_name;

	/**
	 * @var Page
	 */
	protected $page;
	
	/**
	 * @var KApp
	 */
	protected $app;
	
	/**
	 * @var String
	 */
	protected $appName;

	/**
	 * @var BaseUrl
	 */
	protected $baseUrl;

	/**
	 * @var Bool
	 */
	protected $affiche_popup = 0;
	
	protected $appHeader;
	protected $appFooter;
	
	//protected $languageManager;
	
	//Le gestionnaire d'accroche
	protected $hookManager;
	protected $languageManager;
	protected $eventManager;
	protected $messageManager;

	function __construct()
	{
		$this->session_name = $GLOBALS["config"]["session_name"];
		$this->txt_title = $GLOBALS["config"]["txt_title"];
	}

	/**
	 * Constructeur
	 */
	function build()
	{
		ExecutionTimer::getRef()->start("building Karibou");

		// va créer la page en fonction des paramètres dans l'url,
		// et crée l'objet BaseUrl
		$this->baseUrl = BaseURL :: getRef();
		//$this->baseUrl->parseURL($_SERVER['REQUEST_URI']);substr($_SERVER['REQUEST_URI'], strrpos($_SERVER['SCRIPT_NAME'], '/'))
		$this->baseUrl->parseURL($_SERVER['REQUEST_URI']);

		$this->connectDB();
		KeyChainFactory::$db = $this->db;
		
		// Récupération de l'utilisateur courant
		$this->userFactory = new UserFactory($this->db);
		$this->currentUser = $this->userFactory->getCurrentUser();

		//Instanciation du gestionnaire de langages
		
		$this->languageManager	= new LanguageManager();
		$this->hookManager		= new HookManager();
		$this->eventManager		= new EventManager();
		$this->messageManager	= new MessageManager();
		
		//Definition du langage de l'utilisateur
		//TODO -> A deplacer
		if( $lang = $this->currentUser->getPref("lang") )
		{
			$this->languageManager->setCurrentLanguage($lang);
		}
		$modelbuilder = new ModelBuilder();
		

		$this->appList = new AppList($modelbuilder, $this->db, $this->userFactory, 
			$this->languageManager, $this->hookManager, $this->eventManager, $this->messageManager );
		
		// on créé la page
		$this->appName = $this->baseUrl->getApp();
		$this->app = $this->appList->getApp( $this->appName );

		$urlParser = $this->baseUrl->getParser();
		
		// traitement des fonctions formulaire des applis
		if( $model = $this->app->doForm($urlParser) )
		{
			$modelbuilder->build();
			
			// On deconnecte de la base de données avant de commencer à afficher
			unset($this->db);
			if ( $location = $model->getLocation() )
			{
				header("Location: ".$location);
			}
		}
		else
		{
			$daemonLoader = new DaemonLoader($this->db, $this->userFactory, $this->appList,
				$this->languageManager, $this->hookManager, $this->eventManager, $this->messageManager );
			$daemonLoader->loadDaemonDir(KARIBOU_DAEMON_DIR);
			$this->eventManager->sendEvent("load");
			$this->eventManager->performActions();

			// construction de l'appli principale
			if( ! $this->app->buildPage($urlParser) )
			{
				$this->baseUrl->parseURL("/error/notfound/");
				$this->appName = $this->baseUrl->getApp();
				$this->app = $this->appList->getApp( $this->appName );
				$urlParser = $this->baseUrl->getParser();
				$this->app->buildPage($urlParser);
			}
			//(execute la methode build de chaque appli)
			$modelbuilder->build();
			
			// quand tout est instancié on lance le remplissage de la pile
			$this->userFactory->setUserList();
			
			// On deconnecte de la base de données avant de commencer à afficher
			unset($this->db);
		}
		ExecutionTimer::getRef()->stop("building Karibou");
	}

	function __destruct()
	{
		$this->userFactory->saveCurrentUser();
	}
	
	function translate () {

    }

	public function loadApp($name, $file)
	{
		Config::loadApp($name, $file);
	}
	
	public function loadAppDir($dirname, $configfile="config.xml")
	{
		$files = glob($dirname.'/*');
		foreach( $files as $f )
		{
			if( is_dir($f) && is_file($f."/".$configfile) )
			{
				$this->loadApp(basename($f), $f."/".$configfile);
			}
		}
	}

	/**
	 * Connexion à la base de données
	 */
	protected function connectDB()
	{
		try
		{
			$this->db = new Database(
				$GLOBALS['config']['bdd']["dsn"], 
				$GLOBALS['config']['bdd']["username"], 
				$GLOBALS['config']['bdd']["password"]);
/*
			$this->db = new PDO(
				$GLOBALS['config']['bdd']["dsn"], 
				$GLOBALS['config']['bdd']["username"], 
				$GLOBALS['config']['bdd']["password"]);
*/
			//$this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING); 
		}
		catch (PDOException $e)
		{
			Debug::kill($e->getMessage());
		}
	}

	public function fetch()
	{
		$html = "";
		$html .= $this->app->fetch(_BIG_);
		return $html;
	}
	public function display()
	{
		ExecutionTimer::getRef()->start("display Karibou");
		$this->app->display();
		ExecutionTimer::getRef()->stop("display Karibou");
	}

	protected function check_popups()
	{
		$flag = FALSE;
		foreach ($this->currentUser->popupApplis as $popupAppli)
		{
			if ($popupAppli)
			{
				$flag = $this->appList->getApp($popupAppli)->need_popup();
				if ($flag)
				{
					break;
				}
			}
		}
		return $flag;
	}

	protected function add_appli_popup()
	{
		foreach ($this->currentUser->popupApplis as $popupAppli)
		{
			if ($popupAppli)
			{
				$this->appList->add($popupAppli, _POPUP_);
			}
		}
	}
}



?>
