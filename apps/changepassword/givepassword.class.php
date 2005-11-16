<?php 

/**
 * @copyright 2005 Antoine Leclercq <http://antoine.leclercq.netcv.org>
 *
 * See the enclosed file COPYING for license information (GPL). If you
 * did not receive this file, see http://www.gnu.org/licenses/gpl.html.
 * 
 * @package applications
 **/

class GivePassword extends Model
{

	public function build()
	{
		if (isset($_SESSION["changepasswordMessage"]))
		{
			$this->assign ("message", $_SESSION["changepasswordMessage"]);
			unset($_SESSION["changepasswordMessage"]);
		}
	}
}

?>
