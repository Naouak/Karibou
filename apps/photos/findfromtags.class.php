<?php

/**
 *@copyright 2009 Gilles DEHAUDT <tonton1728@gmail.com>
 **/

/**
 *@package Application
 **/

class FindFromTags extends PhotosModel {
    public function build() {
		$this->assign("alltags",$this->getAllTags());
	}
}
