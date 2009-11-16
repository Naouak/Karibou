<?php
/**
 * @copyright 2009 Gilles DEHAUDT <tonton1728@gmail.com>
 *
 * @license http://www.gnu.org/licenses/gpl.html GNU Public License
 * See the enclosed file COPYING for license information.
 *-
 * @package applications
 **/

class AddContainerForm extends PhotosFormModel {
	public function build() {
		// We retrieve all data from the form and we filter this data to avoid problems
		$name = filter_input(INPUT_POST,"name", FILTER_SANITIZE_SPECIAL_CHARS);
		$type=filter_input(INPUT_POST,"type",FILTER_SANITIZE_SPECIAL_CHARS);
		$parent=filter_input(INPUT_POST,"parent",FILTER_SANITIZE_NUMBER_INT);
		$tags = filter_input(INPUT_POST,"tags",FILTER_SANITIZE_SPECIAL_CHARS);

		// we create the new container
		$stmt = $this->db->prepare("INSERT INTO pictures_album (`name`,`type`,`parent`) VALUES (:name,:type,:parent);");
		$stmt->bindValue(":name",$name);
		$stmt->bindValue(":parent",$parent);
		$stmt->bindValue(":type",$type);
		$stmt->execute();
		
		$id = $this->db->lastInsertId();

		if ($id != 0)
			$this->insertNewTags($tags,$type,$id);

		

		// this permits to Redirect to the page written in the config.xml with this argument after the submit
		$this->setRedirectArg("id",$parent);

	}
}
