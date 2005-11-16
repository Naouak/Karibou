<?php
/**
 * @copyright 2005 Jonathan Semczyk <jonathan.semczyk@free.fr>
 *
 * @license http://www.gnu.org/licenses/lgpl.html Lesser GNU Public License
 * See the enclosed file COPYING for license information (LGPL).
 * 
 * @package framework
 **/
 

function smarty_function_kurl($params, &$smarty)
{
	$array = array_merge( array("app" => $smarty->getApp()), $params);
	return kurl( $array , $smarty->getAppList() );
}

function kurl(&$params , $appList)
{
	$app = "";
	$page = "";
	$url = "";
	$first = true;
	$proto = "http";

	//Ajout pour eviter les eventuels problemes de notice
	if (is_array($params))
	{
    	foreach($params as $key => $value)
    	{
    		switch($key)
    		{
    			case "proto":
    				$proto = $value;
    				break;
    			case "server":
    				$server = $value;
    				break;
    			case "app":
    					$app = $value;
    				break;
    			case "page":
    					$page = $value;
    				break;
    			case "action":
    				$page = $value;
    				$url = "";
    				break 2;
    			default:
                    //Creation d'un nouvel argument, instanciation de la classe a partir du nom du parametre
                    $myApp = $appList->getApp($app);
                    $class = $myApp->getArgClass($page, $key) ;
    				$arg = new $class($value);

    				//S'il y a plusieurs arguments, on les separe par des virgules
    				if( !$first ) $url .= ',';

    				//Concatenation de la fin de l'URL
    				$url .= $arg->getUrlArgument();
    				$first = false;
    				break;
    		}
    	}
	}
    else
    {
        Debug::kill("Array needed in kURL");
    }
    
    if( !empty($app) ) $app = $app.'/';
    if( !empty($page) ) $page = $page.'/';
    
	$url = '/'.$app.$page.$url;
	if(isset($server))
	{
		$url = $proto."://".$server.$url;
	}
	return $url;
}

?>
