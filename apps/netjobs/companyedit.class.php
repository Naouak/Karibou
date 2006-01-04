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
class NJCompanyEdit extends Model
{
	public function build()
	{
		$netJobs = new NetJobs ($this->db, $this->userFactory);
		$allCompanies = $netJobs->getCompanyList();
		$this->assign("allCompanies", $allCompanies);
		
		if ( (isset($this->args["companyid"])) && ($this->args["companyid"] != "") && ($myCompany = $netJobs->getCompanyById($this->args["companyid"])))
		{
			//Job modification
			$this->assign("myCompany", $myCompany);
			
			$menuApp = $this->appList->getApp($this->appname);
			$menuApp->addView("menu", "header_menu", array("page" => "companyedit", "companyid" => $myCompany->getInfo("id")) );
		}
		else
		{
			//New job
			$menuApp = $this->appList->getApp($this->appname);
			$menuApp->addView("menu", "header_menu", array("page" => "companyedit") );
		}
	}
}

?>