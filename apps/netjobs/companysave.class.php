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
		$netJobs = new NetJobs($this->db, $this->userFactory);
		if (isset($_POST["companyinfos"], $_POST["companyinfos"]["id"]))
		{
			
			$myCompany = $netJobs->getCompanyById($_POST["companyinfos"]["id"]);
			
			if ($myCompany->canWrite())
			{
				//Save modifications...
				$netJobs->saveCompany($_POST["companyinfos"]);
			}
			
			$this->setRedirectArg('page', 'companydetails');
			$this->setRedirectArg('companyid', $_POST["companyinfos"]["id"]);
			
		}
		elseif (isset($_POST["companyinfos"]) && isset($_POST["jobid"]))
		{
			$myJob = $netJobs->getJobById($_POST["jobid"]);
			
			if ($myJob->canWrite())
			{
				$companyid = $netJobs->saveCompany($_POST["companyinfos"]);
				$jobInfo = $myJob->getAllInfo();
				$jobInfo["company_id"] = $companyid;
				
				$netJobs->saveJob($jobInfo);
				
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
		else
		{
		}

	}
}

?>