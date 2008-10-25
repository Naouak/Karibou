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

class HomeContainers extends ObjectList
{
	protected $div_id = "app_container";
	protected $default;
	
	function __construct()
	{
		$this->default = array();
	}

function testReturn ()
{
	return $this->default;
}
	
	function add($size = 's')
	{
		$cnt = count($this->data);
		$this[ $this->div_id.$cnt ] = array( 
			'size' => $size ,
			'apps' => array()
			);
		return $this->div_id.$cnt;
	}
	
	function addApp($id)
	{
		$this->default[] = $id;
	}
	
	function getDefaultApps()
	{
		return $this->default;
	}

	function setDefaultApps($apps)
	{
		$this->default = $apps;
	}
	
	function setSize($id, $size)
	{
		$this->data[$id]['size'] = $size;
	}

	function setApps($id, $apps)
	{
		$this->data[$id]['apps'] = $apps;
	}
	
	function deleteApp($id)
	{
		foreach($this->data as $data_key => $container)
		{
			foreach($container['apps'] as $index => $app_id)
			{
				if( $id == $app_id )
				{
					unset($this->data[$data_key]['apps'][$index]);
				}
			}
		}
		foreach($this->default as $index => $app_id)
		{
			if( $id == $app_id )
			{
				unset($this->default[$index]);
			}
		}
	}
	
	function setSizes($str)
	{
		$final_cnt = 0;
		for( $i = 0 ; $i < strlen($str) ; $i++ )
		{
			$size = $str{$i};
			if( isset($this->data[$this->div_id.$i]) )
			{
				switch($size)
				{
					case 's':
					case 'm':
					case 'l':
						$this->setSize($this->div_id.$i, $size);
					default:
						break;
				}
			}
			else
			{
				switch($size)
				{
					case 's':
					case 'm':
					case 'l':
						$this->add($size);
					default:
						break;
				}
			}
			$final_cnt++;
		}
		
		$cont_cnt = count($this->data);
		for( $i=0 ; $i < $cont_cnt - $final_cnt ; $i++ )
		{
			$this->setApps($this->div_id.($final_cnt-1), array_merge(
				$this->data[$this->div_id.($final_cnt-1)]['apps'] ,
				$this->data[$this->div_id.($final_cnt+$i)]['apps'] ) );
				
			unset($this->data[$this->div_id.($final_cnt+$i)]);
		}		
	}
}

?>
