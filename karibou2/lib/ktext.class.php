<?php
/**
 * @copyright 2005 Antoine Leclercq <http://antoine.leclercq.netcv.org>
 *
 * @license http://www.gnu.org/licenses/lgpl.html Lesser GNU Public License
 * See the enclosed file COPYING for license information (LGPL).
 *
 * @package lib
 **/

/**
 * Gestion de mise en forme de texte
 * Cette classe va permettre de centraliser le formattage de texte
 *
 * @todo indent properly
 * @package lib
 */
class KText
{
	function __construct() {

	}

	function checkEmail ($email)
	{
		$regex =
		  '^'.
		  '[_a-z0-9-]+'.
		  '(\.[_a-z0-9-]+)*'.
		  '@'.	  '[a-z0-9-]+'.
		  '(\.[a-z0-9-]{2,})+'.
		  '$';

		if (eregi($regex, $email))
		{
		  return TRUE;
		}
		else
		{
		  return FALSE;
		}
	}

	function epureString ($string)
	{
		$string = $this->removeAccents($string);
		$string = preg_replace("/[^.A-Za-z0-9 _-]/", '',$string);
		return $string;
	}

	//Thanks SSI for the following methods
	function removeAccents ($string)
	{
		if ($this->seemsUTF8($string)) {
				$chars = array(
					chr(195).chr(128) => 'A', chr(195).chr(129) => 'A',
					chr(195).chr(130) => 'A', chr(195).chr(131) => 'A',
					chr(195).chr(132) => 'A', chr(195).chr(133) => 'A',
					chr(195).chr(135) => 'C', chr(195).chr(136) => 'E',
					chr(195).chr(137) => 'E', chr(195).chr(138) => 'E',
					chr(195).chr(139) => 'E', chr(195).chr(140) => 'I',
					chr(195).chr(141) => 'I', chr(195).chr(142) => 'I',
					chr(195).chr(143) => 'I', chr(195).chr(145) => 'N',
					chr(195).chr(146) => 'O', chr(195).chr(147) => 'O',
					chr(195).chr(148) => 'O', chr(195).chr(149) => 'O',
					chr(195).chr(150) => 'O', chr(195).chr(153) => 'U',
					chr(195).chr(154) => 'U', chr(195).chr(155) => 'U',
					chr(195).chr(156) => 'U', chr(195).chr(157) => 'Y',
					chr(195).chr(160) => 'a', chr(195).chr(161) => 'a',
					chr(195).chr(162) => 'a', chr(195).chr(163) => 'a',
					chr(195).chr(164) => 'a', chr(195).chr(165) => 'a',
					chr(195).chr(167) => 'c', chr(195).chr(168) => 'e',
					chr(195).chr(169) => 'e', chr(195).chr(170) => 'e',
					chr(195).chr(171) => 'e', chr(195).chr(172) => 'i',
					chr(195).chr(173) => 'i', chr(195).chr(174) => 'i',
					chr(195).chr(175) => 'i', chr(195).chr(177) => 'n',
					chr(195).chr(178) => 'o', chr(195).chr(179) => 'o',
					chr(195).chr(180) => 'o', chr(195).chr(181) => 'o',
					chr(195).chr(182) => 'o', chr(195).chr(182) => 'o',
					chr(195).chr(185) => 'u', chr(195).chr(186) => 'u',
					chr(195).chr(187) => 'u', chr(195).chr(188) => 'u',
					chr(195).chr(189) => 'y', chr(195).chr(191) => 'y',
					chr(197).chr(146) => 'OE', chr(197).chr(147) => 'oe',
					chr(197).chr(160) => 'S', chr(197).chr(161) => 's',
					chr(197).chr(189) => 'Z', chr(197).chr(190) => 'z',
					chr(226).chr(130).chr(172) => 'E');

				$string = strtr($string, $chars);
		} else {
				// Assume ISO-8859-1 if not UTF-8
				$chars['in'] = chr(128).chr(131).chr(138).chr(142).chr(154).chr(158)
						.chr(159).chr(162).chr(165).chr(181).chr(192).chr(193).chr(194)
						.chr(195).chr(196).chr(197).chr(199).chr(200).chr(201).chr(202)
						.chr(203).chr(204).chr(205).chr(206).chr(207).chr(209).chr(210)
						.chr(211).chr(212).chr(213).chr(214).chr(216).chr(217).chr(218)
						.chr(219).chr(220).chr(221).chr(224).chr(225).chr(226).chr(227)
						.chr(228).chr(229).chr(231).chr(232).chr(233).chr(234).chr(235)
						.chr(236).chr(237).chr(238).chr(239).chr(241).chr(242).chr(243)
						.chr(244).chr(245).chr(246).chr(248).chr(249).chr(250).chr(251)
						.chr(252).chr(253).chr(255);

				$chars['out'] = "EfSZszYcYuAAAAAACEEEEIIIINOOOOOOUUUUYaaaaaaceeeeiiiinoooooouuuuyy";

				$string = strtr($string, $chars['in'], $chars['out']);
				$double_chars['in'] = array(chr(140), chr(156), chr(198), chr(208), chr(222), chr(223), chr(230), chr(240), chr(254));
				$double_chars['out'] = array('OE', 'oe', 'AE', 'DH', 'TH', 'ss', 'ae', 'dh', 'th');
				$string = str_replace($double_chars['in'], $double_chars['out'], $string);
		}

		return $string;

	}

	function seemsUTF8($Str)
	{
			for ($i=0; $i<strlen($Str); $i++) {
					if (ord($Str[$i]) < 0x80) continue;
					elseif ((ord($Str[$i]) & 0xE0) == 0xC0) $n=1; # 110bbbbb
					elseif ((ord($Str[$i]) & 0xF0) == 0xE0) $n=2; # 1110bbbb
					elseif ((ord($Str[$i]) & 0xF8) == 0xF0) $n=3; # 11110bbb
					elseif ((ord($Str[$i]) & 0xFC) == 0xF8) $n=4; # 111110bb
					elseif ((ord($Str[$i]) & 0xFE) == 0xFC) $n=5; # 1111110b
					else return false;
					for ($j=0; $j<$n; $j++) {
							if ((++$i == strlen($Str)) || ((ord($Str[$i]) & 0xC0) != 0x80))
							return false;
					}
			}
			return true;
	}
}
?>