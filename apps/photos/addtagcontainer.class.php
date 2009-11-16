<?php 
/**
 *@copyright 2009 Gilles DEHAUDT <tonton1728@gmail.com>
 **/

/**
 *@package Applications
 **/

class AddTagContainer extends PhotosModel {
	public function build() {
		$this->assign("parent",$this->args["parent"]);
		$this->assign("type",$this->args["type"]);
		if ($this->args["type"]=="album" || $this->args["type"]=="carton"){
			$container = containerFactory::getInstance();
			$objalbum = $container->getPictureStorage($this->args["parent"]);
			$this->assign("tags",$objalbum->getAllTags());
		}
		elseif($this->args["type"] == "photos"){
			$photos = new photos($this->args["parent"]);
			$this->assign("tags",$photos->getAllTags());
		}
		$this->assign("alltags",$this->getAllTags());
	}
}