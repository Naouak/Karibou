<?php

/**
 *@copyright 2009 Gilles DEHAUDT <tonton1728@gmail.com>
 **/

/**
 *@package Application
 **/

abstract class PhotosModel extends Model {
    function getAllTags() {
		$sql = $this->db->prepare("SELECT * FROM pictures_tags;");
		$sql->execute();
		return $sql->fetchAll();
	}
}
