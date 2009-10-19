<?php 
/**
 * @copyright 2009 Gilles DEHAUDT
 *
 * @license http://www.gnu.org/licenses/gpl.html GNU Public License
 * See the enclosed file COPYING for license information.
 * 
 * @package applications
 **/
// this is the file which permits to have the configuration system
class rumourconfig extends AppConfigModel
{

    public function formFields()
    {

        // in this return, you should type, the configuration variable and if they are needed and what type they are
	    return array("number"=>array("type"=>"int","required"=>false, "label" => _("nombre de rumeurs"), "value"=> "5"));
    }
}

?>
