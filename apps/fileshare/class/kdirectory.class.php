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
class KDirectory
{
	protected $fullpath;
	protected $path;
	protected $rootdir;
	protected $files = array();
	protected $subdirs = array();
	protected $exists;

	function __construct($path = "", $rootdir = FALSE)
	{
		//Security
		$path = str_replace ('/..', '', $path);
		$path = str_replace ('../', '', $path);
	
		$this->path = $path;
		if ($rootdir == FALSE)
		{
			$this->rootdir = KARIBOU_PUB_DIR.'/fileshare/';
		}
		else
		{
			$this->rootdir = $rootdir;
		}
		$this->fullpath = $this->rootdir.$this->path;

		if (is_dir($this->fullpath))
		{
			$this->exists = TRUE;
			
			if ($d = dir($this->getFullPath()))
			{
				while (false !== ($entry = $d->read())) {
				   if ($entry != '.' && $entry != '..' && $entry != '.htaccess')
				   {
					   if (is_file($this->fullpath.$entry))
					   {
							$this->addFile($this->getPath()."/".$entry);
						}
						elseif (is_dir($this->fullpath.$entry))
						{
							$this->addSubDir($this->getPath()."/".$entry);
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
		return $this->fullpath;
	}

	//Return path from rootdir without trailing slash
	public function getPath()
	{
		if ( (strrpos($this->path,'/')== strlen($this->path)-1) )
		{
			$path = substr($this->path,0,strlen($this->path)-1);
		}
		else
		{
			$path = $this->path;
		}
		
		return $path;
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
		return filemtime($this->fullpath);
	}

	public function addFile ($filename)
	{
		array_push($this->files, new KFile($filename) );
	}
	
	public function addSubDir ($dirname)
	{
		array_push($this->subdirs, new KDirectory($dirname) );
	}
	
	public function returnFileList()
	{
		return $this->files;
	}
	
	public function returnSubDirList()
	{
		return $this->subdirs;
	}
}

?>
