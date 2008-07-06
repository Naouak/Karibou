<?php 

/**
 * @copyright 2008 Vincent Billey <http://vincent.billey.netcv.fr>
 *
 * See the enclosed file COPYING for license information (GPL). If you
 * did not receive this file, see http://www.gnu.org/licenses/gpl.html.
 * 
 * @package applications
 **/

class MinichatRendering
{	
	protected $userichtext;

	function __construct($userichtext)
	{
		$this->userichtext = $userichtext;
	}

	protected function bbcode($in)
	{
		/*
			PHP Regexp (preg_replace)
			U : tente de repérer l'expression la plus petite possible
			i : insensible à la casse
		*/
		$patterns = array("/\[url=(.*)\](.*)\[\/url\]/Ui", 	// [url=http://chezmoicamarche.org]test[/url]
				  "/\[url\](.*)\[\/url\]/Ui",		// [url]http://chezmoicamarche.org[/url]
				  "/^((ftp|http|https):\/\/(\w+:{0,1}\w*@)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?)/",		// http://chezmoicamarche.org
				  "/\[b\](.*)\[\/b\]/Ui",		// [b]texte en gras[/b]
				  "/\[i\](.*)\[\/i\]/Ui",		// [i]texte en italique[/i]
				  "/\[color=(#[a-fA-F0-9]{3,6}|[a-z]*)\](.*)\[\/color\]/Ui");	// [color=blue]texte en bleu[/color] ou [color=#FFFFFF]texte blanc[/color] (ou #fff)

		if ($this->userichtext) {
			$replaces = array("<a href=\"$1\" target=\"_blank\">$2</a>",
					  "<a href=\"$1\" target=\"_blank\">$1</a>",
					  "<a href=\"$0\" target=\"_blank\">$0</a>",
					  "<span style=\"font-weight:bold;\">$1</span>",
					  "<span style=\"font-style:italic;\">$1</span>",
					  "<span style=\"color:$1;\">$2</span>");
		} else {
			$replaces = array("$2",
					  "$1",
					  "$0",
					  "$1",
					  "$1",
					  "$2");
		}
		
		$out = preg_replace($patterns, $replaces, $in);
		return $out;
	}

	protected function wordwrap_if_needed($in) 
	{	
		$wordwrap_needed = false;
		$words = str_word_count(strip_tags($in), 1);

		foreach ($words as $word) 
		{
			if (strlen($word) > 34)
				$wordwrap_needed = true;
		}

		if ($wordwrap_needed)
			$out = wordwrap($in, 34, " ", true);
		else
			$out = $in;

		return $out;
	}
	
	public function transform($in)
	{
		$out = $this->wordwrap_if_needed($in);
		$out = $this->bbcode($out);
		return $out;
	}
	
}

?>