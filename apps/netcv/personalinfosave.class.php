<?php 

/**
 * @copyright 2005 Antoine Leclercq <http://antoine.leclercq.netcv.org>
 *
 * See the enclosed file COPYING for license information (GPL). If you
 * did not receive this file, see http://www.gnu.org/licenses/gpl.html.
 * 
 * @package applications
 **/

class NetCVPersonalInfoSave extends FormModel
{
	public function build()
	{
	
		if (isset($_POST["firstname"],$_POST["lastname"],$_POST["email"])) {
			$userInfos = array (
				"firstname" 		=> 	$_POST["firstname"],
				"lastname"			=> 	$_POST["lastname"],
				"email"				=> 	$_POST["email"],
				"address1stline"	=>		$_POST["address1stline"],
				"addresscitycode"	=> 	$_POST["addresscitycode"],
				"addresscity"		=>		$_POST["addresscity"],
				"phonehome"			=> 	$_POST["phonehome"],
				"phonecompany"		=> 	$_POST["phonecompany"],
				"phonemobile"		=> 	$_POST["phonemobile"],
				"otherinfos"		=> 	$_POST["otherinfos"],
				"jobtitle" 			=> 	$_POST["jobtitle"]
			);
			
			if (isset($_POST["gid"],$_POST["cvid"]) && ($_POST["cvid"] != "") && ($_POST["gid"] != "") ) {
				$myNetCVUser = new NetCVUser($this->db, $this->currentUser,TRUE);
				$myNetCVSingleCV = new NetCVSingleCV ($this->db);
				$myNetCVSingleCV->load ($myNetCVUser->getInfo("id"), $_POST["gid"], $_POST["cvid"]);
				$myNetCVSingleCV->updateInfos($userInfos);
				$this->formMessage->add (FormMessage::SUCCESS, $this->languageManager->getTranslation("CV_PERSONAL_INFO_UPDATED"));
			} else {
		    	$myNetCVUser = new NetCVUser($this->db, $this->currentUser,TRUE);

				$setValues = "";
				foreach ($userInfos as $key => $value) {
					$setValues .= $key." = '".$value."',"; 
				}
		
				$updatePersonalInfoReq = "
					UPDATE netcv_users 
					SET
						".$setValues."
						date_modification	= '".date("Y-m-d H:i:s")."'
					WHERE username='".$myNetCVUser->getInfo("username")."'";
				$updatePersonalInfoRes = $this->db->prepare($updatePersonalInfoReq);
				$updatePersonalInfoRes->execute();
				$this->formMessage->add (FormMessage::SUCCESS, $this->languageManager->getTranslation("DEFAULT_PERSONAL_INFO_UPDATED"));
			}
		}
		$this->formMessage->setSession();
	}
}
?>
