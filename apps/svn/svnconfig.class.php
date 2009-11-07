<?php 
/**
 * @copyright 2009 Pierre Ducroquet <pinaraf@pinaraf.info>
 *
 * @license http://www.gnu.org/licenses/gpl.html GNU Public License
 * See the enclosed file COPYING for license information.
 * 
 * @package applications
 **/
// this is the file which permits to have the configuration system
class svnconfig extends AppConfigModel
{

	public function formFields()
	{
		// in this return, you should type, the configuration variable and if they are needed and what type they are
		return array("count" => array("type" => "int", "min" => 1, "max" => 10, "value" => 4, "label" => _("Number of log entry to display")));
	}
}

?>
