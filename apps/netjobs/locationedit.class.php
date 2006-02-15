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
class NJLocationEdit extends Model
{
	public function build()
	{
		$netJobs = new NetJobs ($this->db, $this->userFactory);

		if ( (isset($this->args["jobid"])) && ($this->args["jobid"] != "") && ($this->args["jobid"] > 0) )
		{
		
			$myGeo = new Geo($this->db, $this->userFactory);
			$this->assign("countries", $myGeo->getCountryList());
			$this->assign("counties", $myGeo->getCountyList("99189"));

			$myJob = $netJobs->getJobById($this->args["jobid"]);
			
			if ($myJob->canWrite())
			{
				//$location = $myJob->getLocation();
				$this->assign("myJob", $myJob);
				//$this->assign("location", $location);
			}
			else
			{
			}
		
			$menuApp = $this->appList->getApp($this->appname);
			$menuApp->addView("menu", "header_menu", array("page" => "locationedit", "jobid" => $myJob->getInfo("id")) );
		}
		else
		{
			//A location can only be set on a job or a company
		}
	}
}

?>