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
class NJLocationSave extends FormModel
{
	public function build()
	{
		$netJobs = new NetJobs($this->db, $this->userFactory);
		if (isset($_POST["jobid"], $_POST["locationinfos"], $_POST["locationinfos"]["country_id"]) && $_POST["jobid"] != "" && $_POST["locationinfos"]["country_id"] != "")
		{
			
			$myJob = $netJobs->getJobById($_POST["jobid"]);
			
			if ($myJob->canWrite())
			{
				//$jobInfo = $myJob->getAllInfo();
				
				//$netJobs->saveJob($jobInfo);
				
				$netJobs->saveJobLocation($_POST["jobid"], $_POST["locationinfos"]);
				
				$this->setRedirectArg('app', 'netjobs');
				$this->setRedirectArg('page', 'jobdetails');
				$this->setRedirectArg('jobid', $_POST["jobid"]);
			}
			else
			{
				$this->setRedirectArg('app', 'netjobs');
				$this->setRedirectArg('page', 'locationedit');
				$this->setRedirectArg('jobid', $_POST["jobid"]);
			}
			
		}
		else
		{
				$this->setRedirectArg('app', 'netjobs');
		}

	}
}

?>