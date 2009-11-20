<?php

/**
 *@copyright 2009 Gilles DEHAUDT <tonton1728@gmail.com>
 **/

/**
 *@package Application
 **/

class CommentairesModify extends Model {
	public function build() {
		$sql = $this->db->prepare("SELECT comment FROM comment WHERE id=:id;");
		$sql->bindValue(":id",$this->args["id"]);
		$sql->execute();
		$this->assign("tomodify",$sql->fetch());
		$this->assign("id",$this->args["id"]);
		$this->assign("parent",$this->args["parent"]);
	}
}