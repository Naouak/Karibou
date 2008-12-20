<?php
/**
 * @copyright 2008 Pinaraf <pinaraf@pinaraf.info>
 *
 * @license http://www.gnu.org/licenses/gpl.html GNU Public License
 * See the enclosed file COPYING for license information.
 * 
 * @package applications
 **/

class MyNotesConfig extends AppConfigModel
{
	public function formFields()
	{
		return array("notes" => array("type" => "textarea"));
	}
}

?>
