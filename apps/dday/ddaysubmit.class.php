<?php

class DDaySubmit extends AppContentModel {

	public function isModifiable($key) {
		if ($this->app->getPermission() == _ADMIN_) {
			return true;                         
		}                                            
		$query = $this->db->prepare("SELECT user_id FROM dday WHERE id=:id");
		$query->bindValue(":id", $key);                                            
		if (!$query->execute()) {                                                  
			Debug::kill("Error while checking rights");                        
		} else {                                                                   
			$row = $query->fetch();                                            
			if ($row[0] == $this->currentUser->getID())                        
				return true;                                               
		}                                                                          
		return false;                                                              
	}

	public function modify ($key, $parameters) {
		$query = $this->db->prepare("UPDATE dday SET `event`=:event, `date`=:date, `link`=:link, `desc`=:desc WHERE id=:id LIMIT 1");
		$query->bindValue(':event', $parameters["event"]);
		$query->bindValue(':date', $parameters["date"]);
		$query->bindValue(':link', $parameters["url"]);
		$query->bindValue(':desc', $parameters["description"]);
		$query->bindValue(":id", intval($key));                                                                             
		if (!$query->execute()) {                                                                                           
			Debug::kill("Error while updating");                                                                        
		}                 
	}

	public function fillFields($key, &$fields) {
		$query = $this->db->prepare("SELECT `event`, DATE_FORMAT(`date`, \"%d/%m/%Y\") AS rdate, `link`, `desc` FROM dday WHERE id=:id");
		$query->bindValue(":id", intval($key));
		if (!$query->execute()) {
			Debug::kill("Error while filling fields");
		} else {
			$row = $query->fetch();
			$fields["event"]["value"] = $row["event"];
			$fields["date"]["value"] = $row["rdate"];
			$fields["description"]["value"] = $row["desc"];
			$fields["url"]["value"] = $row["link"];
		}
	}

	public function submit($parameters) {
		$ddaydate = str_replace("-", "", $parameters["date"]);
		if ($ddaydate >= date('Ymd'))
		{
			$stmt = $this->db->prepare('INSERT INTO dday (user_id, event, date, link, `desc`) VALUES (:user, :event, :date, :link, :desc)');
			$stmt->bindValue(':user', $this->currentUser->getID());
			$stmt->bindValue(':event', $parameters["event"]);
			$stmt->bindValue(':date', $parameters["date"]);
			$stmt->bindValue(':link', $parameters["url"]);
			$stmt->bindValue(':desc', $parameters["description"]);
			$stmt->execute();
		}		
	}

	public function formFields () {
		return array("event" => array("type" => "text", "required" => true, "label" => "Evènement : "),
			"date" => array("type" => "date", "required" => true, "label" => "Date : "),
			"description" => array("type" => "text", "label" => "Description : "),
			"url" => array("type" => "url", "label" => "Lien : "));
	}
}

?>
