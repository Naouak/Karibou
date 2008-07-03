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
	protected $userichtext;

	function __construct($userichtext)
	{
		$this->userichtext = $userichtext;
	}
	
	function transform($in)
	{
		/*
			PHP Regexp (preg_replace)
			U : tente de repérer l'expression la plus petite possible
			i : insensible à la casse
		*/
		$patterns = array("/\[url=(.*)\](.*)\[\/url\]/Ui", 	// [url=http://chezmoicamarche.org]test[/url]
				  "/\[url\](.*)\[\/url\]/Ui",		// [url]http://chezmoicamarche.org[/url]
				  "/\[b\](.*)\[\/b\]/Ui",		// [b]texte en gras[/b]
				  "/\[i\](.*)\[\/i\]/Ui",		// [i]texte en italique[/i]
				  "/\[color=(#[a-fA-F0-9]{3,6}|[a-z]*)\](.*)\[\/color\]/Ui");	// [color=blue]texte en bleu[/color] ou [color=#FFFFFF]texte blanc[/color] (ou #fff)

		if ($this->userichtext) {
			$replaces = array("<a href=\"\\1\" target=\"_blank\">\\2</a>",
					"<a href=\"\\1\" target=\"_blank\">\\1</a>",
					"<span style=\"font-weight:bold;\">\\1</span>",
					"<span style=\"font-style:italic;\">\\1</span>",
					"<span style=\"color:\\1;\">\\2</span>");
		} else {
			$replaces = array("\\1",
					"\\1",
					"\\1",
					"\\1",
					"\\2");
		}
		$out = strip_tags($out);
		$out = preg_replace($patterns, $replaces, $in);
		return $out;
	}
	
}

?>