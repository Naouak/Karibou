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

class NetCVSingleCVSave extends FormModel
{
	public function build()
	{
		if(isset($_POST["netcvGroupId"], $_POST["netcvSingleCVLang"])) {
			$gid = $_POST["netcvGroupId"];

			$cvInfos = array("lang" => $_POST["netcvSingleCVLang"]);
			
			$myNetCVUser = new NetCVUser($this->db, $this->currentUser,TRUE);
			if ($_POST["netcvSingleCVId"] != "") {
				$cvid = $_POST["netcvSingleCVId"];
				//Mise a jour d'une version de CV existante
				$myNetCVUser->updateSingleCV($gid,$_POST["netcvSingleCVId"], $cvInfos);
				$this->setRedirectArg('app', 'netcv');
				$this->setRedirectArg('page', '');
				$this->formMessage->add (FormMessage::SUCCESS, $this->languageManager->getTranslation("TRANSLATION_MODIFIED"));
			} else {
				//Creation d'une nouvelle version du CV (langue)

				$cvid = $myNetCVUser->createSingleCV($gid, $cvInfos);
				
				$this->setRedirectArg('app', 'netcv');
				$this->setRedirectArg('page', 'cvsectionlist');
				$this->setRedirectArg('gid',  $gid);
				$this->setRedirectArg('cvid',  $cvid);
				$this->formMessage->add (FormMessage::SUCCESS, $this->languageManager->getTranslation("TRANSLATION_CREATED"));
			}
	
		} else {
			$this->setRedirectArg('app', 'netcv');
			$this->setRedirectArg('page', '');
		}
		$this->formMessage->setSession();
	}
}
?>
