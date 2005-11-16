<?php 

/**
 * @version $Id:  netcvhome.class.php,v 0.1 2005/08/07 10:52:56 dat Exp $
 * @copyright 2005 Antoine Leclercq <http://antoine.leclercq.netcv.org>
 *
 * See the enclosed file COPYING for license information (GPL). If you
 * did not receive this file, see http://www.gnu.org/licenses/gpl.html.
 * 
 * @package applications
 **/
 
//require_once dirname(__FILE__)."/inc/classes.php";
require_once dirname(__FILE__)."/class/netcvuser.class.php";

//include_once("applis/$appliDir/lang/lang.php");
//include_once("applis/$appliDir/inc/auth.php");

//define ('MIN_INFOS',6);
//define ('MIN_SECTIONS',4);

class NetCVHome extends Model
{
	public function build()
	{
		$this->assign ("permission", $this->permission);
		if($this->currentUser->isLogged() && ($this->permission >= _SELF_WRITE_))
		{
			$netcv = $this->appList->getApp("netcv");
			$netcv->addView("cvgrouplistview", "netcvHome");
		}
	}
}

?>