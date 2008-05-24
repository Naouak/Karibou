<?php
/**
 * @copyright 2005 Jonathan Semczyk <jonathan.semczyk@free.fr>
 *
 * See the enclosed file COPYING for license information (GPL). If you
 * did not receive this file, see http://www.gnu.org/licenses/gpl.html.
 * 
 * @package applications
 **/

/**
 * @package applications
 */

class HomeMiniApps extends ObjectList
{
	function __construct()
	{
	}
	
	function getNewApp($internal_id, $args=array())
	{
		$config_apps = $this->getConfig();
		
		if( ! isset($config_apps[$internal_id]) )
		{
			return false;
		}
		
		$i = 0;
		$new_id = $internal_id.'_'.$i;
		while( isset($this->data[$new_id]) )
		{
			$i++;
			$new_id = $internal_id.'_'.$i;
		}
		$this->data[$new_id] = $config_apps[$internal_id];
		$this->data[$new_id]['args'] = $args;

		return $new_id;
	}
	
	function setArgs($id, $args)
	{
		$this->data[$id]['args'] = $args;
	}
	
	function getConfig()
	{
		$config_apps = array();
		
		if ($dh = opendir(KARIBOU_APP_DIR)) {
			while (($file = readdir($dh)) !== false) {
				if (($file != ".") && ($file != "..") && is_dir(KARIBOU_APP_DIR . "/$file") && is_file(KARIBOU_APP_DIR . "/$file/$file.app")) {
					$appFile = KARIBOU_APP_DIR . "/$file/$file.app";
					$attributes = parse_ini_file($appFile);
					if (isset($attributes["size"]) && isset($attributes["id"]) && isset($attributes["view"])) {
						// This file is valid...
						if ($attributes["size"] == "medium")
							$attributes["size"] = "m";
						else if ($attributes["size"] == "small")
							$attributes["size"] = "s";
						$attributes["app"] = $file;
						$config_apps[$attributes["id"]] = $attributes;
					}
				}
			}
			closedir($dh);
		}
		
		return $config_apps;
	}
	
	function deleteApps($array)
	{
		foreach( $array as $id)
		{
			$this->deleteApp($id);
		}
	}
	
	function deleteApp($id)
	{
		if( isset($this->data[$id]) )
		{
			unset($this->data[$id]);
		}
	}
	
}

?>
