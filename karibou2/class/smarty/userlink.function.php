<?php
/**
 * @copyright 2005 Antoine Leclercq <http://antoine.leclercq.netcv.org>
 *
 * @license http://www.gnu.org/licenses/lgpl.html Lesser GNU Public License
 * See the enclosed file COPYING for license information (LGPL).
 * 
 * @package framework
 **/
 

function smarty_function_userlink($params, &$smarty)
{
	$array = array_merge($params);
	return userlink( $array , $smarty->getAppList());
}

function userlink($params , $appList = FALSE)
{

	//Gestion des templates PHP
	if ($appList === FALSE)
	{
		//Cas du template PHP
		if (isset($GLOBALS['phpTemplateCurrentAppList'], $GLOBALS['phpTemplateCurrentAppName']))
		{
			$appList = $GLOBALS['phpTemplateCurrentAppList'];
			$params = array_merge( array("app" => $GLOBALS['phpTemplateCurrentAppName']), $params);
		}
		else
		{
			Debug::kill('Erreur : appList n\'est pas initialis� (cas du template PHP).');
		}
	}
	else
	{
		//Cas d'un template TPL (Smarty) o� le $appList est d�j� charg�
	}


	$userlink = "";

	//Ajout pour eviter les eventuels problemes de notice
	if (is_array($params))
	{
		if (isset($params["user"]))
		{
 			$user = $params["user"];
 			
			if ( is_object($user) && $user->getLogin() )
			{
 				$firstname = $user->getFirstName();
	 			$lastname = $user->getLastName();

				if ( ($firstname != "") && ($lastname != "" ) )
				{
					$fullName = addslashes($user->getFullName());
				}
				else
				{
					$fullName = "<em>".$user->getLogin()."</em>";
				}

				$userlink = "<a href=\"".kurl(array("app" => 'annuaire', "username" => $user->getLogin()), $appList)."\""; 
				if (isset($params["noicon"]) && ($params["noicon"] === true))
					$userlink .= " class=\"userlink_noicon\"";
				else
					$userlink .= " class=\"userlink\"";
				if (isset($params["showpicture"]) && ($params["showpicture"] === TRUE) )
				{
					$userlink .= " onMouseover=\"showhint('<img src=\'".$user->getPicturePath()."\' /><span>";
					$userlink .= $fullName;
					if ($appList) {
						$groups = $appList->userFactory->getGroupsFromUser($user);
						if(!empty($groups)) {
							$userlink .= "<br /></span><span>"._("Groupes")." :";
							foreach ($groups as $group) {
								if (abs($group->getLeft() - $group->getRight()) == 1)
									$userlink .= " " . $group->getName();
							}
						}
					}
					$g = GeolocationFactory::getInstance();
					if(isset($params["showlocation"]) && ($params["showlocation"] === true)) {
						$info = $g->prepareForUser($user);
						if($info->getLocation() != "") $userlink .= "<br /></span><span>".addslashes($info->getLocation());
					}
					$userlink .= "</span>','hint_profile');\" onMouseout=\"hidehint()\"";
				}
				if (isset($params["showfullname"]) && ($params["showfullname"] === TRUE) )
					$userlink .= ">" . $fullName . "</a>";
				else
					$userlink .= ">".$user->getDisplayName()."</a>";
			}
			else
			{
				$userlink .= "?";
			}
    	}
	}
	else
   {
        Debug::kill("Array needed in userlink");
   }
    
	return $userlink;
}

?>
