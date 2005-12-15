<?php
/**
 * @copyright 2005 Antoine Leclercq <http://antoine.leclercq.netcv.org>
 *
 * @license http://www.gnu.org/licenses/gpl.html GNU Public License
 * See the enclosed file COPYING for license information.
 *
 * @package applications
 **/

/**
 *
 * @package applications
 **/
class KDBFSElementVersion
{
	protected $data;
	
	protected $rootdir;
	
	function __construct ($data)
	{
		$this->data = $data;
		
		$this->rootdir = KARIBOU_PUB_DIR.'/fileshare/versions/';
	}
	
	public function getPath()
	{
		return $this->rootdir.$this->getInfo("id").".".$this->getInfo("versionid");
	}
	
	public function getInfo($key)
	{
		if (isset($this->data[$key]))
		{
			return $this->data[$key];
		}
		else
		{
			return FALSE;
		}
	}
	
	public function getSize()
	{
		if (is_file($this->getPath()))
		{
			return filesize($this->getPath());
		}
		else
		{
			return FALSE;
		}
	}
	
}

?>