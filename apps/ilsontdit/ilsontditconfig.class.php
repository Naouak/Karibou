<?php
/**
 * @copyright 2008 Pinaraf <pinaraf@pinaraf.info>
 *
 * @license http://www.gnu.org/licenses/gpl.html GNU Public License
 * See the enclosed file COPYING for license information.
 * 
 * @package applications
 **/

class IlsontditConfig extends AppConfigModel
{
	public function formFields()
	{
		return array("maxquotes" => array("type" => "int", "min" => 1, "max" => 10, "label" => _("Number of quotes to display :")));
	}
}

?>
