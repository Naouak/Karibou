<?php

/**
 *@copyright 2009 Gilles DEHAUDT <tonton1728@gmail.com>
 **/

/**
 *This class extends the Model Class to allow every class from photos to have some common function
 *@package Application
 **/

abstract class PhotosModel extends Model {
	/**
	 *This function return all existing tags
	 **/
    function getAllTags() {
		$sql = $this->db->prepare("SELECT * FROM pictures_tags;");
		$sql->execute();
		return $sql->fetchAll();
	}
}
