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
		$xml = new XMLCache(KARIBOU_CACHE_DIR.'/xml_miniapps');
		$xml->loadFile(dirname(__FILE__).'/miniapps.xml');
		$apps = $xml->getXML();
		
		$config_apps = array();
		
		if( isset($apps->small) )
		{
			foreach( $apps->small as $a )
			{
				$a['size'] = 's';
				$config_apps[$a['id']] = $a;
			}
		}
		if( isset($apps->medium) )
		{
			foreach( $apps->medium as $a )
			{
				$a['size'] = 'm';
				$config_apps[$a['id']] = $a ;
			}
		}
		return $config_apps;
	}
	
	function deleteApps($array)
	{
		foreach( $array as $id)
		{
			if( isset($this->data[$id]) )
			{
				unset($this->data[$id]);
			}
		}
	}
	
}

?>