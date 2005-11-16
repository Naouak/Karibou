<?php 

/**
 * @copyright 2005 Antoine Leclercq <http://antoine.leclercq.netcv.org>
 *
 * See the enclosed file COPYING for license information (GPL). If you
 * did not receive this file, see http://www.gnu.org/licenses/gpl.html.
 * 
 * @package applications
 **/
 

class NetCVDisplay extends Model
{
	public function build()
	{
		$app = $this->appList->getApp($this->appname);
		$config = $app->getConfig();
	
		if (isset($this->args['hostname']) && ($this->args['hostname'] != ""))
		{
			//Récupération du group
			$myNetCVGroup = new NetCVGroup($this->db, $this->args['hostname'], TRUE /* readonly */);
			//Création du user à partir du groupe
			$myNetCVUser = new NetCVUser($this->db, $myNetCVGroup->getInfo("user_id"));
			//Création de la liste de CV
			$myNetCVSingleCVList = $myNetCVGroup->returnCVListObject();
			//Création du singlecv à partir de la langue
			if (isset($this->args["lang"]) && ($this->args["lang"] != ""))
			{
				$myNetCVSingleCV = $myNetCVGroup->CVList->returnCVByLang($this->args["lang"]);
			}
			if ( (!isset($myNetCVSingleCV)) || ($myNetCVSingleCV == FALSE) )
			{
				$myNetCVSingleCV = $myNetCVSingleCVList->returnDefaultCV();
				
				if ( (!isset($myNetCVSingleCV)) || ($myNetCVSingleCV == FALSE) )
				{
					Debug::kill("No CV at this address");
				}
			}

			$myNetCVPersonalInfo = $myNetCVUser->returnPersonalInfo($myNetCVSingleCV->getInfo("id"));
			$myNetCV = $myNetCVSingleCV->getContent();

			if (isset($this->args['preview']) && ($this->args['preview'] == "1") )
			{
				$this->assign("preview", TRUE);
			}
			else
			{
				$this->assign("preview", FALSE);
			}

			//Verification de la presence d'erreur et affection du message d'erreur a afficher
			$this->assign("netcvMessages", $this->formMessage->getSession());
			$this->formMessage->flush();

		}
		else
		{
			Debug::kill("Aucun CV a afficher");
		}
		/*
		$this->assign("gid", $myNetCVGroup->getInfo("id"));
		$this->assign("cvid", $myNetCVSingleCV->getInfo("id"));
		*/
		$this->assign("myNetCVGroup", $myNetCVGroup);
		$this->assign("myNetCVPersonalInfo", $myNetCVPersonalInfo);
		$this->assign("myNetCV", $myNetCV);
		$this->assign("myNetCVUser", $myNetCVUser);
		$this->assign("myNetCVSingleCV", $myNetCVSingleCV);
		$this->assign("myNetCVSingleCVList", $myNetCVSingleCVList->returnCVList());
		$this->assign("config", $config);
		
		$myNetCVLanguage = new NetCVLanguage();
		$this->assign("myNetCVLanguage", $myNetCVLanguage);
		
		if ( ($myNetCVSingleCV->countSections() < NETCV_MIN_SECTIONS) || ($myNetCVSingleCV->countElements() < NETCV_MIN_ELEMENTS) )
		{
		
		}
	}
}

?>