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
class NJJobSave extends FormModel
{
	public function build()
	{
		$NetJobs = new NetJobs($this->db, $this->userFactory);
		if (isset($_POST["jobinfos"], $_POST["jobinfos"]["id"]))
		{
			$myJob = $NetJobs->getJobById($_POST["jobinfos"]["id"]);
			if ($myJob->canUpdate())
			{
				//Save modifications...
				$NetJobs->saveJob($_POST["jobinfos"]);
			}
			
			$this->setRedirectArg('page', 'jobdetails');
			$this->setRedirectArg('jobid', $_POST["jobinfos"]["id"]);
			
			if (isset($_POST["company_new"]) && $_POST["company_new"] == "on")
			{
				$this->setRedirectArg('app', 'netjobs');
				$this->setRedirectArg('page', 'companyedit');
				$this->setRedirectArg('companyid', 0);
				$this->setRedirectArg('jobid', $_POST["jobinfos"]["id"]);
			}
			else
			{
				$this->setRedirectArg('app', 'netjobs');
				$this->setRedirectArg('page', '');
			}
			
		}
		elseif (isset($_POST["jobinfos"]))
		{
			//create job
			$jobid = $NetJobs->saveJob($_POST["jobinfos"]);
			
			if (isset($_POST["company_new"]) && $_POST["company_new"] == "on")
			{
				$this->setRedirectArg('app', 'netjobs');
				$this->setRedirectArg('page', 'companyedit');
				$this->setRedirectArg('companyid', 0);
				$this->setRedirectArg('jobid', $jobid);
			}
			else
			{
				$this->setRedirectArg('app', 'netjobs');
				$this->setRedirectArg('page', '');
			}
		}
		elseif (isset($_POST["jobiddelete"]))
		{
			$myJob = $NetJobs->getJobById($_POST["jobiddelete"]);
			if ($myJob->canWrite())
			{
				$NetJobs->deleteJob($_POST["jobiddelete"]);
				$this->setRedirectArg('app', 'netjobs');
				$this->setRedirectArg('page', '');
			}
			else
			{
				$this->setRedirectArg('app', 'netjobs');
				$this->setRedirectArg('page', '');
			}
		}
		else
		{
				$this->setRedirectArg('app', 'netjobs');
				$this->setRedirectArg('page', '');
		}

	}
}

?>