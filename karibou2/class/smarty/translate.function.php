<?php
/**
 * @copyright 2005 Jonathan Semczyk <jonathan.semczyk@free.fr>
 *
 * @license http://www.gnu.org/licenses/lgpl.html Lesser GNU Public License
 * See the enclosed file COPYING for license information (LGPL).
 * 
 * @package framework
 **/
 

function smarty_function_translate($params, &$smarty)
{
	if( isset($params['key']) )
	{
		return $smarty->translate_key($params['key']);
	}
	return "";
}

?>