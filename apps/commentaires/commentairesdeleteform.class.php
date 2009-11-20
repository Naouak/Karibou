<?php

/**
 *@copyright 2009 Gilles DEHAUDT <tonton1728@gmail.com>
 **/

/**
 *@package Application
 **/

class CommentairesDeleteForm extends FormModel {
	public function build () {
// 		$delete = filter_input(INPUT_POST,"delete",FILTER_SANITIZE_NUMBER_INT);
		$id = filter_input(INPUT_POST,"id",FILTER_SANITIZE_NUMBER_INT);



		if( $id != false) {
			$sql = $this->db->prepare("UPDATE comment SET deleted=1 WHERE id=:id");
			$sql->bindValue(":id",$id);
			$sql->execute();
		}
	}
}