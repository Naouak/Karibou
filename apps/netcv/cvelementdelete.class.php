<?php 

/**
 * @copyright 2005 Antoine Leclercq <http://antoine.leclercq.netcv.org>
 *
 * See the enclosed file COPYING for license information (GPL). If you
 * did not receive this file, see http://www.gnu.org/licenses/gpl.html.
 * 
 * @package applications
 **/
 
 
$GLOBALS['myTranslation'] = new NetCVTempTranslation();

class NetCVElementDelete extends Model
{
	public function build()
	{
		if( isset($this->args["cvid"],$this->args["id"]) ) {
			$myNetCVUser = new NetCVUser($this->db, $this->currentUser,TRUE);
		   $myNetCVSingleCV = $myNetCVUser->returnSingleCV(FALSE, $this->args["cvid"]);	
		   $myNetCVSingleCVContent = $myNetCVSingleCV->getContent();

			$myNetCVSingleCVContent->recurseDeleteElement($this->args["id"]);
		}
	}
}
?>
