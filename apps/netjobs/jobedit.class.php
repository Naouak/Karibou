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
class NJJobEdit extends Model
{
	public function build()
	{
		$netJobs = new NetJobs ($this->db, $this->userFactory);
		$myJob = $netJobs->getJobById($this->args["jobid"]);
		$this->assign("myJob", $myJob);
	}
}

?>