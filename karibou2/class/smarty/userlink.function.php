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
	return userlink( $array , $smarty->getAppList(), $smarty );
}

function userlink($params , $appList, &$smarty)
{
	$userlink = "";

	//Ajout pour eviter les eventuels problemes de notice
	if (is_array($params))
	{
		if (isset($params["user"]))
		{
 			$user = $params["user"];
			if ( ($user->getFirstname() != "") || ($user->getLastname() != "") )
			{
				$fullName = addslashes($user->getFullName());
			}
			else
			{
				$fullName = "<em>".$user->getLogin()."</em>";
			}
			//$userlink = <a href="{kurl app="annuaire" username=$user.object->getLogin()}" class="userlink"{if $islogged} onMouseover="showhint('<img src=\'{$user.object->getPicturePath()}\' />{if ($user.object->getFirstname() != "")}<span>{$user.object->getFirstname()|escape:"quotes"} {$user.object->getLastname()|escape:"quotes"}</span>{else}<em>{$user.object->getLogin()}</em>{/if}','hint_profile');" onMouseout="hidehint()"{/if}>{if $user.object->getSurname() != ""}{$user.object->getSurname()}{elseif $user.object->getFirstname() != "" || $user.object->getLastname() != ""}{$user.object->getFirstname()} {$user.object->getLastname()}{else}{$user.object->getLogin()}{/if}</a>
			$userlink = "<a href=\"".smarty_function_kurl(array("app" => annuaire, "username" => $user->getLogin()), $smarty)."\" class=\"userlink\"";
			if (isset($params["showpicture"]) && ($params["showpicture"] === TRUE) )
			{
				$userlink .= " onMouseover=\"showhint('<img src=\'".$user->getPicturePath()."\' /><span>".$fullName."</span>','hint_profile');\" onMouseout=\"hidehint()\"";
			}
			$userlink .= ">".$user->getDisplayName()."</a>";
    	}
	}
	else
   {
        Debug::kill("Array needed in userlink");
   }
    
	return $userlink;
}

?>