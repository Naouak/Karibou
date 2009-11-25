<?php
/**
 * @copyright 2009 Gilles DEHAUDT <tonton1728@gmail.com>
 *
 * @license http://www.gnu.org/licenses/lgpl.html Lesser GNU Public License
 * See the enclosed file COPYING for license information (LGPL).
 *
 * @package framework
 **/


function smarty_function_commentbox($params,&$smarty){
	$db = Database::instance();
	$currentuser = UserFactory::instance()->getCurrentUser();
	$count = $db->prepare("SELECT count(*) FROM comment WHERE deleted=0 AND key_id=:id;");
	$count->execute(array(":id" => $params["id"]));
	if (!array_key_exists("nounread",$params)){
		$unreadcount = $db->prepare("SELECT count(*) FROM comment AS c LEFT JOIN comment_read AS cr ON c.id=cr.comment AND cr.user=:user_id WHERE c.key_id=:id AND c.deleted =0  AND cr.comment IS NULL;");
		$unreadcount->bindValue(":user_id",$currentuser->getID());
		$unreadcount->bindValue(":id",$params["id"]);
		$unreadcount->execute();
		$result = $count->fetchColumn(0);
		if ( ($unread=$unreadcount->fetchColumn(0)) != 0){
			$result .= "(".$unread.")";
		}
	}
    return $result;
}
