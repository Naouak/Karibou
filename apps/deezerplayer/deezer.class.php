<?php 
/**
 * @copyright 2007 Charles Anssens
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
class Deezer extends Model
{
    public function build()
    {
	if (isset($_POST, $_POST['deezer_path']) && ($this->currentUser->getID() > 0) ) 
		{
			
			if ($_POST['deezer_path'] != "" && $_POST['deezer_id']!= "")
			{
				$currentUser = $this->userFactory->getCurrentUser();
				$currentUser->setPref('deezer_path', $this->args['deezer_path']);//$_POST['deezer_path']
				$currentUser->setPref('deezer_id', $_POST['deezer_id']);
			}
			
		}


        $app = $this->appList->getApp($this->appname);
        $config = $app->getConfig();
	

	$deezer_path = $this->userFactory->getCurrentUser()->getPref('deezer_path');
	$deezer_id = $this->userFactory->getCurrentUser()->getPref('deezer_id');

	if($deezer_id!="") 
		{
		$this->assign("playlistpath",$deezer_path);
		$this->assign("playlistid",$deezer_id);
		}
	else
		{
		$this->assign("playlistpath",$config["playlist"]["path"]);
		$this->assign("playlistid",$config["playlist"]["id"]);
		}


    }
}

?>
