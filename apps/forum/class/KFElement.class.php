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
class KFElement
{

	protected $infos;
	protected $userFactory;
	
	public $creator;
	protected $rights;
	protected $currentUser;

	function __construct(Array $infos, UserFactory $userFactory)
	{
		if (count($infos) > 0)
		{
			$this->infos = $infos;
			$this->userFactory = $userFactory;
			
			if ($this->getInfo("userid") !== FALSE && $this->getInfo("userid") > 0)
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
			return TRUE;
		}
		else
		{
			return FALSE;
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
	
	public function setInfo($key, $value)
	{
		$this->infos[$key] = $value;
	}
		
	public function getAllInfo()
	{
		return $this->infos;
	}
	
	/**
	 * Method setting automatically the element info from an array (can be a post array)
	 */
	public function setInfosFromArray($array)
	{
		foreach ($array as $key => $value)
		{
			$this->setInfo($key, $value);
		}
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
}

?>