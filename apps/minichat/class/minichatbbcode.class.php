<?php 

/**
 * @copyright 2008 Vincent Billey <http://vincent.billey.netcv.fr>
 *
 * See the enclosed file COPYING for license information (GPL). If you
 * did not receive this file, see http://www.gnu.org/licenses/gpl.html.
 * 
 * @package applications
 **/

class MinichatBBCode
{	
	function __construct()
	{
	
	}
	
	function transform($in)
	{
		$patterns = array("/\[url\](.*)\[\/url\]/U",
				  "/\[color=(#[1-9a-fA-F]{3,6}|[a-z]*)\](.*)\[\/color\]/U");
		$replaces = array("<a href=\"\\1\" target=\"_blank\">\\1</a>",
				  "<span style=\"color:\\1;\">\\2</span>");
		$out = preg_replace($patterns, $replaces, $in);
		return $out;
	}
	
}

?>