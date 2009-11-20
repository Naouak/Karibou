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
	$db = Database::instance();
	$count = $db->prepare("SELECT count(*) FROM comment WHERE deleted=0 AND key_id=:id;");
	$count->execute(array(":id" => $params["id"]));
	
    return $count->fetchColumn(0);
}
