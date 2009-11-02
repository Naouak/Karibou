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
	public function isModifiable($key) {
		if ($this->app->getPermission() == _ADMIN_) {
			return true;
		}
		return false;
	}

	public function modify ($key, $parameters) {
		$query = $this->db->prepare("UPDATE rumours SET `rumours`=:rumours WHERE id=:id LIMIT 1");
		$query->bindValue(":rumours", $parameters["rumours"]);
		$query->bindValue(":id", intval($key));
		if (!$query->execute()) {
			Debug::kill("Error while updating");
		}
	}

	public function delete ($key) {
		$query = $this->db->prepare("UPDATE rumours SET `deleted`=1 WHERE id=:id LIMIT 1");
		$query->bindValue(":id", intval($key));
		if (!$query->execute()) {
			Debug::kill("Error while updating");
		}
	}

	public function fillFields($key, &$fields) {
		$query = $this->db->prepare("SELECT `rumours` FROM rumours WHERE id=:id");
		$query->bindValue(":id", intval($key));
		if (!$query->execute()) {
			Debug::kill("Error while filling fields");
		} else {
			$row = $query->fetch();
			$fields["rumours"]["value"] = $row["rumours"];
		}
	}
	
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
