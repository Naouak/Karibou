<?php
/**
 * @copyright 2008 Gilles DEHAUDT <tonton1728@gmail.com>
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
//this is the class which permits to create the submit system
class rumoursubmit extends AppContentModel
{
    // this fonction permits to use the information the user type
	public function submit($parameters)
	{
        $stmt = $this->db->prepare("INSERT INTO rumours (rumours,`date`) VALUES (:secret, NOW());");
        $stmt->bindValue(":secret",$parameters["rumour"]);
        $stmt->execute();
	}
	// this fonction permits to define what information who want, what type they are, if they are needed or not, ...
	public function formFields()
	{
        return (array("rumour"=>array("type"=>"textarea","required"=>true,"label"=>_("saisis ta rumeur"),"columns" => 30, "rows" => 8)));
	}
}
