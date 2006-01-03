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
class NJJobDetails extends Model
{
	public function build()
	{
		$netJobs = new NetJobs ($this->db, $this->userFactory);
		
		if (isset($this->args["jobid"]) && $this->args["jobid"] !="" && $myJob = $netJobs->getJobById($this->args["jobid"]))
		{
			$this->assign("myJob", $myJob);
			
			$menuApp = $this->appList->getApp($this->appname);
			$menuApp->addView("menu", "header_menu", array("page" => "jobdetails", "jobid" => $myJob->getInfo("id")) );
		}
		else
		{
		}
	}
}

?>