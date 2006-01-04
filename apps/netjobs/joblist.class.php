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

		if (isset($this->args["maxjobs"]) && $this->args["maxjobs"] != "")
		{
			$maxjobs = $this->args["maxjobs"];
		}
		else
		{
			$maxjobs = FALSE;
		}
		
		if (isset($this->args["page"]) && $this->args["page"] != "")
		{
			$page = $this->args["page"];
		}
		else
		{
			$page = FALSE;
		}
		$this->assign ("jobcount", $netJobs->countJobs());
		$this->assign ("maxjobs", $maxjobs);
		$this->assign ("page", $page);
		
		$myJobs = $netJobs->getJobList($maxjobs, $page);
		$this->assign("myJobs", $myJobs);
		
		$myCompanies = $netJobs->getCompanyList();
		$this->assign("myCompanies", $myCompanies);
	}
}

?>