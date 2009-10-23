<?php
/**
 * @copyright 2009 Gilles DEHAUDT <tonton1728@gmail.com>
 *
 * @license http://www.gnu.org/licenses/lgpl.html Lesser GNU Public License
 * See the enclosed file COPYING for license information (LGPL).
 *-
 * @package framework
 **/


function smarty_function_commentbox($params,&$smarty){
    $array = array_merge(array("app" => "commentaires"), $params);
    $url = kurl($array,$smarty->getAppList());
    return "";
}
