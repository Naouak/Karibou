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
class KFile extends KDBFSElement
{
	protected $rootdir;
	protected $fullpath; //Contains the fullpath of the file
	protected $path; //Contains the path of the file (from the rootdir)
	
	protected $userFactory;

	function __construct(PDO $db, UserFactory $userFactory, $path = FALSE, $rootdir = FALSE, $id = FALSE, $data = FALSE)
	{
		$this->db = $db;
		$this->userFactory = $userFactory;
		
		if ($rootdir == FALSE)
		{
			$this->rootdir = KARIBOU_PUB_DIR.'/fileshare/share/';
		}
		else
		{
			$this->rootdir = $rootdir;
		}
	
		if ($id === FALSE && $path !== FALSE)
		{
			//Security
			$path = str_replace ('/..', '', $path);
			$path = str_replace ('../', '', $path);
			
			$this->path = $path;
			
			parent::__construct($db, $userFactory, 'file', $this->getPath());
		}
		elseif ($id !== FALSE)
		{
			parent::__construct($db, $userFactory, FALSE, FALSE, $id, $data);
			
		}

		$this->fullpath = $this->rootdir.$this->path;
	}
	
	
	function getPathBase64()
	{
		return base64_encode($this->getPath());
	}
	
	function getPath()
	{
		return $this->path;
	}
	
	function getSize()
	{
		return filesize($this->fullpath);
	}
	
	function getModificationDate()
	{
		return filemtime($this->fullpath);
	}
	
	//Returns full file name (with extension)
	function getName()
	{
		preg_match("/([^\/])+$/", $this->fullpath, $matches);
		
		if (isset ($matches[0]))
		{
			return $matches[0];
		}
		else
		{
			return '';
		}
	}

	//Method getting the fileid
	public function getFileId()
	{
		return parent::getElementId();
	}

	//Returns file extension if exists (string after the last dot)
	function getExtension ()
	{
		preg_match("/\.([^.])+$/", $this->getName(), $matches);
		if (isset ($matches[0]))
		{
			return substr($matches[0], 1, strlen($matches[0]));
		}
		else
		{
			return '';
		}
	}
	
	//Returns file name without extension
	function getShortName ()
	{
		$extlength = strlen($this->getExtension());
		if ($extlength>0)
		{
			$filename = $this->getName();
			return substr($filename, 0,strlen($filename) - ($extlength+1));
		}
		else
		{
			return $this->getName();
		}
	}
	
	function getNameWithoutExtension()
	{
		return $this->name;
	}
	
	function getFullPath()
	{
		return $this->fullpath;
	}
}

?>