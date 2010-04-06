<?php
/**
 * @copyright 2008 Vincent Billey <http://vincent.billey.netcv.fr>
 * @copyright 2010 Rémy Sanchez <remy.sanchez@hyperthese.net>
 *
 * See the enclosed file COPYING for license information (GPL). If you
 * did not receive this file, see http://www.gnu.org/licenses/gpl.html.
 *
 * @package applications
 **/

class Emoticons
{
	protected $userFactory;
	protected $emoticon_dir;
	protected $emoticon_themes;

	function __construct($userFactory) {
		$this->userFactory = $userFactory;
		$this->emoticon_dir = substr(KARIBOU_STATICS_URL . "/emoticons", 1);
		$this->emoticon_themes = array("None");

		if (is_dir($this->emoticon_dir)) {
			if ($dir_handle = opendir($this->emoticon_dir)) {
				while (($file = readdir($dir_handle)) !== false) {
					if ($file[0] != ".")
						$this->emoticon_themes[] = $file;
				}
				closedir($dir_handle);
			}
		}
	}

	public function get_emoticon_themes() {
		return $this->emoticon_themes;
	}

	public function set_user_emoticon_theme($theme) {
		$currentUser = $this->userFactory->getCurrentUser();
		$prefName = 'emoticon_theme';
		$currentUser->setPref($prefName, $this->emoticon_themes[$theme]);
	}

	public function get_user_emoticon_theme() {
		return $this->userFactory->getCurrentUser()->getPref('emoticon_theme');
	}

	public function render($in) {
		$out = $in;
		$user_emoticon_dir = $this->emoticon_dir . "/" . $this->get_user_emoticon_theme() . "/";
		if (is_file($user_emoticon_dir . "/emoticons.xml")) {
			$xmlCache = new XMLCache(KARIBOU_CACHE_DIR.'/emoticons');
			$xmlCache->loadFile($user_emoticon_dir . "emoticons.xml", false);
			$xml_emoticon_list = $xmlCache->getXml();

			foreach($xml_emoticon_list->emoticon as $emoticon) {
				$string_array = array();
				foreach($emoticon->string as $string) {
					$string_array[] = "#((\s|^)".preg_quote(htmlspecialchars($string), '#')."(\s|$))#U";
				}
				$fileName = $emoticon['file'];
				if (!is_file($user_emoticon_dir . "/" . $fileName))
					if (is_file($user_emoticon_dir . "/" . $fileName . ".png"))
						$fileName .= ".png";
					else if (is_file($user_emoticon_dir . "/" . $fileName . ".gif"))
						$fileName .= ".gif";
				$out = preg_replace($string_array, "$2<img src=\"" . $user_emoticon_dir . $fileName ."\" />$3", $out);
			}
		}

		return $out;
	}

	public function getMatchArray($theme) {
		$out = array(
			"picts" => array(),
			"strings" => array()
		);

		$url = $this->emoticon_dir . "/" . $theme . "/";
		$dir = KARIBOU_WEBSITE_DIR . "/" . $url;

		if(is_file($dir . "emoticons.xml")) {
			$xml = new XMLCache(KARIBOU_CACHE_DIR.'/emoticons');
			$xml->loadFile($dir . "emoticons.xml", false);
			$list = $xml->getXml();

			foreach($list->emoticon as $e) {
				// Step 1 : find the file and encode it
				$file = $e['file'];
				if(!is_file($dir . $file)) {
					if(is_file($dir . $file . ".png")) {
						$file .= ".png";
					} elseif(is_file($dir . $file . ".gif")) {
						$file .= ".gif";
					} else {
						continue;
					}
				}

				$out['picts'][$file] = $url . $file;

				// Step 2 : list strings
				foreach($e->string as $str) {
					$out['strings'][] = array(
						'str' => preg_replace("/([\\\.\$\[\]\(\)\{\}\^\?\*\+\-])/", "\\\\$1", filter_var($str, FILTER_SANITIZE_STRING)),
						'pict' => $file
					);
				}
			}
		}

		return $out;
	}
}
