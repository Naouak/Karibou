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

ClassLoader::add('Database', KARIBOU_CLASS_DIR.'/database.class.php');
ClassLoader::add('Debug', KARIBOU_CLASS_DIR.'/debug.class.php');
ClassLoader::add('ErrorHandler', KARIBOU_CLASS_DIR.'/errorhandler.class.php');
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
ClassLoader::add('EmptyModel', KARIBOU_CLASS_DIR.'/EmptyModel.class.php');
ClassLoader::add('FormModel', KARIBOU_CLASS_DIR.'/formmodel.class.php');
ClassLoader::add('ModelFactory', KARIBOU_CLASS_DIR.'/modelfactory.class.php');
ClassLoader::add('ModelBuilder', KARIBOU_CLASS_DIR.'/modelbuilder.class.php');
ClassLoader::add('KSmarty', KARIBOU_CLASS_DIR."/ksmarty.class.php");
ClassLoader::add('Listener', KARIBOU_CLASS_DIR."/listener.class.php");
ClassLoader::add('Event', KARIBOU_CLASS_DIR."/event.class.php");
ClassLoader::add('EventManager', KARIBOU_CLASS_DIR."/eventmanager.class.php");
ClassLoader::add('MessageManager', KARIBOU_CLASS_DIR."/messagemanager.class.php");
ClassLoader::add('AuthManager', KARIBOU_CLASS_DIR."/authManager.class.php");

ClassLoader::add('ObjectList', KARIBOU_LIB_DIR.'/objectlist.class.php');
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

ClassLoader::add('KForm', KARIBOU_CLASS_DIR."/kform.class.php");
ClassLoader::add('KFormField', KARIBOU_CLASS_DIR."/kformfield.class.php");
ClassLoader::add('KFormFactory', KARIBOU_CLASS_DIR."/kformfactory.class.php");

ClassLoader::add('CommentSource', KARIBOU_CLASS_DIR."/commentsource.class.php");
ClassLoader::add('Score', KARIBOU_CLASS_DIR."/votes.class.php");


/**
 * @todo move session_start() in a class
 */
session_start();

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
	 * @var ErrorHandler
	 */
	protected $errorHandler;

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

	protected $appHeader;
	protected $appFooter;
	
	
	//Le gestionnaire d'accroche
	protected $hookManager;
	protected $eventManager;
	protected $messageManager;
	
	//Langue courante
	protected $currentLanguage;

	function __construct()
	{
	}

	/**
	 * Constructeur
	 */
	function build()
	{
		ExecutionTimer::getRef()->start("building Karibou");

		try {
			$this->connectDB();
			KeyChainFactory::$db = $this->db;
		}
		catch (PDOException $e)
		{
			Debug::kill($e->getMessage());
			die("Could not connect to database." . "\n". "Please check the database name, host, login and password.");
		}

		// Récupération de l'utilisateur courant
		$this->userFactory = new UserFactory($this->db);
		$this->currentUser = $this->userFactory->getCurrentUser();

		// Création de la page en fonction des paramètres dans l'url, et création de l'objet BaseUrl
		$this->baseUrl = BaseURL :: getRef();
		$this->baseUrl->setCurrentUser($this->currentUser);
		$this->baseUrl->parseURL($_SERVER['REQUEST_URI']);

		$this->hookManager		= new HookManager();
		$this->eventManager		= new EventManager();
		$this->messageManager	= new MessageManager();
		
		//Definition du langage de l'utilisateur
		//TODO -> A deplacer
		$lang = $this->currentUser->getPref("lang");
        if (isset($lang) && $lang != '')
        {
            if (substr($lang,0,2) == 'en')
            {
                $this->currentLanguage = 'en_US.utf-8';
            }
            else
            {
                $this->currentLanguage = 'fr_FR.utf-8';
            }
        } else {
            $this->currentLanguage = 'fr_FR.utf-8';
        }
        	// On commence la gestion des erreurs
        	$this->errorHandler = new ErrorHandler($this->db, $this->currentUser);
        
		putenv("LANG=".$this->currentLanguage); 
		setlocale(LC_ALL, $this->currentLanguage, substr($this->currentLanguage, 0, 2));
		$domain = 'messages';
		bindtextdomain($domain, KARIBOU_LOCALE_DIR); 
		bind_textdomain_codeset($domain, 'utf-8');
		textdomain($domain);

		$modelbuilder = new ModelBuilder();

		$this->appList = new AppList($modelbuilder, $this->db, $this->userFactory, 
		$this->hookManager, $this->eventManager, $this->messageManager );
		
		// on créé la page
		$this->appName = $this->baseUrl->getApp();
		$this->app = $this->appList->getApp( $this->appName );

		$urlParser = $this->baseUrl->getParser();
		
		$this->requestedAppName = $urlParser->getAppName();
		$this->requestedPageName = $urlParser->getPageName();

		//Chargement du daemonloader et du hookmanager ici pour permettre le logout
		$daemonLoader = new DaemonLoader($this->db, $this->userFactory, $this->appList,
		$this->hookManager, $this->eventManager, $this->messageManager );
		$daemonLoader->loadDaemonDir(KARIBOU_DAEMON_DIR);

		$this->eventManager->sendEvent("running");
		$this->eventManager->performActions();

		// traitement des fonctions formulaire des applis
		if( $model = $this->app->doForm($urlParser) )
		{
			$modelbuilder->build();
			
			// Il faut détruire le gestionnaire d'erreurs
			unset($this->errorHandler);
			// On deconnecte de la base de données avant de commencer à afficher
			unset($this->db);

			if ( $location = $model->getLocation() )
			{
				header("Location: ".$location);
			}
		}
		else
		{
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
        if($this->userFactory)
    		$this->userFactory->saveCurrentUser();
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
		//Prise en compte des espaces (si la variable de base de données est initialisée)
		if (isset($GLOBALS['config']['current_space']['bdd']['appsdb']))
		{
			$dsn = 'mysql:dbname='.$GLOBALS['config']['current_space']['bdd']['appsdb'].';host=localhost';
		}
		else
		{
			$dsn = $GLOBALS['config']['bdd']['dsn'];
		}

		Database::initialize(
				$dsn, 
				$GLOBALS['config']['bdd']["username"], 
				$GLOBALS['config']['bdd']["password"]);
		$this->db = Database::instance();
	}

	public function display()
	{
		ExecutionTimer::getRef()->start("display Karibou");
		$this->app->display();
		ExecutionTimer::getRef()->stop("display Karibou");
	}

}

?>
