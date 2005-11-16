<?php
/**
 * @copyright 2005 Jonathan Semczyk <jonathan.semczyk@free.fr>
 *
 * @license http://www.gnu.org/licenses/lgpl.html Lesser GNU Public License
 * See the enclosed file COPYING for license information (LGPL).
 * 
 * @package framework
 **/

ClassLoader::add('KeyChain', KARIBOU_CLASS_DIR.'/keychain.class.php');
ClassLoader::add('KeyChainDB', KARIBOU_CLASS_DIR.'/keychaindb.class.php');
ClassLoader::add('KeyChainSession', KARIBOU_CLASS_DIR.'/keychainsession.class.php');

/**
 * @package framework
 */
class KeyChainFactory
{
	public static $db;

	public static function getKeyChain(CurrentUser $user)
	{
		if (isset($GLOBALS['config']['keychain'], $GLOBALS['config']['keychain']['storage']))
		{
			switch( $GLOBALS['config']['keychain']['storage'] )
			{
				case 'db':
					return new KeyChainDB( self::$db , $user );
					break;
				case 'session':
					return new KeyChainSession( $user );
					break;
				default:
					Debug::kill("unsupported storage method ".$GLOBALS['config']['keychain']['storage']);
					break;
			}
		}
		else
		{
			Debug::kill("choose your storage method in config file : ['config']['keychain']['storage']");
		}
	}
}

?>