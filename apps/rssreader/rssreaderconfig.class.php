<?php
/**
 * @copyright 2009 Pierre Ducroquet <pinaraf@gmail.com>
 *
 * @license http://www.gnu.org/licenses/gpl.html GNU Public License
 * See the enclosed file COPYING for license information.
 * 
 * @package applications
 **/

class RSSReaderConfig extends AppConfigModel
{
	public function formFields ()
	{
		return array(
			"feed" => array("type" => "url", "required" => true, "label" => _("Feed url")),
			"max" => array("type" => "int", "required" => true, "label" => _("Displayed articles"), "value" => 5, "min" => 2, "max" => 10)
			);
	}
}

?>
