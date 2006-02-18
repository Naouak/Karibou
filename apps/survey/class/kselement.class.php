<?php
/**
 * @copyright 2005 Antoine Leclercq <http://antoine.leclercq.netcv.org>
 *
 * @license http://www.gnu.org/licenses/gpl.html GNU Public License
 * See the enclosed file COPYING for license information.
 *
 * @package applications
 **/

define ("READ", 4);
define ("UPDATE", 2);
define ("WRITE", 1);

/**
 *
 * @package applications
 **/
class KSElement
{

	protected $infos;
	protected $userFactory;
	
	public $creator;
	protected $rights;
	protected $currentUser;

	function __construct(Array $infos, UserFactory $userFactory)
	{
		$this->infos = $infos;
		$this->userFactory = $userFactory;
		
		if ($this->getInfo("userid") !== FALSE)
		{
			$this->creator = $userFactory->prepareUserFromId($this->getInfo("userid"));
		}
		
		$this->currentUser = $this->userFactory->getCurrentUser();
		
		if ($this->getInfo("userid") == $this->currentUser->getId())
		{
			$this->rights = READ | UPDATE | WRITE;
		}
		else
		{
			$this->rights = READ;
		}		
	}

	/* Infos methods */

	public function getInfo($key)
	{
		if (isset($this->infos[$key]))
		{
			return $this->infos[$key];
		}
		else
		{
			return FALSE;
		}
	}
		
	public function getLocationInfo($key)
	{
		if (isset($this->infos["locationinfos_".$key]))
		{
			return $this->infos["locationinfos_".$key];
		}
		else
		{
			return FALSE;
		}
	}
	
	public function getAllInfo()
	{
		return $this->infos;
	}


	public function getSecondsSinceLastUpdate ()
	{
		$updatedate	= $this->getInfo("timestamp");
		$nowdate	= mktime();
		return ($nowdate-$updatedate);
	}


	/* Rights methods */

	public function canRead()
	{
		if ($this->rights & READ == READ)
			return TRUE;
		else
			return FALSE;
	}
	
	public function canUpdate()
	{
		if ($this->rights & UPDATE == UPDATE)
			return TRUE;
		else
			return FALSE;
	}
	
	public function canWrite()
	{
		if ($this->rights & WRITE == WRITE)
			return TRUE;
		else
			return FALSE;
	}


	/* Location methods */
	public function getLocation()
	{
		$location = array();
		if (TRUE)
		{

		}
		
		return $location;
	}
	
	/* Set method */
	public function setInfo($key, $value)
	{
		$this->infos[$key] = $value;
	}

}

?>