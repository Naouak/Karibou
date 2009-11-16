<?php
/**
 *@copyright 2009 Gilles DEHAUDT <tonton1728@gmail.com>
 **/

/**
 *@package Application
 **/

class DisplayPhoto extends Model {
	public function build() {
		$photo = new photos($this->args["id"]);
		$this->assign("photo",$photo->getAll());
	}
}