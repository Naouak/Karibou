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
    $box = "";
    if (!$params["voted"] && $params["type"]=="miniapp"){
        $box = "<a onclick=\"Votes.more(".$params['id'].",\$app(this).refresh.bind(\$app(this))); return false;\"> + </a> / <a onclick=\"Votes.less(".$params['id'].",\$app(this).refresh.bind(\$app(this))); return false;\" > - </a>";
    }
// if the vote sytem is not in a miniapp, we don't use refresh system from default
    elseif(!$params["voted"]){
        $box = "<a onclick=\"Votes.more(".$params['id']."); return false;\"> + </a> / <a onclick=\"Votes.less(".$params['id']."); return false;\" > - </a>";
    }
    $box = $box."score total ".$params['score']." nombre de votants ".$params['votant'];
    return $box;
}
