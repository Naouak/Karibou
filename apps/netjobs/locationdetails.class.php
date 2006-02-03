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
class NJLocationDetails extends Model
{
	public function build()
	{
		$netJobs = new NetJobs ($this->db, $this->userFactory);
		
		if (isset($this->args["companyid"]) && $this->args["companyid"] !="" && $myCompany = $netJobs->getCompanyById($this->args["companyid"]))
		{
			$this->assign("myCompany", $myCompany);
			
			$menuApp = $this->appList->getApp($this->appname);
			$menuApp->addView("menu", "header_menu", array("page" => "jobdetails", "companyid" => $myCompany->getInfo("id")) );
		}
		else
		{
		}
	}
}

?>