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
class NJJobEdit extends Model
{
	public function build()
	{
		$netJobs = new NetJobs ($this->db, $this->userFactory);
		
		$geo = new Geo ($this->db, $this->userFactory);
		
		$this->assign("allCompanies", $netJobs->getCompanyList());
		$this->assign("countries", $geo->getCountryList());
		
		
		if ( (isset($this->args["jobid"])) && ($this->args["jobid"] != "") && ($myJob = $netJobs->getJobById($this->args["jobid"])))
		{
			//Job modification
			$this->assign("myJob", $myJob);
			
			$menuApp = $this->appList->getApp($this->appname);
			$menuApp->addView("menu", "header_menu", array("page" => "jobedit", "jobid" => $myJob->getInfo("id")) );
		}
		else
		{
			//New job
			$menuApp = $this->appList->getApp($this->appname);
			$menuApp->addView("menu", "header_menu", array("page" => "jobedit") );
		}
		

		
	}
}

?>