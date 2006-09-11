<?php
/**
 * @copyright 2004 Jonathan Semczyk <jonathan.semczyk@free.fr>
 *
 * @license http://www.gnu.org/licenses/lgpl.html Lesser GNU Public License
 * See the enclosed file COPYING for license information (LGPL).
 *
 * @package framework
 **/

require_once dirname(__FILE__)."/pageparser.class.php";
require_once dirname(__FILE__)."/formparser.class.php";

/**
 * Create PageParser and FormParser from XML config
 *
 * @package framework
 */
class URLParser
{
	protected $url;
	protected $pages;
	protected $forms;
	
	protected $appname;
	protected $pagename;
	protected $args;
	
	protected $defaultHeader;
	protected $defaultFooter;
	
	/**
	 * @param $url Typiquement ce devra être le REQUEST_URI
	 */
	function __construct($appname, $url = "")
	{
		$this->url = $url;
		$this->pages = array();
		$this->forms = array();
		
		//Gestion des CV (pour les liens dans les CV
		if (preg_match($GLOBALS['config']['netcv']['hostregexp'], $_SERVER["HTTP_HOST"]))
		{
			//Cas d'une requête vers un CV

			$this->appname = "";
			$this->pagename = "";
			$this->args = array();

			//Récupération de la langue, du fichier CSS, ou autre
			if (preg_match('#^([0-9A-Za-z_\.]+)#',$this->url,$match))
			{
				$this->args = array($match[1]);
				
				//Si l'argument est un fichier CSS, on charge le module d'affichage de CSS
				if (preg_match('/\.css$/i', $match[1]))
				{
					$this->pagename = "cvskindisplay";
				}
			}
		}
		else
		{
			//Cas d'une requête Intranet

			$this->appname = $appname;

			//Requête vers la page par défaut
			if ( empty($this->url) || ereg("^/$", $this->url) || ereg("^/\?", $this->url) )
			{
				$this->pagename = "";
				$this->args = array();
			}
			else
			{
				$url_tab = explode('/', $this->url);
				// 0 => vide 1 => nom 2 => args
				// 0 => vide 1 => args
				if( count($url_tab) > 2 )
				{
					$this->pagename = $url_tab[1];
					$url_args = $url_tab[2];
				}
				else if( count($url_tab) > 1 )// pas de nom de page
				{
					$this->pagename = "";
					$url_args = $url_tab[1];
				}
				else
				{
					$this->pagename = "";
					$url_args = "";
				}
				if( $url_args !== "" )
				{
					$split_array = explode('?', $url_args) ;
					if( $split_array[0] !== "" )
					{
						$this->args = explode(',', $split_array[0]) ;
					}
					else
					{
						$this->args = array();
					}
				}
				else
				{
					$this->args = array();
				}
			}
		}
	}
	
	/**
	 * Méthodes permettant de récupérer le nom de l'appli demandée et de la page
	 */
	function getAppName()
	{
		return $this->appname;
	}
	function getPageName()
	{
		return $this->pagename;
	}
	
	/**
	 * Permet d'ajouter un nouveau masque
	 */
	function newPage($name, $viewname)
	{
		$manager = new PageParser($name, $viewname);
		$this->pages[] = array(
			'name' => $name,
			'manager' => $manager);
		return $manager;
	}
	function newForm($name, $modelname)
	{
		$manager = new FormParser($name, $modelname);
		$this->forms[] = array(
			'name' => $name,
			'manager' => $manager);
		return $manager;
	}
		
	/**
	 * la fonction qui va récupérer le bon masque
	 */
	function parse()
	{
		foreach( $this->pages as $page )
		{
			if( $page['name'] == $this->pagename )
			{
				if( $page['manager']->match( $this->args ) !== false )
				{
					return $page['manager'];
				}
			}
		}
		return false;
	}
	
	function parseForm()
	{
		//Debug::kill($this);
		foreach( $this->forms as $form )
		{
			if( $form['name'] == $this->pagename )
			{
				return $form['manager'];
			}
		}
		return false;
	}
	
	public function getPage($pagename)
	{
		foreach( $this->pages as $page )
		{
			if( $page['name'] == $pagename )
			{
				return $page['manager'];
			}
		}
	}
	
	public function getPagesArray()
	{
		return $this->pages;
	}
	
	public function setPagesArray($pages)
	{
		$this->pages = $pages;
	}
	public function setFormsArray($forms)
	{
		$this->forms = $forms;
	}
	function setDefaulHeader()
	{
	}
	
	public function build(&$xmlcache, $cached=false)
	{
		if( $cached )
		{
			$pagecachefile = $xmlcache->getCacheDir().'/'.$xmlcache->getCacheId().'_page';
			$formcachefile = $xmlcache->getCacheDir().'/'.$xmlcache->getCacheId().'_form';
	
			if( is_file($pagecachefile) && 
				($xmlcache->getLastModified() < filectime($pagecachefile)) )
			{
				$this->pages = unserialize(file_get_contents($pagecachefile));
				Debug::display("Reading Cache File ".$pagecachefile);
			}
			else
			{
				$appChildren = $xmlcache->getXML()->getChildren();
				if( isset($appChildren['page']) )
				{
					$this->createPageParser($appChildren['page']);
					file_put_contents($pagecachefile, serialize($this->pages));
					Debug::display("Creating Cache File ".$pagecachefile);
				}
			}
			if( is_file($formcachefile) && 
				($xmlcache->getLastModified() < filectime($formcachefile)) )
			{
				$this->forms = unserialize(file_get_contents($formcachefile));
				Debug::display("Reading Cache File ".$formcachefile);
			}
			else
			{
				$appChildren = $xmlcache->getXML()->getChildren();
				if( isset($appChildren['form']) )
				{
					$this->createFormParser($appChildren['form']);
					file_put_contents($formcachefile, serialize($this->forms));
					Debug::display("Creating Cache File ".$formcachefile);
				}
			}
			//print_r($this->urlManager);
		}
		else
		{
			$this->createPageParser($xmlcache->getXML());
			$this->createFormParser($xmlcache->getXML());
		}
	}
	
	function createPageParser($xml)
	{
        if( isset($xml->header) )        {            $default_header = array( 
            	'app' => $xml->header[0]['app'], 
            	'view' => $xml->header[0]['view']);        }        else        {            $default_header = false;        }        if( isset($xml->footer) )        {            $default_footer =  array( 
            	'app' => $xml->footer[0]['app'], 
            	'view' => $xml->footer[0]['view']);        }        else        {            $default_footer = false;        }
        
		if( !isset($xml->page) ) return ;
		foreach($xml->page as $page)
		{
			if( isset($page['name']) )
			{
				$pagename = $page['name'];
			}
			else
			{
				$pagename = "";
			}
			if( !isset($page['view']) )
			{
				Debug::kill("Missing view for a page in XML config file");
			}
			$myPage = $this->newPage($pagename, $page['view']);
			if( isset($page->header) )
			{
				$myPage->setHeader($page->header[0]['app'], $page->header[0]['view']);
			}
			else if( $default_header )
			{
				$myPage->setHeader($default_header['app'], $default_header['view']);
			}
			if( isset($page->footer) )
			{
				$myPage->setFooter($page->footer[0]['app'], $page->footer[0]['view']);
			}
			else if ( $default_footer)
			{
				$myPage->setFooter( $default_footer['app'], $default_footer['view'] );
			}
			
			if( isset($page->argument) )
			{
				foreach($page->argument as $argument)
				{
					$class = $argument['class'];
					$myPage->manageArgument($argument['name'], new $class(), true);
				}
			}
			if( isset($page->option) )
			{
				foreach($page->option as $option)
				{
					$class = $option['class'];
					$myPage->manageArgument($option['name'], new $class(), false);
				}
			}
		}	
	}

	function createFormParser($xml)
	{
		if( !isset($xml->form) ) return ;
		foreach($xml->form as $form)
		{
			if( isset($form['action']) )
			{
				$action = $form['action'];
			}
			else
			{
				$action = "";
			}
			if( !isset($form['class']) )
			{
				Debug::kill("Form : ".$action." need a class");
			}
			$myform = $this->newForm($action, $form['class'] );
			if( isset( $form->redirect ) )
			{
				$myform->redirect();
				if( isset($form->redirect[0]['app']) )
				{
					$myform->setRedirectArg('app', $form->redirect[0]['app']);
				}
				else
				{
					$myform->setRedirectArg('app', $this->appname);
				}
				if( isset($form->redirect[0]['page']) )
				{
					$myform->setRedirectArg('page', $form->redirect[0]['page']);
				}
				else
				{
					$myform->setRedirectArg('page', $this->pagename);
				}
				if( isset($form->redirect[0]->argument) )
				{
					foreach($form->redirect[0]->argument as $arg)
					{
						$myform->setRedirectArg( $arg['class'], $arg['value']);
					}
				}
			}
			else if( isset($form->referer) )
			{
				$myform->referer();
			}
			else
			{
				// nothing to do after form is done ...
				//Debug::kill("Erreur xml : manque une cible du formulaire");
			}
		}
	}
}


?>
