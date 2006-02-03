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
		$NetJobs = new NetJobs($this->db, $this->userFactory);
		if (isset($_POST["companyinfos"], $_POST["companyinfos"]["id"]))
		{
			
			$myCompany = $NetJobs->getCompanyById($_POST["companyinfos"]["id"]);
			
			if ($myCompany->canWrite())
			{
				//Save modifications...
				$NetJobs->saveCompany($_POST["companyinfos"]);
			}
			
			$this->setRedirectArg('page', 'companydetails');
			$this->setRedirectArg('companyid', $_POST["companyinfos"]["id"]);
			
		}
		elseif (isset($_POST["companyinfos"]) && isset($_POST["jobid"]))
		{
			$myJob = $NetJobs->getJobById($_POST["jobid"]);
			
			if ($myJob->canWrite())
			{
				$companyid = $NetJobs->saveCompany($_POST["companyinfos"]);
				$jobInfo = $myJob->getAllInfo();
				$jobInfo["company_id"] = $companyid;
				
				$NetJobs->saveJob($jobInfo);
				
				$this->setRedirectArg('app', 'netjobs');
				$this->setRedirectArg('page', 'locationedit');
				$this->setRedirectArg('jobid', $_POST["jobid"]);
			}
			else
			{
				$this->setRedirectArg('app', 'netjobs');
				$this->setRedirectArg('page', '');
			}
		}
		elseif (isset($_POST["companyiddelete"]))
		{
			$myCompany = $NetJobs->getCompanyById($_POST["companyiddelete"]);
			if ($myCompany->canWrite())
			{
				$NetJobs->deleteCompany($_POST["companyiddelete"]);
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
		}

	}
}

?>