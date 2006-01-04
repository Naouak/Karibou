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
class NJCompanyList extends Model
{
	public function build()
	{
		$menuApp = $this->appList->getApp($this->appname);
		$menuApp->addView("menu", "header_menu", array("page" => "default") );

		$netJobs = new NetJobs ($this->db, $this->userFactory);
		
		if (isset($this->args["maxjobs"]) && $this->args["maxjobs"] != "")
		{
			$maxjobs = $this->args["maxjobs"];
		}
		else
		{
			$maxjobs = FALSE;
		}
		
		$myJobs = $netJobs->getJobList($maxjobs);
		$this->assign("myJobs", $myJobs);
		
		$myCompanies = $netJobs->getCompanyList();
		$this->assign("myCompanies", $myCompanies);
	}
}

?>