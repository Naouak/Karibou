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

class NetCVSectionModify extends Model
{
	public function build()
	{
		if (isset($this->args['cvid'], $this->args['gid'])) {
			$myNetCVUser = new NetCVUser($this->db, $this->currentUser,TRUE);
		   $myNetCVSingleCV = $myNetCVUser->returnSingleCV($this->args['gid'], $this->args["cvid"]);	
		   $myNetCVSingleCVContent = $myNetCVSingleCV->getContent();
		   
			$myNetCVGroupList	= $myNetCVUser->returnCVGroupList();
			$myNetCVGroup		= $myNetCVGroupList->returnGroupById($this->args['gid']);

			$this->assign("cvid", $this->args['cvid']);
			$this->assign("gid", $this->args['gid']);

			if (isset($this->args['id'])) {
				$this->assign("id", $this->args['id']);
			}
			if (isset($this->args['first']))
			{
				$this->assign("first", $this->args['first']);
			}

			$this->assign("myNetCVGroup", $myNetCVGroup);
		
			$this->assign("myNetCV", $myNetCVSingleCVContent);
			$this->assign("myNetCVSingleCV", $myNetCVSingleCV);
			
			$myNetCVLanguage = new NetCVLanguage();
			$this->assign("myNetCVLanguage",$myNetCVLanguage);
		} else {
            //Aucun CV a charger
		}

	}
}
?>
