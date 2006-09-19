<?php 
/* 
* Smarty plugin 
* ------------------------------------------------------------- 
* File:     highlight.modifier.php 
* Type:     modifier 
* Purpose:  retourne les mots clés en gras
* ------------------------------------------------------------- 
*/ 
function smarty_modifier_highlight($string, $keywords)
{ 
    $string = preg_replace('/('.$keywords.')/i', "<strong>\\1</strong>", $string);
    //$keywords, '<strong>'.$keywords.'</strong>', $string);
    return $string;
}

function highlight($string, $keywords)
{
    return smarty_modifier_highlight($string, $keywords);
}
?>