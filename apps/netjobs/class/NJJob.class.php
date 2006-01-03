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
class NJJob
{
	protected $jobinfos;
	public $company;
	public $creator;

	public function __construct(Array $jobinfos, UserFactory $userFactory)
	{
		$this->jobinfos = $jobinfos;

		$this->creator = $userFactory->prepareUserFromId($this->getInfo("user_id"));
	}
	
	public function getInfo($key)
	{
		if (isset($this->jobinfos[$key]))
		{
			return $this->jobinfos[$key];
		}
		else
		{
			return FALSE;
		}
	}
	
	public function getCompanyInfo($key)
	{
		if (isset($this->company))
		{
			return $this->company->getInfos($key);
		}
		else
		{
			return FALSE;
		}
	}
	
}

?>