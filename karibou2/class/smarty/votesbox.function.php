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
        $box .= ($params["type"]=="miniapp") ?
            "<a onclick=\"Votes.more(".$params['id'].",\$app(this).refresh.bind(\$app(this))); return false;\"> + </a> / <a onclick=\"Votes.less(".$params['id'].",\$app(this).refresh.bind(\$app(this))); return false;\" > - </a>":
            "<a onclick=\"Votes.more(".$params['id']."); return false;\"> + </a> / <a onclick=\"Votes.less(".$params['id']."); return false;\" > - </a>";
        $box .= ")";
    }

    return $box;
}
