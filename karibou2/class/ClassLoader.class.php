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
 * Stores an array of all class we could need
 * mostly used for __autoload function
 * 
 * @package framework
 */
class ClassLoader
{
	protected static $ref;
	
	protected static $files=array();
	
	public static function add( $class, $file)
	{
		self::$files[$class] = $file;
	}
	
	public static function getFilename($class)
	{
		if( isset(self::$files[$class]) ) return self::$files[$class] ;
		Debug::kill("ClassLoader : La classe $class ne peut etre chargee");
	}
}
?>
