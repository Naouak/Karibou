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
class NJJobSearchRedirect extends FormModel
{
	public function build()
	{
		if (isset($_POST["jobsearch"]))
		{
				$this->setRedirectArg('app', 'netjobs');
				$this->setRedirectArg('page', 'joblist');
				$this->setRedirectArg('companyid', 0);
				$this->setRedirectArg('pagenum', 0);
				$this->setRedirectArg('maxjobs', 0);
				$this->setRedirectArg('search', $_POST["jobsearch"]);
		}
		else
		{
				$this->setRedirectArg('app', 'netjobs');
				$this->setRedirectArg('page', '');
		}

	}
}

?>