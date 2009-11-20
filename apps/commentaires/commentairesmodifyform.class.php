<?php

/**
 *@copyright 2009 Gilles DEHAUDT <tonton1728@gmail.com>
 **/

/**
 *@package Application
 **/

class CommentairesModifyForm extends FormModel {
	public function build() {
		$id = filter_input(INPUT_POST,"id",FILTER_SANITIZE_NUMBER_INT);
		$parent = filter_input(INPUT_POST,"parent",FILTER_SANITIZE_NUMBER_INT);
		$comment = filter_input(INPUT_POST,"comment",FILTER_SANITIZE_STRIPPED);

		if($id != false and $parent != false and $comment != false) {
			$sql = $this->db->prepare("UPDATE comment SET comment=:comment WHERE id=:id");
			$sql->bindValue(":comment",$comment);
			$sql->bindValue(":id",$id);
			$sql->execute();
		}

		$this->setRedirectArg("id",$parent);
	}
}