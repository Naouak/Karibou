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
class NJJobList extends Model
{
	public function build()
	{
		$menuApp = $this->appList->getApp($this->appname);
		$menuApp->addView("menu", "header_menu", array("page" => "joblist") );

		$netJobs = new NetJobs ($this->db, $this->userFactory);

		if (isset($this->args["maxjobs"]) && $this->args["maxjobs"] != "" && $this->args["maxjobs"] > 0)
		{
			$maxjobs = $this->args["maxjobs"];
		}
		else
		{
			//TEMP
			$maxjobs = 90;
		}
		
		if (isset($this->args["pagenum"]) && $this->args["pagenum"] != "" && $this->args["pagenum"] > 0)
		{
			$page = $this->args["pagenum"];
		}
		else
		{
			$page = 1;
		}
		
		if (isset($this->args["search"]) && $this->args["search"] != "")
		{
			$search = urldecode($this->args["search"]);
			$this->assign ("search", $search);
		}
		else
		{
			$search = FALSE;
		}
		
		if (isset($this->args["companyid"]) && $this->args["companyid"] != "" && $this->args["companyid"] > 0)
		{
			$companyid = $this->args["companyid"];
			$myCompany = $netJobs->getCompanyById($companyid);
			$this->assign("myCompany", $myCompany);
		}
		else
		{
			$companyid = FALSE;
		}
		
		$this->assign ("jobcount", $netJobs->countJobs($companyid, $search));
		$this->assign ("maxjobs", $maxjobs);
		$this->assign ("pagenum", $page);

		$myJobs = $netJobs->getJobList($maxjobs, $page, $companyid, $search);
		$this->assign("myJobs", $myJobs);
		
		/*
		$myCompanies = $netJobs->getCompanyList();
		$this->assign("myCompanies", $myCompanies);
		*/
	}
}

?>