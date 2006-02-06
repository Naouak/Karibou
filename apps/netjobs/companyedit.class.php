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

		if ( (isset($this->args["companyid"])) && ($this->args["companyid"] != "") && ($this->args["companyid"] > 0) && ($myCompany = $netJobs->getCompanyById($this->args["companyid"])))
		{
			//Company modification
			$this->assign("myCompany", $myCompany);
			
			$menuApp = $this->appList->getApp($this->appname);
			$menuApp->addView("menu", "header_menu", array("page" => "companyedit", "companyid" => $myCompany->getInfo("id")) );
		}
		else
		{
			//New company
			$menuApp = $this->appList->getApp($this->appname);
			$menuApp->addView("menu", "header_menu", array("page" => "companyedit") );
			
			if (isset($this->args["jobid"]) && $this->args["jobid"] != "")
			{
				$myJob = $netJobs->getJobById($this->args["jobid"]);
				if ($myJob !== FALSE)
				{
					$this->assign("myJob",$myJob);
				}
			}
		}
	}
}

?>