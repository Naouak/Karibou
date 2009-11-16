<?php
/**
 *@copyright 2009 Gilles DEHAUDT
 */

/**
 *@package Applications
 **/

abstract class PhotosFormModel extends FormModel {
	/**
	 *@param String $tags
	 *@param String $type
	 *@param Int $id
	 **/
	public function insertNewTags($tags,$type,$id)  {
		
		$array_tags = explode(",",$tags);

		// $sql insert the tags if they doesn't exist
		$sql = $this->db->prepare("INSERT IGNORE INTO pictures_tags SET `name` = :name;");
		$sql->bindParam(":name",$tag);
		if ($type=="carton" || $type="album"){
			//$stmt insert the association of tags and album
			$stmt = $this->db->prepare("INSERT IGNORE INTO pictures_album_tagged (id_album,id_tag) (SELECT :id,t.id FROM pictures_tags as t where t.name=:name);");
		}
		elseif ($type=="photos") {

		}
		else {
			return;
		}
			$stmt->bindValue(":id",$id);
			$stmt->bindParam(":name",$tag);

		foreach($array_tags as $tag) {
			$sql->execute();

			$stmt->execute();
		}
	}

	/**
	 *@param Int $album
	 **/

    public function addPicture($album) {
		$stmt = $this->db->prepare("INSERT INTO pictures (album,`date`) VALUES (:album,NOW())");
		$stmt->bindValue(":album",$album);
		$stmt->execute();
		return $this->db->lastInsertId();
    }
}