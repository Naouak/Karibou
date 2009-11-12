<?php
/**
 *@copyright 2009 Gilles DEHAUDT <tonton1728@gmail.com>
 */

class AddTagContainerForm extends FormModel {
	public function build() {
		$tags = filter_input(INPUT_POST,"tag",FILTER_SANITIZE_SPECIAL_CHARS);
		$id = filter_input(INPUT_POST,"id",FILTER_SANITIZE_NUMBER_INT);

		
		$type = filter_input(INPUT_POST,"type",FILTER_SANITIZE_SPECIAL_CHARS);
		$array_tags = explode(",",$tags);

		// $sql insert the tags if they doesn't exist
		$sql = $this->db->prepare("INSERT IGNORE INTO pictures_tags SET `name` = :name;");
		$sql->bindParam(":name",$tag);

		//$stmt insert the association of tags and album
		$stmt = $this->db->prepare("INSERT IGNORE INTO pictures_album_tagged (id_album,id_tag) (SELECT :id,t.id FROM pictures_tags as t where t.name=:name);");
		$stmt->bindValue(":id",$id);
		$stmt->bindParam(":name",$tag);

		foreach($array_tags as $tag) {
			$sql->execute();

			$stmt->execute();
		}
 		$this->setRedirectArg("page",$type);
		$this->setRedirectArg("id",$id);
	}
}
