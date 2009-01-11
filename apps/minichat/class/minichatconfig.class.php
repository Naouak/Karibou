<?php
/**
 * @copyright 2008 Pinaraf <pinaraf@pinaraf.info>
 *
 * @license http://www.gnu.org/licenses/gpl.html GNU Public License
 * See the enclosed file COPYING for license information.
 * 
 * @package applications
 */

class MinichatConfig extends AppConfigModel {
	public function formFields () {
		$config = $this->app->getConfig();
		$emoticons = new Emoticons(null);
		$themes = array();
		$i = 0;
		foreach ($emoticons->get_emoticon_themes() as $theme) {
			$themes[$i] = $theme;
			$i++;
		}
		return array("maxlines" => array("type" => "int", "required" => true, "value" => $config["max"]["small"], "label" => _("NUMBEROFLINES"), "min" => 2, "max" => 40),
			"userichtext" => array("type" => "bool", "value" => $config["userichtext"]["small"], "label" => _("USERICHTEXT")),
			"inversepostorder" => array("type" => "bool", "value" => $config["inversepostorder"]["small"], "label" => _("INVERSEPOSTORDER")),
			"emoticon_theme" => array("type" => "enum", "values" => $themes, "value" => $config["emoticon_theme"]["small"], "label" => _("EMOTICONTHEME"))
		);
	}
}

?>
