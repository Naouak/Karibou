<?php   
/**
 * @version 0.1
 * @copyright 2003 Jonathan Semczyk <jonathan.semczyk@free.fr>
 * @copyright 2003 Pierre Laden <pladen@elv.enic.fr>
 *
 * @license http://www.gnu.org/licenses/lgpl.html Lesser GNU Public License
 * See the enclosed file COPYING for license information (LGPL).
 * 
 * @package framework
 **/

/**
 * Une liste d'applications
 *
 * @package framework
 */

class AppList extends ObjectList
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
	 * @var BaseUrl
	 */
	protected $baseUrl;

	/**
	 * @var Annuaire
	 */
	protected $annuaire;

	/**
	 * @var Permissions
	 */
	protected $permissions;
	
	protected $languageManager;
	protected $hookManager;
	protected $messageManager;

	/**
	 * @param PDO $db
	 * @param BaseUrl $baseUrl
	 * @param Annuaire $annuaire
	 * @param Permissions $permissions
	 */
	public function __construct(ModelBuilder $modelbuilder, PDO $db, UserFactory $userFactory,
		LanguageManager $languageManager, HookManager $hookManager, EventManager $eventManager, MessageManager $messageManager)
	{
		parent::__construct();
		$this->modelbuilder = $modelbuilder;
		$this->db = $db;
		$this->currentUser = $userFactory->getCurrentUser();
		$this->baseUrl = BaseURL :: getRef();
		$this->userFactory = $userFactory;
		$this->languageManager = $languageManager;
		$this->hookManager = $hookManager;
		$this->eventManager = $eventManager;
		$this->messageManager = $messageManager;

		$permissionsFactory = new PermissionsFactory($this->db);
		$this->permissions = $permissionsFactory->getPermissions($this->currentUser);
		unset ($permissionsFactory);
	}

	function __destruct()
	{
		unset ($this->appliVide);
	}

	/**
	 * Prépare l'applis dans la liste pour toutes les créer en même temps
	 * @param string $name
	 */
	function getApp($name)
	{
		if( empty($name) ) $name = "default";
		// on vérifie qu'on a pas deja une instance de l'applis
		if (isset ($this->data[$name]))
		{
			return $this->data[$name];
		}
		if (!Config :: isAppLoaded($name))
		{
			Debug::display("L'Appli n'est pas charg&eacute;e : ".$name);
			if( $name != 'error' ) return $this->getApp('error');
			Debug::kill("App error does not exist");
		}

		//$droits = $this->permissions->get($appli);
		$configfile = Config::getAppConfig($name);
		
		$this->data[$name] = new KApp(
			$name,
			$configfile,
			$this->modelbuilder,
			$this->db,
			$this->userFactory,
			$this,
			$this->permissions->get($name),
			$this->languageManager,
			$this->hookManager,
			$this->eventManager,
			$this->messageManager
			 );
		return $this->data[$name];

	}

	/**
	 * Lance les constructeurs de toutes les applis préparées
	 */
	function buildPages()
	{
		foreach ($this as $app)
		{
			$app->buildPage();
		}
	}
	
	/**
	 * Creation des models
	 */
	function buildModels()
	{
		foreach ($this as $app)
		{
			$app->buildModels();
		}
	}
}

?>