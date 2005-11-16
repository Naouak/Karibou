<?php
/**
 * @copyright 2004 Jonathan Semczyk <jonathan.semczyk@free.fr>
 *
 * @license http://www.gnu.org/licenses/lgpl.html Lesser GNU Public License
 * See the enclosed file COPYING for license information (LGPL).
 * 
 * @package framework
 **/

/**
 * @package framework
 */
class KPermissions
{
	public static $txt2id = array(
		"_UNKNOWN_" => _UNKNOWN_ ,
		"_NO_ACCESS_" => _NO_ACCESS_ ,
		"_READ_ONLY_" => _READ_ONLY_ ,
		"_DEFAULT_" => _DEFAULT_ ,
		"_SELF_WRITE_" => _SELF_WRITE_ ,
		"_GROUP_WRITE_" => _GROUP_WRITE_ ,
		"_GROUP_ADMIN_" => _GROUP_ADMIN_ ,
		"_FULL_WRITE_" => _FULL_WRITE_ ,
		"_ADMIN_" => _ADMIN_
		);

	public static $id2txt = array(
		_UNKNOWN_ => "_UNKNOWN_" ,
		_NO_ACCESS_ => "_NO_ACCESS_" ,
		_READ_ONLY_ => "_READ_ONLY_" ,
		_DEFAULT_ => "_DEFAULT_" ,
		_SELF_WRITE_ => "_SELF_WRITE_" ,
		_GROUP_WRITE_ => "_GROUP_WRITE_" ,
		_GROUP_ADMIN_ => "_GROUP_ADMIN_" ,
		_FULL_WRITE_ => "_FULL_WRITE_" ,
		_ADMIN_ => "_ADMIN_"
		);
	/**
	 * @var Array
	 */
	protected $permArray;
	
	/**
	 */
	function __construct()
	{
		$this->permArray = array();
	}
	
	/**
	 * @param String $appli nom de l'appli
	 * @param Int $perm
	 */
	function set($appli, $perm)
	{
		$this->permArray[$appli] = $this->getFromText($perm);
	}
	
	/**
	 * @param Int $appli
	 */
	function get($appli)
	{
		if ( isset($this->permArray[$appli]) )
		{
			return $this->permArray[$appli];
		}
		else
		{
			return _DEFAULT_;
		}	
	}
	
	public function getFromText($string)
	{
		if( isset(self::$txt2id[$string]) )
		{
			return self::$txt2id[$string];
		}
		else
		{
			Debug::display("Permission string not found : ".$string);
			return _DEFAULT_;
		}
	}
	public function getFromId($id)
	{
		if( isset(self::$id2txt[$id]) )
		{
			return self::$id2txt[$id];
		}
		else
		{
			Debug::display("Permission id not found : ".$id);
			return "_DEFAULT_";
		}
	}
}

?>
