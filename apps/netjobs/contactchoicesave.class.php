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
class NJContactChoiceSave extends FormModel
{
	public function build()
	{
		$NetJobs = new NetJobs($this->db, $this->userFactory);

		if (isset($_POST["jobid"], $_POST["companyid"], $_POST["contactid"]))
		{
			$myJob = $NetJobs->getJobById($_POST["jobid"]);
			if ($myJob->canUpdate())
			{
				if ($_POST["contactid"] == "new")
				{
					$this->setRedirectArg('app', 'addressbook');
					$this->setRedirectArg('page', 'addnj');
					$this->setRedirectArg('jobid', $_POST["jobid"]);
					$this->setRedirectArg('companyid', $_POST["companyid"]);
					$this->setRedirectArg('type', "job");
				}
				elseif ($_POST["contactid"] > 0)
				{
					//Save modifications...
					$NetJobs->saveContactChoice($_POST["jobid"], $_POST["companyid"], $_POST["contactid"]);
				}
			}
			else
			{
				$this->setRedirectArg('app', 'netjobs');
				$this->setRedirectArg('page', 'jobdetails');
				$this->setRedirectArg('jobid', $_POST["jobid"]);
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