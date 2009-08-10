<?php
/**
 * @copyright 2005 Jonathan Semczyk <jonathan.semczyk@free.fr>
 *
 * @license http://www.gnu.org/licenses/lgpl.html Lesser GNU Public License
 * See the enclosed file COPYING for license information (LGPL).
 * 
 * @package framework
 **/

require_once KARIBOU_CLASS_DIR.'/executiontimer.class.php';
require_once KARIBOU_CLASS_DIR.'/debug.class.php';

/**
 * Stores an array of all class we could need
 * mostly used for __autoload function
 * 
 * @package framework
 */
class ClassLoader
{
	protected static $ref;
	
	protected static $files=array();

	public static function add( $class, $file, $context = "_global_")
	{
		if (!isset(self::$files[$context]))
			self::$files[$context] = array();

		self::$files[$context][$class] = $file;
	}
	
	public static function getFilename($class)
	{
		foreach (self::$files as $context => $classes) {
			if (isset($classes[$class])) return $classes[$class];
		}
		Debug::kill("ClassLoader : La classe $class ne peut etre chargee");
	}
	public static function unloadContext($context) {
		// One day, it'll perhaps be possible to unset classes in PHP... But we can't do that right now (it expects T_PAAMAYIM_NEKUDOTAYIM...)
		// So far, we only remove the context...
		if (isset(self::$files[$context]))
			unset(self::$files[$context]);
	}
}

/**
 * Class autoload, uses ClassLoader
 * @param String
 */

function __autoload($className)
{
	//ExecutionTimer::getRef()->stop("building Karibou");
	ExecutionTimer::getRef()->start("ALL __autoload");
	ExecutionTimer::getRef()->start("Include __autoload (".$className.")");
	$file = ClassLoader::getFilename($className);
	
	if (!is_file($file))
		Debug::kill("Missing class : ".$className);
	else
		require_once $file;
	
	ExecutionTimer::getRef()->stop("Include __autoload (".$className.")");
	ExecutionTimer::getRef()->stop("ALL __autoload");
	//ExecutionTimer::getRef()->start("building Karibou");
}

?>
