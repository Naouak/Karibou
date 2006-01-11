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
class KDirectory extends KDBFSElement
{
	protected $fullpath;
	protected $path;
	protected $rootdir;
	protected $files = array();
	protected $subdirs = array();
	protected $exists;
	protected $permission;
	
	protected $userFactory;

	function __construct(PDO $db, UserFactory $userFactory, $permission, $path = "", $rootdir = FALSE, $id = FALSE, $maxdepth = 1)
	{
	
		$this->db = $db;
		$this->userFactory = $userFactory;
		$this->permission = $permission;

		if ($rootdir == FALSE)
		{
			$this->rootdir = KARIBOU_PUB_DIR.'/fileshare/share/';
		}
		else
		{
			$this->rootdir = $rootdir;
		}

		
		if ($id !== FALSE)
		{
			parent::__construct($db, $this->userFactory, $this->permission, 'folder', FALSE, $id);
		}
		else
		{
			//Security... need more?
			$path = str_replace ('/..', '', $path);
			$path = str_replace ('../', '', $path);
		
			$this->path = $path;

			parent::__construct($db, $this->userFactory, $this->permission, 'folder', $this->getPath());
		}
		
		
		$this->fullpath = $this->rootdir.$this->getPath();

		if (is_dir($this->fullpath))
		{
			$this->exists = TRUE;
			if ($maxdepth > 0 || $maxdepth === FALSE)
			{
				if ($d = dir($this->getFullPath()))
				{
					while (false !== ($entry = $d->read())) {
					   if ($entry != '.' && $entry != '..' && $entry != '.htaccess')
					   {
							if (is_file($this->getFullPath().'/'.$entry))
							{
								$this->addFile($this->getPath()."/".$entry);
							}
							elseif (is_dir($this->getFullPath().'/'.$entry))
							{
								if ( ($maxdepth !== FALSE) && ($maxdepth > 0) )
								{
									$maxdepth -= 1;
								}
								$this->addSubDir($this->getPath()."/".$entry, $maxdepth);
							}
					   }
					}
					$d->close();
				}
				else
				{
					//Problem opening the directory
				}
			}
		}
		else
		{
			//Directory doesn't exist
			$this->exists = FALSE;
		}
		
		
	}
	
	//Return true if directory exists
	public function exists ()
	{
		return $this->exists;
	}
	
	//Return directory name
	public function getName ()
	{
	/*
		if (strrpos($this->getPath(),'/') == strlen($this->getPath())-1)
		{
			$path = substr($this->getPath(),0,strlen($this->getPath())-1);
		}
		else
		{
			$path = $this->getPath();
		}
		*/
		
		preg_match("/([^\/])+$/", $this->getPath(), $matches);
			
			if (isset ($matches[0]))
			{
				return $matches[0];
			}
			else
			{
				return '';
			}
	}

	//Return full path from rootdir without trailing slash
	public function getFullPath()
	{
		preg_match("/(.*)[\/]{0,1}$/", $this->fullpath, $matches);
		//var_dump($matches);
		return $matches[0];
		//return $this->fullpath;
	}


	
	//Method getting the folderid
	public function getFolderId()
	{
		return parent::getElementId();
	}
	
	//Return TRUE if dir is rootdir
	public function isRootDir()
	{
		if ($this->getPath() == "")
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}
	
	//Return Base64 path
	public function getPathBase64 ()
	{
		return base64_encode($this->getPath());
	}
	
	//Return parent path of actual dir
	public function getParentPath()
	{

		if ($this->getPath() != $this->getName())
		{
			$parentpath = substr($this->getPath(), 0, strlen($this->getPath()) - strlen($this->getName())-1);
		}
		else
		{
			$parentpath = '';
		}
		
		return $parentpath;
	}
	
	//Return Base64 parent path
	public function getParentPathBase64 ()
	{
		return base64_encode($this->getParentPath());
	}
	
	//Create a subdirectory
	public function createSubDirectory ($name)
	{
		if (mkdir ($this->getFullPath().'/'.$name))
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}

	function getModificationDate()
	{
		return filectime($this->fullpath);
	}

	public function addFile ($filename)
	{
		$kfile = new KFile($this->db, $this->userFactory, $this->permission, $filename);
		
		array_push($this->files, $kfile );
	}
	
	public function addSubDir ($dirname, $maxdepth)
	{
		array_push($this->subdirs, new KDirectory($this->db, $this->userFactory, $this->permission, $dirname, FALSE, FALSE, $maxdepth) );
	}
	
	public function returnFileList()
	{
		return $this->files;
	}
	
	public function returnSubDirList()
	{
		return $this->subdirs;
	}
	
	public function isEmpty()
	{
		if (count($this->files) == 0 && count($this->subdirs) == 0)
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}
	
	public function returnTree($first = TRUE)
	{	
		$tree = array();
		if ($first)
		{
			$tree[$this->getElementId()] = "/";
		}
		
		$subdirs = $this->returnSubDirList();
		foreach ($subdirs as $subdir)
		{
			if ($subdir->canUpdate())
			{
				$tree[$subdir->getElementId()] = $subdir->getPath();
			}
			$subdirtree = $subdir->returnTree(FALSE);
			$tree = $tree + $subdirtree;
		}
		return $tree;
	}
}

?>
