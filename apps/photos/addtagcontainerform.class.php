<?php
/* 
 *@copyright 2009 Gilles DEHAUDT <tonton1728@gmail.com>
 */

class AddTagContainerForm extends formModel {
	public function build() {
		$tags = filter_input(INPUT_POST,"tag",FILTER_SANITIZE_SPECIAL_CHARS);
		$array_tags = explode(",",$tags);

		$sql = $this->db->prepare("INSERT IGNORE INTO pictures_tags SET `name` = :name;");

		foreach($array_tags as $tag) {
			$sql->bindValue(":name",$name);
			$sql->execute();
		}
	}
}
