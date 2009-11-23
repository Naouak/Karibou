<?php
/**
 * @copyright 2004 Jonathan Semczyk <jonathan.semczyk@free.fr>
 *
 * @license http://www.gnu.org/licenses/lgpl.html Lesser GNU Public License
 * See the enclosed file COPYING for license information (LGPL).
 * 
 * @package framework
 **/

require_once dirname(__FILE__).'/urlparser.class.php';

/**
 * Used to fetch appname, pagename and create an arguments array
 * 
 * @package framework
 */
class BaseUrl
{
	static private $ref;
	private $current_user;
	
	protected $base_url;
	protected $app;
	protected $arguments;

	private function __construct()
	{
		if( isset($GLOBALS['config']["base_url"]) )
		{
			$this->base_url = $GLOBALS['config']["base_url"].'/';
		}
		else
		{
			die("il manque l'url de base dans le fichier de config de la community");
		}
		$this->arguments = '/';
		$this->current_user = null;
	}
	
	static public function getRef()
	{
		if( self::$ref == NULL )
		{
			self::$ref = new BaseUrl();
		}
		return self::$ref;
	}

	function setCurrentUser(CurrentUser $currentUser)
	{
		$this->current_user = $currentUser;
	}
	
	function getBaseUrl()
	{
		return $this->base_url;
	}

	function parseURL($p_url=null)
	{
		if(!isset($p_url))
		{
			$p_url = $_SERVER['REQUEST_URI'];
		}

		
			//Chargement d'une page de l'Intranet
			//Requête de la page d'accueil
			if ( preg_match('/^'.addcslashes($this->base_url, "/")."[\\/]*$/", $p_url) )
			{
				$this->app = $GLOBALS['config']['defaultapp'];
				if ($this->current_user != null)
					if ($this->current_user->isLogged() && isset($GLOBALS['config']['defaultloggedapp']))
						$this->app = $GLOBALS['config']['defaultloggedapp'];
			}
			//Recherche pour déterminer si nous sommes dans un espace
			elseif (preg_match('#^'.$this->base_url.'([0-9A-Za-z_.]+)(\S*|\z)#',$p_url,$match))
			{
				if (isset($GLOBALS['config']['spaces']) && array_key_exists($match[1], $GLOBALS['config']['spaces']))
				{
					//Nous sommes dans l'espace $match[1]
					$GLOBALS['config']['current_space']			= $GLOBALS['config']['spaces'][$match[1]];
					$GLOBALS['config']['current_space']['id']	= $match[1];
					
					//Vérification que nous avons une application demandée
					if (preg_match('#^[/]{0,1}([0-9A-Za-z_.]+)(\S*|\z)#',$match[2],$match2))
					{
						//Une application est demandée ($match2[1])
						$this->app = $match2[1];
					}
					else
					{
						//Aucune application demandée : chargement de la page d'accueil
						$this->app = $GLOBALS['config']['current_space']['defaultapp'];
					}
					if(isset($match2[2]))
					{
						$this->arguments = $match2[2];
					}
				}
				else
				{
					//Nous sommes dans l'espace par défaut
					$this->app = $match[1];
					if(isset($match[2]))
					{
						$this->arguments = $match[2];
					}
				}
			}
	}

	function getApp()
	{
		return $this->app;
	}

	function getArguments() {
		return $this->arguments;
	}

	/**
	 *
	 */
	function getParser()
	{
		return new URLParser($this->app, $this->arguments);
	}
}

?>
