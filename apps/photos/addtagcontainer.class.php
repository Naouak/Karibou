<?php 
/**
 *@copyright 2009 Gilles DEHAUDT <tonton1728@gmail.com>
 **/

/**
 *@package Applications
 **/

class AddTagContainer extends Model {
	public function build() {
		$this->assign("parent",$this->args["parent"]);
		$this->assign("type",$this->args["type"]);
		$container = containerFactory::getInstance();
		$objalbum = $container->getPictureStorage($this->args["parent"]);
		$this->assign("tags",$objalbum->getAllTags());
		$sql = $this->db->prepare("SELECT * FROM pictures_tags;");
		$sql->execute();
		$this->assign("alltags",$sql->fetchAll());
	}
}