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
class NJCompany
{
	protected $companyinfos;

	public function __construct(Array $companyinfos, UserFactory $userFactory)
	{
		$this->companyinfos = $companyinfos;
	}
	
	public function getInfo($key)
	{
		if (isset($this->companyinfos[$key]))
		{
			return $this->companyinfos[$key];
		}
		else
		{
			return FALSE;
		}
	}
	
}

?>