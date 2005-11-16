<?php 

/**
 * @copyright 2005 Antoine Leclercq <http://antoine.leclercq.netcv.org>
 *
 * See the enclosed file COPYING for license information (GPL). If you
 * did not receive this file, see http://www.gnu.org/licenses/gpl.html.
 * 
 * @package applications
 **/
 
 
class NetCVGroupSave extends FormModel
{
	public function build()
	{
    	if(isset($_POST["netcvGroupName"], $_POST["netcvGroupHostName"], $_POST["netcvGroupDiffusion"], $_POST["netcvGroupEmailDisplay"], $_POST["netcvGroupSkin"]) && ($_POST["netcvGroupName"] != "") && ($_POST["netcvGroupHostName"] != "") && ($_POST["netcvGroupDiffusion"] != "")) {
    		$myNetCVUser = new NetCVUser($this->db, $this->currentUser,TRUE);
			$myNetCVGroupList = $myNetCVUser->returnCVGroupList();

			//Vérification que le hostname n'est pas déjà utilisé
			$groupSelectReq = "
				SELECT count(*) as nb_hostname
				FROM netcv_resumes_group
				WHERE hostname = '".$_POST["netcvGroupHostName"]."'";
			
			if (isset($_POST["gid"]) && $_POST["gid"] != "") {			
				$groupSelectReq .= " AND id != '".$_POST["gid"]."'";
			}

			$groupSelectRes = $this->db->prepare($groupSelectReq);
			$groupSelectRes->execute();
			$nbHostname = $groupSelectRes->fetchAll(PDO::FETCH_ASSOC);
			$nbHostname = $nbHostname[0]["nb_hostname"];
			if ($nbHostname > 0)
			{
				$this->formMessage->add (FormMessage::FATAL_ERROR, $this->languageManager->getTranslation("ADDRESS_IN_USE"));
				$this->setRedirectArg('app', 'netcv');
				$this->setRedirectArg('page', 'cvgroupmodify');
				$this->setRedirectArg('gid',  $_POST["gid"]);
			}
			else
			{
	    		if( isset($_POST["gid"]) && ($_POST["gid"] != "") ) {
	                $gid_int = intval($_POST["gid"]);
	                //Teste si l'identifiant de section passe en parametre est un nombre
						if ($gid_int > 0) {
							$myNetCVGroupList->updateInfos ($gid_int, array("name" => $_POST["netcvGroupName"], "hostname" => $_POST["netcvGroupHostName"], "skin_id" => $_POST["netcvGroupSkin"], "diffusion" => $_POST["netcvGroupDiffusion"], "emailDisplay" => $_POST["netcvGroupEmailDisplay"]));
							$this->formMessage->add (FormMessage::SUCCESS, $this->languageManager->getTranslation("CV_MODIFIED"));
	                }
					$this->setRedirectArg('app', 'netcv');
					$this->setRedirectArg('page', '');
	    		} else {
	
					$gid = $myNetCVGroupList->insertGroup (array("name" => $_POST["netcvGroupName"], "hostname" => $_POST["netcvGroupHostName"], "skin_id" => $_POST["netcvGroupSkin"], "diffusion" => $_POST["netcvGroupDiffusion"], "emailDisplay" => $_POST["netcvGroupEmailDisplay"]));
					$this->formMessage->add (FormMessage::SUCCESS, $this->languageManager->getTranslation("CV_ADDED"));
	
					$this->setRedirectArg('app', 'netcv');
					$this->setRedirectArg('page', 'cvsinglecvmodify');
					$this->setRedirectArg('gid',  $gid);
				}
			}
		} else {
            //Le nom du groupe doit etre renseigne
				$this->formMessage->add (FormMessage::FATAL_ERROR, $this->languageManager->getTranslation("NO_EMPTY_FIELD"));
				$this->setRedirectArg('app', 'netcv');
				$this->setRedirectArg('page', 'cvgroupmodify');
		}

        $this->formMessage->setSession();
        $lastFormMessage = end($this->formMessage->messages);
		if (($lastFormMessage != 0) &&isset($_POST["netcvGroupName"], $_POST["netcvGroupHostName"], $_POST["netcvGroupDiffusion"], $_POST["netcvGroupEmailDisplay"], $_POST["netcvGroupSkin"]))
		{
			$this->setCurrentGroupInfo($_POST["netcvGroupName"], $_POST["netcvGroupHostName"], $_POST["netcvGroupDiffusion"], $_POST["netcvGroupEmailDisplay"], $_POST["netcvGroupSkin"]);
		}
	}
	
	function setCurrentGroupInfo ($title, $hostname, $diffusion, $emaildisplay, $design) {

		$_SESSION["netcvGroup"]["title"]		= $title;
		$_SESSION["netcvGroup"]["hostname"]		= $hostname;
		$_SESSION["netcvGroup"]["diffusion"]	= $diffusion;
		$_SESSION["netcvGroup"]["emaildisplay"]	= $emaildisplay;
		$_SESSION["netcvGroup"]["design"]		= $design;
	}
}
?>
