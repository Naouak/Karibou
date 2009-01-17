<?php 
/**
 * @copyright 2009 Pierre Quételart
 *
 * @license http://www.gnu.org/licenses/gpl.html GNU Public License
 * See the enclosed file COPYING for license information.
 * 
 * @package applications
 **/

class DaytofConfig extends AppConfigModel
{
    public function formFields()
    {
	    return array("maxtof" => array("type" => "int","required" => false,"label" => _("MAXTOF") , "value" => "3", "min" => 1, "max" => 10));
    }
}

?>