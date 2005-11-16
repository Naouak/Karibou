<?php
/**
 * @version
 * @copyright 2004 Jonathan Semczyk <jonathan.semczyk@free.fr>
 *
 * @license http://www.gnu.org/licenses/lgpl.html Lesser GNU Public License
 * See the enclosed file COPYING for license information (LGPL).
 * 
 * @package framework
 **/


/**
 * Gestion de la configuration
 *
 * @package framework
 */
 
class Config
{
	/**
	 * Tableau d'applis
	 * @var Array
	 */
	protected static $apps = array();
	protected static $config = array();

	protected static $header = array();
	protected static $footer = array();
	
	/**
	 * Constructeur privé pour créer un singleton
	 */
	protected function __construct()
	{
	}

	public function loadApp($name, $configFile)
	{
		if( ! is_file($configFile) ) Debug::kill("config.class.php : cannot open : ".$configFile);
		self::$apps[$name] = $configFile;
	}

	public function isAppLoaded($appname)
	{
		if( isset(self::$apps[$appname]) )
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	
	public function getAppConfig($appname)
	{
		if( isset(self::$apps[$appname]) )
		{
			return self::$apps[$appname];
		}
		else
		{
			return false;
		}
	}
	
	public function appReset()
	{
		reset(self::$apps);
	}
	
	public function appEach()
	{
		return each(self::$apps);
	}

}

?>