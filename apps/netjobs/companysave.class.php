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
class NJCompanySave extends FormModel
{
	public function build()
	{
		if (isset($_POST["companyinfos"], $_POST["companyinfos"]["id"]))
		{
		/*
			$NetJobs = new NetJobs($this->db, $this->userFactory);
			$myJob = $NetJobs->getJobById($_POST["jobinfos"]["id"]);
			if ($myJob->getInfo("user_id") == $this->currentUser->getId())
			{
				//Save modifications...
				$NetJobs->saveJob($_POST["jobinfos"]);
			}
			
			$this->setRedirectArg('page', 'jobdetails');
			$this->setRedirectArg('jobid', $_POST["jobinfos"]["id"]);
		*/
		}
		elseif (isset($_POST["companyinfos"]))
		{
		/*
			//create job
		
			$NetJobs = new NetJobs($this->db, $this->userFactory);
			$NetJobs->saveJob($_POST["jobinfos"]);
		
			$this->setRedirectArg('app', 'netjobs');
			$this->setRedirectArg('page', '');
		*/
		}

	}
}

?>