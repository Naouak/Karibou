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

class NetCVSectionSave extends FormModel
{
	public function build()
	{
		if (isset($_POST["netcvSingleCVId"],$_POST["netcvGroupId"])) {
			$cvid = $_POST["netcvSingleCVId"];
			$gid = $_POST["netcvGroupId"];
						
			if(isset($_POST["netcvSectionName"]) && ($_POST["netcvSectionName"] != "")) {
				$myNetCVUser = new NetCVUser($this->db, $this->currentUser,TRUE);
				$myNetCVSingleCV = $myNetCVUser->returnSingleCV(FALSE, $_POST["netcvSingleCVId"]);
				$myNetCVSingleCVContent = $myNetCVSingleCV->getContent();

				if( isset($_POST["netcvSectionId"]) && ($_POST["netcvSectionId"] != "") ) {
					$sid_int = intval($_POST["netcvSectionId"]);
					//Teste si l'identifiant de section passe en parametre est un nombre					
					if ($sid_int > 0) {
						$myNetCVSingleCVContent->updateElementInfos ($_POST["netcvSectionId"],$_POST["netcvSectionName"]);
						$this->formMessage->add (FormMessage::SUCCESS, gettext("SECTION_MODIFIED"));
					}
				} else {
					$sid_int = $myNetCVSingleCVContent->insertElementLevel (0,$_POST["netcvSectionName"]);
					$this->formMessage->add (FormMessage::SUCCESS, gettext("SECTION_ADDED"));
				}
				
				if (isset($_POST["netcvToSectionList"]) && ($_POST["netcvToSectionList"] != "") ) {
					$this->setRedirectArg('app', 'netcv');
					$this->setRedirectArg('page', 'cvsectionlist');
					$this->setRedirectArg('gid',  $gid);
					$this->setRedirectArg('cvid', $cvid);
				} 
				else
				{
					$this->setRedirectArg('app', 'netcv');
					$this->setRedirectArg('page', 'cvitemlist');
					$this->setRedirectArg('gid',  $gid);
					$this->setRedirectArg('cvid', $cvid);
					$this->setRedirectArg('pid', $sid_int);
				}

				$this->formMessage->setSession();
			} else {
				//Le nom de la section doit etre renseigne
			}
		} else {
			//Aucun groupe ou CV choisi
		}
	}
}
?>
