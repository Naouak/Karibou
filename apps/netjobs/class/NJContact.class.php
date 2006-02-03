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
class NJContact extends Profile
{
	public $companies;
	public $jobs;

	public function __construct(Array $contactinfos, UserFactory $userFactory)
	{
		parent::__construct($contactinfos,$userFactory);
		
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