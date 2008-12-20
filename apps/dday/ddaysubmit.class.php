<?php

class DDaySubmit extends AppContentModel {

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
