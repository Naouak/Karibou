<?php

/**
 * @copyright 2009 Pierre Ducroquet <pinaraf@gmail.com>
 *
 * See the enclosed file COPYING for license information (GPL). If you
 * did not receive this file, see http://www.gnu.org/licenses/gpl.html.
 *
 */

class NewsConfig extends AppConfigModel {
	public function formFields () {
		return array("max" => array("type" => "int", "value" => 3, "label" => _("NUMBEROFNEWS"), "required" => true));
	}
}

?>
