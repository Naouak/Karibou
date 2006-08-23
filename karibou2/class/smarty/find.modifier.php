<?php 
/* 
* Smarty plugin 
* ------------------------------------------------------------- 
* File:     find.modifier.php 
* Type:     modifier 
* Purpose:  trouve une les mots clés dans une chaine et retourne une partie de cette chaine avec les mots clés en gras
* ------------------------------------------------------------- 
*/ 
function smarty_modifier_find($string, $keywords)
{ 
    preg_match_all('/([^.]{0,40}'.$keywords.'[^.]{0,60})/i', $string, $regs);
    $array = $regs[1];
    if (count($array) > 0)
    {
        $string = "";

        $string1 = current($array);
        do
        {
            $string .= '...'.$string1.'... ';
        } while ( ($string1 = next($array)) && (strlen($string)<200)  );
    }
    else
    {
        $end = "";
        if (strlen($string) > 100)
            $end = '...';
        $string = substr($string, 0, 100).$end;
    }

    return $string;
} 
?>