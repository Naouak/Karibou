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
	 * this function insert tags in function of the type of the object tagged and his id
	 **/
	public function insertNewTags($tags,$type,$id)  {
		
		$array_tags = explode(",",$tags);

		// $sql insert the tags if they doesn't exist
		$sql = $this->db->prepare("INSERT IGNORE INTO pictures_tags SET `name` = :name;");
		$sql->bindParam(":name",$tag);
		if ($type=="carton" || $type=="album"){
			//$stmt insert the association of tags and album
			$container = containerFactory::getInstance();
			$album = $container->getPictureStorage($id);
			$req = "INSERT IGNORE 
					INTO pictures_album_tagged 
					(id_album,id_tag) 
					(SELECT 
						a.id,
						(
							SELECT t.id 
							FROM pictures_tags AS t 
							WHERE t.name=:name 
						)
						FROM pictures_album AS a
						WHERE
							a.left >= :left 
						AND
							a.right <= :right
					);";
			$stmt = $this->db->prepare($req);
			$stmt->bindValue(":left",$album->getLeft());
			$stmt->bindValue(":right",$album->getRight());
			//This add tag for the pictures when we add to the parent
			$req2 = "INSERT IGNORE 
					INTO pictures_tagged 
					(pict,tag) 
					(SELECT 
						p.id,
						(
							SELECT t.id 
							FROM pictures_tags AS t 
							WHERE t.name=:name 
						)
						FROM pictures AS p
						LEFT JOIN pictures_album AS a ON a.id=p.album
						WHERE
							a.left >= :left 
						AND
							a.right <= :right
						AND a.type=:type
					);";
			$stmt2  = $this->db->prepare($req2);
			$stmt2->bindValue(":left",$album->getLeft());
			$stmt2->bindValue(":right",$album->getRight());
			$stmt2->bindValue(":type","album");
		}
		elseif ($type=="photos") {
			$stmt = $this->db->prepare("INSERT IGNORE INTO pictures_tagged (pict,tag) (SELECT :id,t.id FROM pictures_tags as t WHERE t.name=:name);");
			$stmt->bindValue(":id",$id);
		}
		else {
			return;
		}
			
			$stmt->bindParam(":name",$tag);

		foreach($array_tags as $tag) {
			$sql->execute();

			$stmt->execute();
			
			if ($type=="carton" || $type=="album")
				$stmt2->execute();
		}
	}

	/**
	 *@param Array $tags
	 *@param String $type
	 *@param Int $id
	 * This function inserts tags from their ids and the type and type of the object. For now, this function is used when we create a new object (carton, album or pictures) to add the parents' tags at the new child
	 **/

	public function insertNewTagsFromId($tags,$type,$id)  {

		if ($type=="carton" || $type=="album"){
			//$stmt insert the association of tags and album
			$container = containerFactory::getInstance();
			$album = $container->getPictureStorage($id);
			$req = "INSERT IGNORE 
					INTO pictures_album_tagged 
					(id_album,id_tag) 
					(SELECT 
						a.id,
						:tagid
						FROM pictures_album AS a
						WHERE
							a.left >= :left 
						AND
							a.right <= :right
					);";
			$stmt = $this->db->prepare($req);
			$stmt->bindValue(":left",$album->getLeft());
			$stmt->bindValue(":right",$album->getRight());
		}
		elseif ($type=="photos") {
			$stmt = $this->db->prepare("INSERT IGNORE INTO pictures_tagged (pict,tag) VALUES (:id,:tagid);");
			$stmt->bindValue(":id",$id);
		}
		else {
			return;
		}
			
			
		foreach($tags as $tag) {
			$stmt->bindValue(":tagid",$tag["id_tag"]);
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