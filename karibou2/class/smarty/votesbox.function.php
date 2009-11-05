<?php
/**
 * @copyright 2009 Gilles DEHAUDT <tonton1728@gmail.com>
 *
 * @license http://www.gnu.org/licenses/lgpl.html Lesser GNU Public License
 * See the enclosed file COPYING for license information (LGPL).
 *-
 * @package framework
 **/


function smarty_function_votesbox($params,&$smarty){
	$votes = VotesScoreFactory::getInstance();
	$score = $votes->getScore($params['id']);
	$currentUser = UserFactory::instance()->getCurrentUser();
	$voted = $votes->Voted($params['id'],$currentUser->getID());

	$box = intval($score[0]) . " (" . intval($score[1]) . ")";

	if(!$voted && $currentUser->isLogged()){
		$box .= " (";
		if($params["type"]=="miniapp") {
			$box.="<a onclick=\"Votes.more(".$params['id'].",\$app(this).refresh.bind(\$app(this))); return false;\"> + </a> / <a onclick=\"Votes.less(".$params['id'].",\$app(this).refresh.bind(\$app(this))); return false;\" > - </a>";
		}
		else{
			$box.="<a onclick=\"Votes.more(".$params['id'].",setTimeout('window.location.reload()',100)); return false;\"> + </a> / <a onclick=\"Votes.less(".$params['id'].",setTimeout('window.location.reload()',100)); return false;\" > - </a>";
		}
		$box .= ")";
	}

	return $box;
}
