<?php
/**
 * Fonction permettant a Smarty d'initialiser le header dans un template
 *
 * @license http://www.gnu.org/licenses/lgpl.html Lesser GNU Public License
 * See the enclosed file COPYING for license information (LGPL).
 * 
 * @package framework
 **/
function insert_header($params)
{
   // this function expects $content argument
   if (empty($params['content'])) {
       return;
   }
   header($params['content']);
   return;
}

?>
