<?php
/**
 * @copyright 2005 Jonathan Semczyk <jonathan.semczyk@free.fr>
 * @copyright 2005 Antoine Leclercq <http://antoine.leclercq.netcv.org>
 *
 * @license http://www.gnu.org/licenses/gpl.html GNU Public License
 * See the enclosed file COPYING for license information.
 * 
 * @package applications
 **/

ClassLoader::add('ProfileFactory', dirname(__FILE__).'/../annauire/classes/profilefactory.class.php');
ClassLoader::add('Profile', dirname(__FILE__).'/../annuaire/classes/profile.class.php');

/**
 * Display profile
 * 
 * @package applications
 */
class AddressBookAddNJ extends Model
{
	function build()
	{
	
		$menuApp = $this->appList->getApp($this->appname);
		$menuApp->addView("menu", "header_menu", array("page" => "add") );
	
		$this->assign('addr_types', array("DOM", "INTL", "POSTAL", "HOME", "WORK") );
		$this->assign('phone_types', array("WORK", "HOME", "FAX", "CELL", "PAGER") );
		$this->assign('email_types', array("INTERNET", "AIM", "ICQ", "JABBER", "MSN", "SKYPE") );



		if (isset($this->args["jobid"]) && $this->args["jobid"] != "")
		{
			$this->assign("jobid", $this->args["jobid"]);
		}

		if (isset($this->args["companyid"]) && $this->args["companyid"] != "")
		{
			$this->assign("companyid", $this->args["companyid"]);
		}
		
		if (isset($this->args["type"]) && $this->args["type"] != "")
		{
			$this->assign("njtype", $this->args["type"]);
		}
		else
		{
			$this->assign("njtype", "single");
		}
		
		$this->assign('netjobs', TRUE);
	}
}

?>