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
class NJContactDetails extends Model
{
	public function build()
	{
		$netJobs = new NetJobs ($this->db, $this->userFactory);
		
		if (isset($this->args["contactid"]) && $this->args["contactid"] !="")
		{
			$myContact = $netJobs->getContactById($this->args["contactid"]);

			if ($myContact !== FALSE)
			{
				$this->assign("myContact", $myContact);

				if ($myContact["profile"]["userid"] == $this->userFactory->getCurrentUser()->getId())
				{
					$this->assign("edit", TRUE);
				}
			}
		
			$menuApp = $this->appList->getApp($this->appname);
			$menuApp->addView("menu", "header_menu", array("page" => "contactdetails", "contactid" => $this->args["contactid"]) );
		}
		else
		{
		}
	}
}

?>