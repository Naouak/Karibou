<?php 
/**
 * @copyright 2008 Gilles Dehaudt
 *
 * @license http://www.gnu.org/licenses/gpl.html GNU Public License
 * See the enclosed file COPYING for license information.
 * 
 * @package applications
 **/

class AnnonceConfig extends AppConfigModel
{
    public function formFields()
    {
	    return array("maxannonce" => array("type" => "int","required" => false,"label" => _(MAXANNONCE) , "value" => "5"));
    }
}

?>