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
class NJContact extends NJElement
{
	public $companies;
	public $jobs;

	public function __construct(Array $contactinfos, UserFactory $userFactory)
	{
		parent::__construct($contactinfos,$userFactory);
		
	}

	public function getProfileInfo($key)
	{
		if (isset($this->infos["profile"], $this->infos["profile"][$key]))
		{
			return $this->infos["profile"][$key];
		}
		else
		{
			return FALSE;
		}
	}
	
	public function getAddresses()
	{
		if (isset($this->infos["addresses"]))
		{
			return $this->infos["addresses"];
		}
		else
		{
			return FALSE;
		}
	}
	
	public function getPhones()
	{
		if (isset($this->infos["phones"]))
		{
			return $this->infos["phones"];
		}
		else
		{
			return FALSE;
		}
	}

	public function getEmails()
	{
		if (isset($this->infos["emails"]))
		{
			return $this->infos["emails"];
		}
		else
		{
			return FALSE;
		}
	}


	public function getCompanyInfo($key)
	{
		if (isset($this->company) && (is_object($this->company)) )
		{
			return $this->company->getInfo($key);
		}
		else
		{
			return FALSE;
		}
	}
	
}

?>