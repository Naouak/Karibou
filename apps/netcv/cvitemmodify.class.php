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

class NetCVItemModify extends Model
{
	public function build()
	{
	
		$myNetCVUser = new NetCVUser($this->db, $this->currentUser, TRUE);
		if (isset($this->args['cvid'], $this->args['gid'])) {
			//$myNetCVUser->getCVContent($this->args['cvid']);
			//$myNetCV = $myNetCVUser->returnCVContent($this->args['cvid']);

			$myNetCVGroupList	= $myNetCVUser->returnCVGroupList();
			$myNetCVGroup		= $myNetCVGroupList->returnGroupById($this->args['gid']);
			$this->assign("myNetCVGroup", $myNetCVGroup);

		   $myNetCVSingleCV = $myNetCVUser->returnSingleCV($this->args['gid'], $this->args["cvid"]);	
		   $myNetCVSingleCVContent = $myNetCVSingleCV->getContent();
			$this->assign("myNetCV", $myNetCVSingleCVContent);
			$this->assign("myNetCVSingleCV", $myNetCVSingleCV);

			if (isset($this->args['id'])) {
				$this->assign("id", $this->args['id']);
			}
			
			if (isset($this->args['pid'])) {
				$this->assign("pid", $this->args['pid']);
			}
			
			//Verification de la presence d'erreur et affection du message d'erreur a afficher
			$this->assign("netcvMessages", $this->formMessage->getSession());
			$this->formMessage->flush();
			
			$this->assign("cvid", $this->args['cvid']);
			$this->assign("gid", $this->args['gid']);

			$myNetCVLanguage = new NetCVLanguage();
			$this->assign("myNetCVLanguage",$myNetCVLanguage);
		} else {
            //Aucun CV a charger
		}
	}
}
?>