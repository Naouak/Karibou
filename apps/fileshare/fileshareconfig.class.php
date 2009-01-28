<?php

class FileShareConfig extends AppConfigModel {

	public function formFields() {
		return array(
			"maxfilesadd" => array("type" => "int", "min" => 1, "max" => 15, "value" => 3, "label" => _("NUMBEROFFILESLASTADDED")),
			"maxfilesdown" => array("type" => "int", "min" => 1, "max" => 15, "value" => 3, "label" => _("NUMBEROFFILESMOSTDOWNLOAD"))
			);
	}
}

?>
