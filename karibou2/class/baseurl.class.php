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
	
	protected $base_url;
	protected $app;
	protected $arguments;

	private function __construct()
	{
		//$this->app = "accueil";
		if( isset($GLOBALS['config']["base_url"]) )
		{
			$this->base_url = $GLOBALS['config']["base_url"].'/';
		}
		else
		{
			die("il manque l'url de base dans le fichier de config de la community");
		}
		$this->arguments = '/';
	}
	
	static public function getRef()
	{
		if( self::$ref == NULL )
		{
			self::$ref = new BaseUrl();
		}
		return self::$ref;
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

		if (preg_match($GLOBALS['config']['netcv']['hostregexp'], $_SERVER["HTTP_HOST"]))
		{
			//Chargement d'un CV
			
			$this->app = $GLOBALS['config']['defaultapp'];
			if (preg_match('#^'.$this->base_url.'([0-9A-Za-z_\.]+)[/]*#',$p_url,$match))
			{
				$this->arguments = $match[1];
			}
		}
		else
		{
			//Chargement d'une page de l'Intranet
			
			//Requête de la page d'accueil
			if ( ereg("^".$this->base_url."[/]*$", $p_url) )
			{
				$this->app = $GLOBALS['config']['defaultapp'];
			}
			//Requête vers une application
			elseif(preg_match('#^'.$this->base_url.'([0-9A-Za-z_]+)(\S*|\z)#',$p_url,$match))
			{
				$this->app = $match[1];
			}
			
			if(isset($match[2]))
			{
				$this->arguments = $match[2];
			}
		}

		/*
		//Logout rajouté par DaT (2005.07.13 @ 11h37)
		//Il y a peut-être une méthode plus sympa de gérer le logout non ?
		else if (isset($_GET["reason"]) && $_GET["reason"] == "logout")
		{
			$this->app = $GLOBALS['config']['defaultapp'];
		}
		else
		{
			die("Erreur de parse URL");
		}
		*/
	}

	function getApp()
	{
		return $this->app;
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
