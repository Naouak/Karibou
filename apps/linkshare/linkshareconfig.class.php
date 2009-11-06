<?php
/**
 * @copyright 2009 Gilles Dehaudt <tonton1728@gmail.com>
 *
 * @license http://www.gnu.org/licenses/gpl.html GNU Public License
 * See the enclosed file COPYING for license information.
 *
 * @package applications
 **/

class linkshareConfig extends AppConfigModel
{
	public function formFields()
	{
		return array("maxlink" => array("type" => "int", "min" => 1, "max" => 10, "value" => 3, "label" => _("Number of link to display :")));
	}
}

?>
