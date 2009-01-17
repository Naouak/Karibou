<?php 
/**
 * @copyright 2007 Charles Anssens
 *
 * @license http://www.gnu.org/licenses/gpl.html GNU Public License
 * See the enclosed file COPYING for license information.
 * 
 * @package applications
 **/

/** 
 * 
 * @package applications
 **/
class DeezerConfig extends AppConfigModel
{
    public function formFields()
    {
	    return array("deezer_path" => array("type" => "int", "required" =>true, "label" => _("deezerpath")), "deezer_id" => array("type" => "int", "required" => true, "label" => _("deezerid")));
    }
}

?>