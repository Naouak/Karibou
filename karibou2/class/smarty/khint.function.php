<?php
/**
 * @copyright 2005 Antoine Leclercq <http://antoine.leclercq.netcv.org>
 *
 * @license http://www.gnu.org/licenses/lgpl.html Lesser GNU Public License
 * See the enclosed file COPYING for license information (LGPL).
 * 
 * @package framework
 **/
 

function smarty_function_khint($params, &$smarty)
{
	$hint = "";
	//Ajout pour eviter les eventuels problemes de notice
	if (is_array($params))
	{
		if (isset($params["langmessage"]))
		{
			$message = addslashes(htmlspecialchars($smarty->translate_key($params["langmessage"])));
		}
		elseif (isset($params["message"]))
		{
			$message = addslashes(htmlspecialchars($params["message"]));
		}
		
	
		if (isset($message))
		{
			
			if (isset($params["type"]))
			{
				$type = $params["type"];
			}
			else
			{
				$type = "help";
			}
			
			//return code that will be located inside a tag
			$hint .= " onMouseover=\"showhint('".$message."','hint_".$type."');\" onMouseout=\"hidehint()\"";

			if (!isset($params["insidetag"]) || $params["insidetag"] === FALSE)
			{
				//return full hint zone
				$hint = "<div class=\"overzone overzone_".$type."\" ".$hint."\"><span><img src=\"/themes/karibou/images/hintbox/small/blank.png\" title=\"$hint\"></span></div>";
			}
    	}
	}
	else
   {
        Debug::kill("Array needed in hint");
   }
    
	return $hint;
}

?>