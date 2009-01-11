<?php
/**
 * @copyright 2004 Jonathan Semczyk <jonathan.semczyk@free.fr>
 * @copyright 2003 Pierre Laden <pladen@elv.enic.fr>
 *
 * @license http://www.gnu.org/licenses/lgpl.html Lesser GNU Public License
 * See the enclosed file COPYING for license information (LGPL).
 *
 * @package framework
 **/

/**
 * Classe de base d'une appli
 * @package framework
 */
class KApp
{
	/**
	 * Nom de la classe
	 * @var String
	 */
	protected $name;

	/**
	 * titre de l appli instanciée
	 * @var String
	 */
	protected $titre			= 'appli_abstract';
	
	/**
	 * Liste des permissions
	 * @var Permissions
	 */
	protected $permissions;
	
	/**
	 * @var int
	 */
	protected $permission;

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

	/**
	 * @var ModelFactory
	 */
	protected $modelFactory;

	/**
	 * @var Array
	 */
	protected $viewList;
	protected $appList;
	
	protected $html; //Ajouté lors de la descente du model factory et de la suppression des versions

	/**
	 * @var BOOL
	 */
	protected $erreur = FALSE;
	
	protected $templatedir;
	
	protected $eventManager;
	protected $messageManager;
	protected $xmlconfig;
	protected $argArray = false;

	protected $config = array();
	protected $contentType;

	/**
	 * cette fonction construit la partie commune des applis
	 *
	 * @param string $name
	 * @param string $configfile
	 * @param PDO $db
	 * @param Int $versions_asked
	 * @param Int $permission
	 */
	function __construct(
		$name, 
		$configfile, 
		ModelBuilder $modelbuilder, 
		PDO $db, 
		UserFactory $userFactory, 
		AppList $appList, 
		$permission, 
		HookManager $hookManager,
		EventManager $eventManager,
		MessageManager $messageManager,
		KSmarty $smarty
		 )
	{
//		Debug::display("Building KApp ".$name." ($permission)");
		$this->name = $name;
		$this->db = $db;
		$this->currentUser = $userFactory->getCurrentUser();
		$this->userFactory = $userFactory;
		$this->appList = $appList;
		
		$this->relativeDir = dirname($configfile);
		
		$this->messageManager = $messageManager;

		$this->viewList = array();

		$this->hookManager = $hookManager;
		$this->eventManager = $eventManager;

		$this->modelFactory = new ModelFactory($modelbuilder, $this->db, $this->userFactory, 
			$appList, $this->hookManager, $this->eventManager, $this->messageManager, $smarty);
		
		$this->permission = $permission;
		//Lecture des configs
		$this->xmlconfig = new XMLCache( KARIBOU_CACHE_DIR.'/'.$this->name );
		$this->xmlconfig->loadFile( $configfile );
		$this->readConfig( $this->xmlconfig->getXML() );
	}

	function __destruct()
	{
	}
	
	protected function getArgArray()
	{
		if( $this->argArray ) return $this->argArray;
		
		$xml = $this->xmlconfig->getXML();
		foreach( $xml->page as $page )
		{
			if( isset($page['name']) )
			{
				$p = $page['name'];
			}
			else
			{
				$p = "";
			}
			if( !isset($this->argArray[$p]) ) $this->argArray[$p] = array();
			if( isset($page->argument) )
			{
				foreach($page->argument as $argument)
				{
					$this->argArray[$p][$argument['name']] = $argument['class'];
				}
			}
			if( isset($page->option) )
			{
				foreach($page->option as $option)
				{
					$this->argArray[$p][$option['name']] = $option['class'];
				}
			}
		}
		return $this->argArray;
	}

	function getArgClass( $pagename , $argname )
	{
		$argArray = $this->getArgArray();
		if( isset($argArray[$pagename], $argArray[$pagename][$argname]) )
		{
			return $argArray[$pagename][$argname];
		}
		Debug::kill("no arg $argname for page $pagename");
		return false;
	}

	function getConfig()
	{
		return $this->config;
	}

	/**
	 * Lecture des configurations de l'appli
	 */
	protected function readConfig(&$xml)
	{
		if( isset($xml['templatedir']) )
		{
			$this->templatedir = $this->relativeDir.'/'.$xml['templatedir'].'/';
		}
		else
		{
			$this->templatedir = $this->relativeDir.'/templates/';
		}
		
		$this->configViewList = array();
		if( isset($xml->view) )
		{
			foreach($xml->view as $view)
			{
				$this->configViewList[$view['name']] = $view;
			}
		}

		if(isset($xml->load))
		{
			foreach($xml->load as $loadclass)
			{
				ClassLoader::add($loadclass['class'], $this->relativeDir.'/'.$loadclass['file']);
			}
		}
		
		if($this->permission == _DEFAULT_)
		{
			if(isset($xml->permissions))
			{
				if($this->currentUser->isLogged())
				{
					$this->permission = KPermissions::getFromText($xml->permissions[0]['logged']);
				}
				else
				{
					$this->permission = KPermissions::getFromText($xml->permissions[0]['default']);
				}
			}
		}

		$this->config = array();
		if(isset($xml->config, $xml->config[0]->param))
		{
			$this->config = $this->readConfigTree($xml->config[0]);
		}
        if (isset($xml->hook))
        {
            foreach ($xml->hook as $hookXML)
            {
                $this->addView($hookXML["view"], $hookXML["name"]);
            }
        }
	}
	
	function readConfigTree ($tree)
	{
		$varb = array();
		$varc = array();

		if (isset($tree->text) && $tree->text != "")
		{
			if (isset($param["name"]))
			{
				$vara = array();
				$vara[$param["name"]] = $tree->text;
			}
			else
			{
				$vara = $tree->text;
			}
		}
		if (!isset($vara) || !is_string($vara))
		{
			if (isset($tree->param) && count($tree->param)>0)
			{
				foreach($tree->param as $param)
				{
					if (isset($param["name"]))
					{
						if (!isset($param["value"]))
						{
							$varb[$param["name"]] = $this->readConfigTree($param);
						}
						else
						{
							$varb[$param["name"]] = $param["value"];
						}
					}
					else
					{
						$varb[] = $this->readConfigTree($param);
					}
				}
			}
			
			if (isset($tree->value))
			{
				if (is_array($tree->value) && count($tree->value)>0 )
				{
					foreach ($tree->value as $value)
					{
						if (isset($value["name"]))
						{
							$varc[$value["name"]] = $this->readConfigTree($value);
						}
						else
						{
							$varc[] = $this->readConfigTree($value);
						}
					}
				}
				else
				{
					if (isset($tree->value["name"]))
					{
						$varc[$tree->value["name"]] = $this->readConfigTree($tree->value);
					}
					else
					{
						$varc[] = $this->readConfigTree($tree->value);
					}
				}
			}

			if (isset($vara,$varb,$varc))
			{
				$var = array_merge($vara, $varb, $varc);
			}
			elseif (isset($vara,$varb))
			{
				$var = array_merge($vara, $varb);
			}
			elseif (isset($varb,$varc))
			{
				$var = array_merge($varb, $varc);
			}
			else
			{
				$var = array_merge($vara, $varc);
			}
			
		}
		elseif (isset($vara))
		{
			$var = $vara;
		}
		else
		{
			return NULL;
		}

		return $var;
	}
	
	/**
	 * Fonction pour ajouter une vue à la liste à construire
	 */
	function addView($name, $hook="default", $args=array() )
	{
		//echo "KApp(" . $this->name . "::addView($name, $hook)\n";
		if( $this->permission > _NO_ACCESS_ )
		{
			if( !isset($this->configViewList[$name]) ) return false;
			$configview =  $this->configViewList[$name];
			
			Debug::display("Adding new view : ".$name." (".$configview['class'].") => ".$hook);
			
			$model = $this->modelFactory->getModel($configview['class'],
				$this->name,
				$this->templatedir,
				$this->permission,
				$args );
			if ($model->getPermission() > _NO_ACCESS_) {
				$this->hookManager->addView($hook, $model, $configview["template"]);
				return $model;
			}
		}
		$err = $this->appList->getApp('error');
		return $err->addView('noaccess', $hook);
	}
	
	/**
	 * Construction d'une Page
	 */
	function buildPage($urlParser)
	{
		$urlParser->createPageParser($this->xmlconfig->getXML());
		if( !($page = $urlParser->parse()) )
		{
			Debug::display($this->name." : erreur de parse de la page et des arguments");
			//Debug::kill($urlParser);
			return false;
		}
		
		// on va chercher dans la config de l'appli instanciée si elle
		// utilise la class Header par défaut ou une autre classe
		if( $header = $page->getHeader() )
		{
			$app = $this->appList->getApp( $header['app']  );
			$app->addView($header['view'], "header");
		}
		if( $footer = $page->getFooter() )
		{
			$app = $this->appList->getApp( $footer['app']  );
			$app->addView($footer['view'], "footer");
		}
		$this->contentType = $page->getContentType();
		
		if( ! $this->addView($page->getViewName(), "default", $page->getArguments()) )
		{
			Debug::kill("Impossible de creer la vue : ".$page->getViewName());
		}
		return true;
	}

	/**
	 * retourne le code html de la version demandée
	 *
	 * @param Int $version_asked
	 * @return String
	 */
	function fetch()
	{
		$this->html = $this->hookManager->fetch("header");
		$this->html .= $this->hookManager->fetch("default");
		$this->html .= $this->hookManager->fetch("footer");
		return $this->html;
	}
	function display()
	{
		// We'll look for a Content-Type property
		if($this->contentType !== null) header("Content-Type: ".$this->contentType);
	
		$this->hookManager->display("header");
		$this->hookManager->display("default");
		$this->hookManager->display("footer");
	}
	
	public function doForm(URLParser $urlParser)
	{
		if( $this->permission > _NO_ACCESS_ )
		{
			$urlParser->createFormParser($this->xmlconfig->getXML());
			if($form = $urlParser->parseForm())
			{
				$model = $this->modelFactory->getModel($form->getModelName(),
					$this->name,
					$this->templatedir,
					$this->permission, 
					array() );
				$class = get_class($model);
				if( get_parent_class($class) != "FormModel" )
				{
					Debug::kill($class." must extends FormModel");
				}
				$model->setForm($form);
				return $model;
			}
		}
		return false;
	}

	public function getPermission() {
		return $this->permission;
	}
}

?>
